<?php

namespace App\Controller\OAuth;

use JetBrains\PhpStorm\NoReturn;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\GithubClient;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/oauth/github', name: "oauth_github")]
class GithubAuthController extends AbstractController
{
    /**
     * Link to this controller to start the "connect" process
     */
    #[Route("/connect", name: "_connect", methods: ["GET"])]
    public function connectAction(ClientRegistry $clientRegistry): RedirectResponse
    {
        // will redirect to Facebook!
        return $clientRegistry
            ->getClient('github') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect([
                'read:user',
            ]);
    }

    /**
     * After going to Github, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     */
    #[NoReturn] #[Route("/connect/check", name: "_connect_check", methods: ["GET"])]
    public function  connectCheckAction(Request $request, ClientRegistry $clientRegistry): JsonResponse
    {
        // ** if you want to *authenticate* the user, then
        // leave this method blank and create a Guard authenticator
        // (read below)

        /** @var GithubClient $client */
        $client = $clientRegistry->getClient('github');

        $token = $client->getAccessToken([
            'read:user'
        ]);

        // TODO Store token to db

        return $this->json([
            'message' => 'success',
            'token' => $token->getToken(),
        ], Response::HTTP_OK);
    }
}
