<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\Project;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class ProjectNormalizer extends AbstractEntityNormalizer
{

    /**
     * @param Project $object
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
                ],
                AbstractNormalizer::IGNORED_ATTRIBUTES => [
                    'rawId',
                ],
            ]
        );

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Project;
    }

    /**
     * @inheritDoc
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            Project::class => true,
        ];
    }
}
