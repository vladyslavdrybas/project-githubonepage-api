<?php

declare(strict_types=1);

namespace App\Builder;

use App\Entity\ApiKey;
use App\Utility\RandomGenerator;

class ApiBuilder implements IEntityBuilder
{
    public function base(
        string $owner,
        string $project,
        ?string $title = null,
        int $ttl = 0
    ): ApiKey {
        $rndGen = new RandomGenerator();
        $hex = $rndGen->sha256($owner . $project);

        if (null === $title) {
            $title = $rndGen->uniqueId('k');
        }

        $apiKey = new ApiKey();
        $apiKey->setOwner($owner);
        $apiKey->setProject($project);
        $apiKey->setApiKey($hex);
        $apiKey->setTitle($title);

        if ($ttl > 0) {
            $endDate = new \DateTime();
            $endDate->modify('+'.$ttl.' seconds');
            $apiKey->setEndDate($endDate);
        }

        return $apiKey;
    }
}
