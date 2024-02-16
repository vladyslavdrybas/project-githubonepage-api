<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\ApiKey;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class ApiKeyNormalizer extends AbstractEntityNormalizer
{

    /**
     * @param ApiKey $object
     * @inheritDoc
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $data = $this->normalizer->normalize(
            $object,
            $format,
            [
                AbstractNormalizer::CALLBACKS => [
                    'owner' => [$this, 'shortObject'],
                    'project' => [$this, 'shortObject'],
                ],
                AbstractNormalizer::IGNORED_ATTRIBUTES => [
                    'rawId',
                    'valid',
                    'subscriptionActive',
                ],
            ]
        );

        $data['isValid'] = $object->isValid();

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof ApiKey;
    }

    /**
     * @inheritDoc
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            ApiKey::class => true,
        ];
    }
}
