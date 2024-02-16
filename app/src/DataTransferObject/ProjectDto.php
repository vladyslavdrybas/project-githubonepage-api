<?php

declare(strict_types=1);

namespace App\DataTransferObject;

use App\Entity\Subscription;
use App\Entity\User;
use DateTime;

class ProjectDto implements IDataTransferObject
{
    public function __construct(
        public ?string $id = null,
        public ?string $title = null,
        public ?string $description = null,
        public ?User $owner = null,
        public ?Subscription $subscription = null,
        public ?DateTime $createdAt = null,
        public ?DateTime $updatedAt = null,
    ) {
    }
}
