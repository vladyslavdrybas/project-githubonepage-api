<?php

namespace App\Controller\OAuth;

use App\Controller\AbstractController;
use App\Entity\OAuthHash;
use App\Repository\OAuthHashRepository;
use App\Utility\EmailHasher;
use App\Utility\RandomGenerator;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HashGeneratorController extends AbstractController
{
    #[Route("/hash/oauth/user", name: "oauth_hash_user", methods: ["POST"])]
    public function generateForUser(
        RandomGenerator $randomGenerator,
        OAuthHashRepository $repository,
        EmailHasher $emailHasher
    ): JsonResponse {
        $user = $this->getUser();
        $emailHash = $emailHasher->hash($user->getEmail());

        $hash = $repository->findOneBy(['email' => $emailHash]);
        if (null !== $hash) {
            $repository->remove($hash);
            $repository->save();
        }

        $oauthHashLifetime = $this->getParameter('oauth_hash_lifetime') ?? 600;

        do {
            $hash = $randomGenerator->sha256($user->getRawId());
            $existedHash = $repository->findOneBy(['hash' => $hash]);
        } while (null !== $existedHash);

        $oAuthHash = new OAuthHash();
        $oAuthHash->setHash($hash);
        $oAuthHash->setEmail($emailHash);
        $oAuthHash->setExpireAt(new DateTime('+'. $oauthHashLifetime .' sec'));

        $repository->add($oAuthHash);
        $repository->save();

        return new JsonResponse(['hash' => $hash]);
    }

    #[Route("/oauth/hash/guest", name: "oauth_hash_guest", methods: ["POST"])]
    public function generateForGuest(
        RandomGenerator $randomGenerator,
        OAuthHashRepository $repository
    ): JsonResponse {
        $oauthHashLifetime = $this->getParameter('oauth_hash_lifetime') ?? 600;

        do {
            $hash = $randomGenerator->sha256();
            $existedHash = $repository->findOneBy(['hash' => $hash]);
        } while (null !== $existedHash);

        $oAuthHash = new OAuthHash();
        $oAuthHash->setHash($hash);
        $oAuthHash->setExpireAt(new DateTime('+'. $oauthHashLifetime .' sec'));

        $repository->add($oAuthHash);
        $repository->save();

        return new JsonResponse(['hash' => $hash]);
    }
}
