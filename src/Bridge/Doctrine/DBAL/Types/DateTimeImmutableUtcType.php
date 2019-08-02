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
    const NAME = 'gansel_datetime_immutable_utc';

    /**
     * @var \DateTimeZone
     */
    private static $utc = null;

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\DBAL\Types\DateTimeType::getName()
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @see \Doctrine\DBAL\Types\DateTimeType::convertToDatabaseValue()
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof \DateTimeImmutable) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), [
                'null',
                \DateTimeImmutable::class,
            ]);
        }

        if ($value->getTimezone()->getName() !== 'UTC') {
            self::$utc === null && self::$utc = new \DateTimeZone('UTC');
            $value = $value->setTimezone(self::$utc);
        }

        return $value->format($platform->getDateTimeFormatString());
    }

    /**
     * @see \Doctrine\DBAL\Types\DateTimeType::convertToPHPValue()
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?\DateTimeImmutable
    {
        if ($value === null) {
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

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\DBAL\Types\Type::requiresSQLCommentHint()
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
