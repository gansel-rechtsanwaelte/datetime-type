<?php

declare(strict_types=1);

namespace Gansel\DateTime\Bridge\Symfony\Serializer\Normalizer;

use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DateTimeZoneNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function __construct()
    {
    }

    /**
     * @param \DateTimeZone $object
     */
    public function normalize($object, $format = null, array $context = []): string
    {
        return $object->getName();
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof \DateTimeZone;
    }

    /**
     * @throws UnexpectedValueException
     */
    public function denormalize($data, $class, $format = null, array $context = []): \DateTimeZone
    {
        try {
            return new \DateTimeZone($data);
        } catch (\Exception $e) {
            throw new UnexpectedValueException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return \DateTimeZone::class === $type;
    }
}
