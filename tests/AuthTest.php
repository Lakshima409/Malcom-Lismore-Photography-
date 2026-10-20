<?php
/**
 * Unit tests for the authentication helpers.
 *
 * Covers is_admin_logged_in() (session state) and proves the password
 * hashing approach with password_verify(). The demo-credentials check reads
 * the real hash out of the database and skips gracefully if MySQL is
 * unreachable.
 */

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/bootstrap.php';

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
        $hash = password_hash('Admin@1234', PASSWORD_DEFAULT);
        $this->assertTrue(password_verify('Admin@1234', $hash));
    }

    public function testPasswordHashRejectsWrongPassword(): void
    {
        $hash = password_hash('Admin@1234', PASSWORD_DEFAULT);
        $this->assertFalse(password_verify('Admin@12345', $hash));
    }

    public function testHashIsNotPlaintext(): void
    {
        // The stored value must be a bcrypt hash, never the raw password.
        $hash = password_hash('Admin@1234', PASSWORD_DEFAULT);
        $this->assertNotSame('Admin@1234', $hash);
        $this->assertMatchesRegularExpression('/^\$2[aby]\$/', $hash);
    }

    public function testDocumentedDemoCredentialsMatchStoredHash(): void
    {
        if (!tests_db_available()) {
            $this->markTestSkipped('Database not reachable - skipping live credential check.');
        }

        $stmt = db()->prepare('SELECT password_hash FROM admins WHERE username = ? LIMIT 1');
        $stmt->execute(['admin']);
        $hash = $stmt->fetchColumn();

        $this->assertNotFalse($hash, 'Demo admin user "admin" should exist in the seed data.');
        $this->assertTrue(
            password_verify('Admin@1234', (string) $hash),
            'The documented demo password Admin@1234 must verify against the stored hash.'
        );
    }
}
