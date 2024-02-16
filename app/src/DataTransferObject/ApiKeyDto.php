<?php

declare(strict_types=1);

namespace App\DataTransferObject;

use App\Entity\Project;
use App\Entity\User;

readonly class ApiKeyDto implements IDataTransferObject
{
    public function __construct(
        public User $owner,
        public Project $project,
        public ?string $title = null,
        public int $ttl = 0
    ) {}
}
