<?php

declare(strict_types=1);

namespace Gansel\DateTime;

use Webmozart\Assert\Assert;

/**
 * A set of pure functions for creating various date and time related objects.
 */
final class DateTimeFactory
{
    public static function toImmutable(\DateTimeInterface $date): \DateTimeImmutable
    {
        if ($date instanceof \DateTimeImmutable) {
            return $date;
        }

        Assert::isInstanceOf($date, \DateTime::class);

        return \DateTimeImmutable::createFromMutable($date);
    }

    public static function toNullableImmutable(?\DateTimeInterface $date): ?\DateTimeImmutable
    {
        if (null === $date) {
            return null;
        }

        return self::toImmutable($date);
    }

    public static function toMutable(\DateTimeInterface $date): \DateTime
    {
        if ($date instanceof \DateTime) {
            return $date;
        }

        $formatted = $date->format('Y-m-d H:i:s');
        $timezone = $date->getTimezone();

        return new \DateTime($formatted, $timezone);
    }

    public static function toNullableMutable(?\DateTimeInterface $date): ?\DateTime
    {
        if (null === $date) {
            return null;
        }

        return self::toMutable($date);
    }

    /**
     * @param \DateTimeInterface|string $date
     * @param \DateTimeZone|string|null $timezone
     */
    public static function createDateTime($date, $timezone = null): \DateTime
    {
        if ($date instanceof \DateTime) {
            $date = clone $date;
        } elseif ($date instanceof \DateTimeImmutable) {
            $date = self::toMutable($date);
        } else {
            $date = new \DateTime($date);
        }

        if (null === $timezone) {
            return $date;
        }

        $timezone = self::createTimezone($timezone);
        $date->setTimezone($timezone);

        return $date;
    }

    /**
     * @param \DateTimeInterface|string|null $date
     * @param \DateTimeZone|string           $timezone
     */
    public static function createNullableDateTime($date, $timezone = null): ?\DateTime
    {
        if (null === $date) {
            return null;
        }

        return self::createDateTime($date, $timezone);
    }

    /**
     * @param \DateTimeInterface|string $date
     * @param \DateTimeZone|string|null $timezone
     *
     * @return \DateTime
     */
    public static function createDateTimeImmutable($date, $timezone = null): \DateTimeImmutable
    {
        if ($date instanceof \DateTime) {
            $date = self::toImmutable($date);
        } elseif (!$date instanceof \DateTimeImmutable) {
            $date = new \DateTimeImmutable($date);
        }

        if (null === $timezone) {
            return $date;
        }

        $timezone = self::createTimezone($timezone);
        $date->setTimezone($timezone);

        return $date;
    }

    /**
     * @param \DateTimeInterface|string|null $date
     * @param \DateTimeZone|string           $timezone
     *
     * @return \DateTime|null
     */
    public static function createNullableDateTimeImmutable($date, $timezone = null): ?\DateTimeImmutable
    {
        if (null === $date) {
            return null;
        }

        return self::createDateTimeImmutable($date, $timezone);
    }

    /**
     * @param \DateTimeInterface|string $date
     * @param \DateTimeZone|string      $timezone
     */
    public static function createDateTimeOverwriteTimezone($date, $timezone = 'UTC'): \DateTime
    {
        if (!$date instanceof \DateTimeInterface) {
            $date = new \DateTime($date);
        }

        $formatted = $date->format('Y-m-d H:i:s');
        $timezone = self::createTimezone($timezone);

        return new \DateTime($formatted, $timezone);
    }

    /**
     * @param \DateTimeInterface|string|null $date
     * @param \DateTimeZone|string           $timezone
     */
    public static function createNullableDateTimeOverwriteTimezone($date, $timezone = 'UTC'): ?\DateTime
    {
        if (null === $date) {
            return null;
        }

        return self::createDateTimeOverwriteTimezone($date, $timezone);
    }

    /**
     * @param \DateTimeInterface|string $date
     * @param \DateTimeZone|string      $timezone
     */
    public static function createDateTimeImmutableOverwriteTimezone($date, $timezone = 'UTC'): \DateTimeImmutable
    {
        if (!$date instanceof \DateTimeInterface) {
            $date = new \DateTimeImmutable($date);
        }

        $formatted = $date->format('Y-m-d H:i:s');
        $timezone = self::createTimezone($timezone);

        return new \DateTimeImmutable($formatted, $timezone);
    }

    /**
     * @param \DateTimeInterface|string|null $date
     * @param \DateTimeZone|string           $timezone
     */
    public static function createNullableDateTimeImmutableOverwriteTimezone($date, $timezone = 'UTC'): ?\DateTimeImmutable
    {
        if (null === $date) {
            return null;
        }

        return self::createDateTimeImmutableOverwriteTimezone($date, $timezone);
    }

    /**
     * @param \DateTimeZone|string $timezone
     */
    public static function createTimezone($timezone): \DateTimeZone
    {
        if ($timezone instanceof \DateTimeZone) {
            return $timezone;
        }

        return new \DateTimeZone($timezone);
    }

    /**
     * @param \DateTimeZone|string|null $timezone
     */
    public static function createNullableTimezone($timezone): ?\DateTimeZone
    {
        if (null === $timezone) {
            return null;
        }

        return self::createTimezone($timezone);
    }
}
