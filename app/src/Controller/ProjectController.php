<?php

declare(strict_types=1);

namespace App\Controller;

use App\Builder\ApiBuilder;
use App\DataTransferObject\ApiKeyCreateDto;
use App\Repository\ApiKeyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/project', name: "api_project")]
class ProjectController extends AbstractController
{
    #[Route('/apikey', name: '_apikey', methods: ["POST"])]
    public function index(
        #[MapRequestPayload] ApiKeyCreateDto $apiKeyCreateDto,
        ApiBuilder $builder,
        ApiKeyRepository $repo
    ): Response {
        $apiKey = $builder->baseApi($apiKeyCreateDto->owner, $apiKeyCreateDto->project);

        $repo->add($apiKey);
        $repo->save();

        return new Response('OK', Response::HTTP_OK);
    }
}
