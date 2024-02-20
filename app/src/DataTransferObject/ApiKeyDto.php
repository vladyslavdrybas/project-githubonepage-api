<?php

declare(strict_types=1);

namespace App\DataTransferObject;

use App\Entity\Project;
use App\Entity\User;

class ApiKeyDto implements IDataTransferObject
{
    public function __construct(
        public Project $project,
        public ?User $owner = null,
        public ?string $title = null,
        public int $ttl = 0,
        public int $costPerCall = 0
    ) {}
}
