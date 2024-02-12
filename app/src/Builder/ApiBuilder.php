<?php

declare(strict_types=1);

namespace App\Builder;

use App\Entity\ApiKey;
use function bin2hex;
use function random_bytes;
use function sprintf;
use function uniqid;

class ApiBuilder implements IEntityBuilder
{
    public function base(
        string $owner,
        string $project,
        ?string $title = null,
        int $ttl = 0
    ): ApiKey {
        $hex = random_bytes(24);
        $hex = bin2hex($hex);
        $hex = hash('sha256',strtoupper($hex . $owner . $project . time()));

        if (null === $title) {
            $title = sprintf(
                '%s.%s'
                , uniqid('k', false)
                , bin2hex(random_bytes(3))
            );
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
