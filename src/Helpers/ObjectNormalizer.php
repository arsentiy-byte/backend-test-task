<?php declare(strict_types=1);

namespace App\Helpers;

use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

class ObjectNormalizer
{
    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {
    }

    /**
     * @param object $object
     * @param list<string> $groups
     * @return array<array-key, mixed>|string|int|float|bool|\ArrayObject<string, mixed>|null
     */
    public function normalizeUsingGroups(object $object, array $groups = []): array|string|int|float|bool|\ArrayObject|null
    {
        $context = (new ObjectNormalizerContextBuilder())->withGroups($groups)->toArray();

        /** @phpstan-ignore-next-line */
        return $this->serializer->normalize($object, null, $context);
    }
}
