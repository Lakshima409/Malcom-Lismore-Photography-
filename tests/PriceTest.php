<?php
/**
 * Unit tests for format_price().
 *
 * Prices are stored as decimal strings in MySQL; this helper turns them into
 * a tidy display string and must handle the whole/integer/decimal cases plus
 * null/empty gracefully.
 */

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/bootstrap.php';

final class PriceTest extends TestCase
{
    public function testNullReturnsNull(): void
    {
        $this->assertNull(format_price(null));
    }

    public function testEmptyStringReturnsNull(): void
    {
        $this->assertNull(format_price(''));
    }

    public function testWholeNumberHasNoDecimals(): void
    {
        $this->assertSame('£1,000', format_price('1000.00'));
        $this->assertSame('£200', format_price('200'));
    }

    public function testThousandsAreCommaSeparated(): void
    {
        $this->assertSame('£2,500', format_price('2500.00'));
    }

    public function testDecimalPricesKeepTwoPlaces(): void
    {
        $this->assertSame('£1,000.50', format_price('1000.50'));
    }

    public function testZeroFormatsAsPoundZero(): void
    {
        $this->assertSame('£0', format_price('0'));
    }

    public function testSmallDecimalIsPreserved(): void
    {
        $this->assertSame('£19.99', format_price('19.99'));
    }
}
