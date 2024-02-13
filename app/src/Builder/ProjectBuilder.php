<?php

declare(strict_types=1);

namespace App\Builder;

use App\DataTransferObject\ProjectDto;
use App\Entity\Project;
use App\Utility\RandomGenerator;

class ProjectBuilder implements IEntityBuilder
{
    public function base(ProjectDto $projectDto): Project
    {
        $rndGen = new RandomGenerator();
        $title = null === $projectDto->title ? $rndGen->uniqueId('p') : $projectDto->title;

        $project = new Project();
        $project->setOwner($projectDto->owner);
        $project->setTitle($title);
        $project->setDescription($projectDto->description);

        return $project;
    }
}
