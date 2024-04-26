<?php

namespace App\Controller\OAuth;

use App\Builder\UserBuilder;
use App\DataTransferObject\AccessTokenDto;
use App\DataTransferObject\EmailDto;
use App\DataTransferObject\GithubUserDto;
use App\Entity\GithubAccessToken;
use App\Entity\OAuthHash;
use App\Entity\User;
use App\Repository\GithubAccessTokenRepository;
use App\Repository\UserRepository;
use App\Utility\EmailHasher;
use App\Utility\RandomGenerator;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\GithubClient;
use App\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/oauth/github', name: "oauth_github")]
class GithubAuthController extends AbstractController
{
    protected const SESSION_OAUTH_HASH = 'oauth_github_hash';

    public function __construct(
        protected ClientRegistry $clientRegistry,
        protected GithubAccessTokenRepository $repo,
        protected HttpClientInterface $httpClient,
        protected EntityManagerInterface $entityManager,
        protected UrlGeneratorInterface $urlGenerator,
        protected SerializerInterface $serializer,
        protected UserBuilder $userBuilder,
        protected UserRepository $userRepository,
        protected RandomGenerator $randomGenerator,
        protected LoggerInterface $logger,
        protected EmailHasher $emailHasher
    ) {
        parent::__construct($entityManager, $urlGenerator, $serializer, $logger);
    }

    /**
     * Link to this controller to start the "connect" process
     */
    #[Route("/connect", name: "_connect", methods: ["GET"])]
    public function connectAction(Request $request): RedirectResponse {
        $hash = $this->getOAuthHash($request);
        if (null === $hash) {
            throw $this->createAccessDeniedException();
        }

        $session = $request->getSession();
        $session->set(static::SESSION_OAUTH_HASH, $hash->getHash());

        // will redirect to Github!
        return $this->clientRegistry
            ->getClient('github') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect([
                'read:user',
                'user:email',
            ]);
    }

    /**
     * After going to Github, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     * ** if you want to *authenticate* the user, then leave this method blank and create a Guard authenticator
     */
    #[NoReturn] #[Route("/connect/check", name: "_connect_check", methods: ["GET"])]
    public function  connectCheckAction(Request $request): JsonResponse
    {
        $hash = $this->getOAuthHash($request);
        if (null === $hash) {
            throw $this->createAccessDeniedException();
        }

        $userCreationEnable = $this->getParameter('oauth_create_user_enable') ?? false;
        if (!$userCreationEnable && null === $hash->getEmail()) {
            $this->logger->error('Creation a new user from oauth restricted');

            throw $this->createAccessDeniedException();
        }

        $accessTokenDto = $this->getGithubAccessToken();
        $githubUserDto = $this->getGithubUser($accessTokenDto);

        $responseData = [
            'message' => 'success',
        ];

        if ($userCreationEnable && null === $hash->getEmail()) {
            // create user from github
            $owner = $this->userBuilder->github($githubUserDto);
            //TODO generate hash for a new user to get JWT

            $this->userRepository->add($owner);
            $this->userRepository->save();
        } else {
            $githubUserIsInHash = $this->checkIfGithubEmailsAreInHash($githubUserDto, $hash);
            if (!$githubUserIsInHash) {
                throw $this->createAccessDeniedException();
            }

            $owner = $this->userRepository->loadByHashedEmail($hash->getEmail());
        }

        $this->storeAccessTokens($githubUserDto, $accessTokenDto, $owner, $hash);

        return $this->json($responseData, Response::HTTP_OK);
    }

    protected function checkIfGithubEmailsAreInHash(GithubUserDto $githubUserDto, OAuthHash $hash): bool
    {
        foreach ($githubUserDto->emails as $emailDto)
        {
            $gEmailHash = $this->emailHasher->hash($emailDto->email);
            $uEmailHash = $hash->getEmail();

            if ($gEmailHash === $uEmailHash) {
                $githubUserDto->email = $emailDto->email;

                return true;
            }
        }

        return false;
    }

    protected function getOAuthHash(Request $request): ?OAuthHash
    {
        $oauthHash = $request->query->get("authhash");
        if (null === $oauthHash) {
            $oauthHash =   $request->getSession()->get(static::SESSION_OAUTH_HASH);
        }

        if (null === $oauthHash) {
            $this->logger->error('Not found oauth hash in query');

            return null;
        }

        $hash = $this->entityManager->getRepository(OAuthHash::class)->findOneBy(['hash' => $oauthHash]);
        if (null === $hash) {
            $this->logger->error('Not found oauth hash in system');

            return null;
        }

        $diff = (new DateTime())->getTimestamp() - $hash->getExpireAt()->getTimestamp();
        $oauthHashLifetime = $this->getParameter('oauth_hash_lifetime') ?? 600;
        if ($oauthHashLifetime < $diff) {
            $this->logger->error('Oauth hash expired.');

            return null;
        }

        return $hash;
    }

    protected function getGithubAccessToken(): AccessTokenDto
    {
        /** @var GithubClient $client */
        $client = $this->clientRegistry->getClient('github');

        $githubAccessToken = $client->getAccessToken([
            'read:user',
            'user:email',
        ]);

        if ($githubAccessToken->getExpires() instanceof DateTimeInterface) {
            $diff = (new DateTime())->getTimestamp() - $githubAccessToken->getExpires();
            if (300 < $diff) {
                throw $this->createAccessDeniedException();
            }
        }

        return $this->serializer->denormalize($githubAccessToken->jsonSerialize(), AccessTokenDto::class);
    }

    protected function getGithubUser(AccessTokenDto $token): GithubUserDto
    {
        $detailsResponse = $this->httpClient->request(
            'GET',
            'https://api.github.com/user',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token->accessToken,
                ],
            ]
        );

        $emailResponse = $this->httpClient->request(
            'GET',
            'https://api.github.com/user/emails',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token->accessToken,
                ],
            ]
        );

        $data = $detailsResponse->toArray();

        $emailCollection = new ArrayCollection();
        foreach ($emailResponse->toArray() as $email)
        {
            if(!str_contains($email['email'], 'users.noreply.github.com')) {
                $emailCollection->add($this->serializer->denormalize($email, EmailDto::class));
            }
        }
        $data['emails'] = $emailCollection->toArray();

        return $this->serializer->denormalize($data, GithubUserDto::class);
    }

    protected function storeAccessTokens(
        GithubUserDto $githubUserDto,
        AccessTokenDto $accessTokenDto,
        User $owner,
        OAuthHash $OAuthHash
    ): void
    {
        $accessTokenRepo = $this->entityManager->getRepository(GithubAccessToken::class);
        $oAuthHashRepo = $this->entityManager->getRepository(OAuthHash::class);
        foreach ($githubUserDto->emails as $emailDto)
        {
            $token = new GithubAccessToken();
            $token->setAccessToken($accessTokenDto->accessToken);

            if ($accessTokenDto->expires instanceof DateTimeInterface) {
                $token->setExpireAt($accessTokenDto->expires);
            }

            $token->setOwner($owner);
            $token->setEmail($emailDto->email);
            $token->setFirstname($githubUserDto->name);
            $token->setUsername($githubUserDto->login);
            $token->setUserId((string) $githubUserDto->id);

            $metadata = [
                'user' => $this->serializer->normalize($githubUserDto),
                'token' => $this->serializer->normalize($accessTokenDto),
            ];

            $token->setMetadata($metadata);

            $existed = $accessTokenRepo->findOneBy([
                'owner' => $token->getOwner(),
                'email' => $token->getEmail(),
                'username' => $token->getUsername(),
            ]);

            if (null !== $existed) {
                $accessTokenRepo->remove($existed);
                $accessTokenRepo->save();
            }

            $accessTokenRepo->add($token);
        }

        $oAuthHashRepo->remove($OAuthHash);

        $this->entityManager->flush();
    }
}
