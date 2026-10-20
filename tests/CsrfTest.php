<?php
/**
 * Unit tests for the CSRF helpers.
 *
 * These tests manipulate $_SESSION directly so each one is self-contained,
 * then reset the token so tests cannot leak state into one another.
 */

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/bootstrap.php';

final class CsrfTest extends TestCase
{
    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Start every test from a clean slate.
        unset($_SESSION['csrf_token']);
    }

    protected function tearDown(): void
    {
        unset($_SESSION['csrf_token']);
    }

    public function testTokenIs64HexCharacters(): void
    {
        $token = csrf_token();
        $this->assertSame(64, strlen($token));
        $this->assertMatchesRegularExpression('/^[0-9a-f]{64}$/', $token);
    }

    public function testTokenIsStableWithinSession(): void
    {
        $first  = csrf_token();
        $second = csrf_token();
        $this->assertSame($first, $second, 'Token should be reused, not regenerated each call.');
    }

    public function testVerifyAcceptsTheRealToken(): void
    {
        $token = csrf_token();
        $this->assertTrue(csrf_verify($token));
    }

    public function testVerifyRejectsAWrongToken(): void
    {
        csrf_token(); // establish a session token
        $this->assertFalse(csrf_verify(str_repeat('a', 64)));
    }

    public function testVerifyRejectsNullToken(): void
    {
        csrf_token();
        $this->assertFalse(csrf_verify(null));
    }

    public function testVerifyFailsWhenNoSessionTokenExists(): void
    {
        // No csrf_token() call, so $_SESSION['csrf_token'] is unset.
        $this->assertFalse(csrf_verify('anything'));
    }

    public function testFieldRendersAHiddenInput(): void
    {
        $field = csrf_field();
        $this->assertStringContainsString('type="hidden"', $field);
        $this->assertStringContainsString('name="csrf_token"', $field);
        $this->assertStringContainsString(csrf_token(), $field);
    }
}
