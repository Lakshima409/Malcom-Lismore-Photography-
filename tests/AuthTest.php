<?php
/**
 * Unit tests for the authentication helpers.
 *
 * Covers is_admin_logged_in() (session state) and proves the password
 * hashing approach with password_verify(). Uses a throwaway test password
 * defined exclusively for testing — never a real or default credential.
 */

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/bootstrap.php';

// Throwaway password used exclusively by this test file. Not a real account
// credential and never used in production or sample data.
define('TEST_PASSWORD', 'Test.Password-Secure-2026!');

final class AuthTest extends TestCase
{
    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['admin_id'], $_SESSION['admin_username']);
    }

    protected function tearDown(): void
    {
        unset($_SESSION['admin_id'], $_SESSION['admin_username']);
    }

    public function testNotLoggedInByDefault(): void
    {
        $this->assertFalse(is_admin_logged_in());
    }

    public function testLoggedInWhenAdminIdIsSet(): void
    {
        $_SESSION['admin_id'] = 1;
        $this->assertTrue(is_admin_logged_in());
    }

    public function testEmptyAdminIdCountsAsLoggedOut(): void
    {
        $_SESSION['admin_id'] = 0;
        $this->assertFalse(is_admin_logged_in());
    }

    public function testPasswordHashVerifiesCorrectPassword(): void
    {
        $hash = password_hash(TEST_PASSWORD, PASSWORD_DEFAULT);
        $this->assertTrue(password_verify(TEST_PASSWORD, $hash));
    }

    public function testPasswordHashRejectsWrongPassword(): void
    {
        $hash = password_hash(TEST_PASSWORD, PASSWORD_DEFAULT);
        $this->assertFalse(password_verify(TEST_PASSWORD . 'wrong', $hash));
    }

    public function testHashIsNotPlaintext(): void
    {
        // The stored value must be a bcrypt hash, never the raw password.
        $hash = password_hash(TEST_PASSWORD, PASSWORD_DEFAULT);
        $this->assertNotSame(TEST_PASSWORD, $hash);
        $this->assertMatchesRegularExpression('/^\$2[aby]\$/', $hash);
    }

    public function testHashIsBcryptWithDefaultCost(): void
    {
        $hash = password_hash(TEST_PASSWORD, PASSWORD_DEFAULT);
        // PASSWORD_DEFAULT uses bcrypt ($2y$) on PHP 8.x.
        $this->assertMatchesRegularExpression('/^\$2y\$\d{2}\$/', $hash);
    }
}
