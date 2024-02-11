<?php

declare(strict_types=1);

namespace App\DataTransferObject;

use Symfony\Component\Validator\Constraints as Assert;

readonly class ApiKeyCreateDto implements IDataTransferObject
{
    public function __construct(
        #[Assert\NotBlank]
        public string $owner,
        #[Assert\NotBlank]
        public string $project,
    ) {}
}
