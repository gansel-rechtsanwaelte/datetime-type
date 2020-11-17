<?php

declare(strict_types=1);

namespace Gansel\DateTime\Bridge\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType;

class DateTimeImmutableUtcType extends DateTimeType
{
    /**
     * @var string
     */
    public const NAME = 'gansel_datetime_immutable_utc';

    /**
     * @var \DateTimeZone
     */
    private static $utc = null;

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof \DateTimeImmutable) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', \DateTimeImmutable::class]);
        }

        if ('UTC' !== $value->getTimezone()->getName()) {
            null === self::$utc && self::$utc = new \DateTimeZone('UTC');
            $value = $value->setTimezone(self::$utc);
        }

        return $value->format($platform->getDateTimeFormatString());
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?\DateTimeImmutable
    {
        if (null === $value) {
            return null;
        }

        self::$utc || self::$utc = new \DateTimeZone('UTC');

        if ($value instanceof \DateTimeImmutable) {
            return $value->setTimezone(self::$utc);
        }

        $date = \DateTimeImmutable::createFromFormat($platform->getDateTimeFormatString(), $value, self::$utc);
        $date || $date = date_create_immutable($value, self::$utc);

        if (!$date) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), $platform->getDateTimeFormatString());
        }

        return $date;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
