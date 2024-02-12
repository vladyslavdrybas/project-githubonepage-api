<?php

declare(strict_types=1);

namespace App\Builder;

use App\DataTransferObject\ProjectCreateDto;
use App\Entity\Project;
use App\Entity\User;
use App\Exceptions\AccessDenied;
use App\Utility\RandomGenerator;
use Symfony\Bundle\SecurityBundle\Security;

class ProjectBuilder implements IEntityBuilder
{
    public function __construct(
        protected readonly Security $security,
    ) {
    }

    public function base(ProjectCreateDto $projectCreateDto): Project
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new AccessDenied();
        }
        $rndGen = new RandomGenerator();

        $title = null === $projectCreateDto->title ? $rndGen->uniqueId('p') : $projectCreateDto->title;


        $project = new Project();
        $project->setOwner($user);
        $project->setTitle($title);
        $project->setDescription($projectCreateDto->description);

        return $project;
    }
}
