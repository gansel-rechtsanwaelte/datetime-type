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
     * {@inheritdoc}
     *
     * @param \DateTimeZone $object
     *
     * @see \Symfony\Component\Serializer\Normalizer\NormalizerInterface::normalize()
     */
    public function normalize($object, $format = null, array $context = []): string
    {
        return $object->getName();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Symfony\Component\Serializer\Normalizer\NormalizerInterface::supportsNormalization()
     */
    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof \DateTimeZone;
    }

    /**
     * {@inheritdoc}
     *
     * @throws UnexpectedValueException
     *
     * @see \Symfony\Component\Serializer\Normalizer\DenormalizerInterface::denormalize()
     */
    public function denormalize($data, $class, $format = null, array $context = []): \DateTimeZone
    {
        try {
            return new \DateTimeZone($data);
        } catch (\Exception $e) {
            throw new UnexpectedValueException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see \Symfony\Component\Serializer\Normalizer\DenormalizerInterface::supportsDenormalization()
     */
    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return \DateTimeZone::class === $type;
    }
}
