<?php
/**
 * Unit tests for the output-escaping helper e().
 *
 * e() is our single defence against reflected/stored XSS, so it is worth
 * proving it neutralises the dangerous characters.
 */

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/bootstrap.php';

final class EscapingTest extends TestCase
{
    public function testEscapesAngleBrackets(): void
    {
        $this->assertSame('&lt;script&gt;alert(1)&lt;/script&gt;', e('<script>alert(1)</script>'));
    }

    public function testEscapesAmpersand(): void
    {
        $this->assertSame('Tom &amp; Jerry', e('Tom & Jerry'));
    }

    public function testEscapesDoubleQuote(): void
    {
        $this->assertSame('&quot;', e('"'));
    }

    public function testEscapesSingleQuote(): void
    {
        // ENT_QUOTES is set, so single quotes are escaped too.
        $this->assertSame('&#039;', e("'"));
    }

    public function testNullBecomesEmptyString(): void
    {
        $this->assertSame('', e(null));
    }

    public function testPlainTextIsUnchanged(): void
    {
        $this->assertSame('Highland landscape', e('Highland landscape'));
    }

    public function testIsIdempotentForOutputUse(): void
    {
        // Escaping already-escaped text is safe (we only ever escape once,
        // but this documents that the ampersand is encoded, not left raw).
        $once  = e('<b>');
        $twice = e($once);
        $this->assertNotSame($once, $twice);
        $this->assertStringStartsWith('&amp;', $twice);
    }
}
