<?php

declare(strict_types=1);

namespace App\Builder;

use App\DataTransferObject\ApiKeyDto;
use App\Entity\ApiKey;
use App\Utility\RandomGenerator;

class ApiBuilder implements IEntityBuilder
{
    public function base(
        ApiKeyDto $apiKeyCreateDto
    ): ApiKey {
        $rndGen = new RandomGenerator();
        $hex = $rndGen->sha256($apiKeyCreateDto->owner->getRawId() . $apiKeyCreateDto->project->getRawId());

        $title = $apiKeyCreateDto->title;
        if (null === $title) {
            $title = $rndGen->uniqueId('k');
        }

        $apiKey = new ApiKey();
        $apiKey->setOwner($apiKeyCreateDto->owner);
        $apiKey->setProject($apiKeyCreateDto->project);
        $apiKey->setApiKey($hex);
        $apiKey->setTitle($title);

        if ($apiKeyCreateDto->ttl > 0) {
            $endDate = new \DateTime();
            $endDate->modify('+'.$apiKeyCreateDto->ttl.' seconds');
            $apiKey->setEndDate($endDate);
        }

        return $apiKey;
    }
}
