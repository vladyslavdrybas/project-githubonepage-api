<?php

declare(strict_types=1);

namespace App\DataTransferObject;

use App\Entity\User;
use DateTime;
use Symfony\Component\Uid\UuidV7;

class ProjectDto implements IDataTransferObject
{
    public function __construct(
        public ?UuidV7 $id = null,
        public ?string $title = null,
        public ?string $description = null,
        public ?User $owner = null,
        public ?DateTime $createdAt = null,
        public ?DateTime $updatedAt = null,
    ) {
    }
}
