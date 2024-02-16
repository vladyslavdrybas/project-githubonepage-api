<?php

declare(strict_types=1);

namespace App\Controller;

use App\Builder\ApiBuilder;
use App\Builder\ProjectBuilder;
use App\DataTransferObject\ApiKeyDto;
use App\DataTransferObject\ProjectDto;
use App\Entity\Project;
use App\Repository\ApiKeyRepository;
use App\Repository\ProjectRepository;
use App\Security\Permissions;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/project', name: "api_project")]
class ProjectController extends AbstractController
{
    #[Route('', name: '_create', methods: ["POST"])]
    public function create(
        #[MapRequestPayload] ProjectDto $projectDto,
        ProjectBuilder                  $builder,
        ProjectRepository               $repo
    ): Response {
        $user = $this->getUser();
        $projectDto->owner = $user;

        $project = $builder->base($projectDto);

        $repo->add($project);
        $repo->save();

        return $this->json($project);
    }

    #[Route('/{project}', name: '_read', methods: ["GET"])]
    #[IsGranted(Permissions::READ, 'project', 'Access denied', Response::HTTP_UNAUTHORIZED)]
    public function read(
        Project $project
    ): Response {
        return $this->json($project);
    }

    #[Route('/{project}', name: '_update', methods: ["PUT"])]
    #[IsGranted(Permissions::UPDATE, 'project', 'Access denied', Response::HTTP_UNAUTHORIZED)]
    public function update(
        #[MapRequestPayload] ProjectDto $projectDto,
        ProjectRepository $repo,
        Project $project
    ): Response {
        $isChanged = false;

        if (null !== $projectDto->title) {
            $project->setTitle($projectDto->title);
            $isChanged = true;
        }

        if (null !== $projectDto->description) {
            $project->setDescription($projectDto->description);
            $isChanged = true;
        }

        if ($isChanged) {
            $repo->add($project);
            $repo->save();
        }

        return $this->json($project);
    }

    #[Route('/apikey', name: '_apikey', methods: ["POST"])]
    public function apiKeyCreate(
        #[MapRequestPayload] ApiKeyDto $apiKeyCreateDto,
        ApiBuilder                     $builder,
        ApiKeyRepository               $repo
    ): Response {
        $apiKey = $builder->base($apiKeyCreateDto);

        $repo->add($apiKey);
        $repo->save();

        return new Response('OK', Response::HTTP_OK);
    }
}
