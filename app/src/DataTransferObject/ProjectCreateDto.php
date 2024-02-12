<?php

declare(strict_types=1);

namespace App\DataTransferObject;

readonly class ProjectCreateDto implements IDataTransferObject
{
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
    ) {
    }
}
