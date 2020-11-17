<?php

declare(strict_types=1);

namespace Gansel\DateTime\Test\Bridge\Doctrine\DBAL\Types;

use Gansel\DateTime\Bridge\Doctrine\DBAL\Types\DateTimeImmutableUtcType;
use PHPUnit\Framework\TestCase;

final class DateTimeImmutableUtcTypeTest extends TestCase
{
    /**
     * @test
     */
    public function constants(): void
    {
        self::assertSame(
            'gansel_datetime_immutable_utc',
            DateTimeImmutableUtcType::NAME
        );
    }
}
