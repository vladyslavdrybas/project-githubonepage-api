<?php

namespace App\Controller\Metrics;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/metrics/github', name: "metrics_github")]
class GithubMetricsController extends AbstractController
{
    #[Route("/contributions", name: "_contributions", methods: ["GET"])]
    public function connectAction(Request $request, HttpClientInterface $httpClient): JsonResponse
    {
        // TODO get it from the user DB;
        $githubUsername = 'vladyslavdrybas';
        $githubAccessToken = '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';

        $query = '
            query($userName:String!) {
              user(login: $userName){
                contributionsCollection {
                  contributionCalendar {
                    totalContributions
                    weeks {
                      contributionDays {
                        contributionCount
                        date
                      }
                    }
                  }
                }
              }
            }
        ';

        $variables = '{"userName": "'. $githubUsername .'"}';

        $body = [
            'query' => $query,
            'variables' => $variables,
        ];

        $requestBody = json_encode($body);

        $response = $httpClient->request(
            'POST',
            'https://api.github.com/graphql',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $githubAccessToken,
                ],
                'body' => $requestBody,
            ]
        );


        return $this->json($response->toArray(), Response::HTTP_OK);
    }
}
