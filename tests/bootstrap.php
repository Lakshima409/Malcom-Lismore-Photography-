<?php
/**
 * PHPUnit bootstrap.
 *
 * Loads the project's helper functions without triggering a database
 * connection (db() is lazy), so the unit tests are hermetic and fast.
 * Functions that genuinely need the database live in IntegrationTest and
 * skip gracefully when MySQL is unreachable.
 *
 * Malcolm Lismore Photography
 */

declare(strict_types=1);

// Make sure the session save path is writable in the CLI test runner.
if (session_status() === PHP_SESSION_NONE) {
    @session_save_path(sys_get_temp_dir());
}

// Core helpers (functions.php pulls in db.php via require_once).
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/auth.php';

/**
 * Helper for integration tests: is the database reachable?
 * Defined here so every test can share the same check.
 */
function tests_db_available(): bool
{
    static $available = null;
    if ($available !== null) {
        return $available;
    }
    try {
        db()->query('SELECT 1');
        $available = true;
    } catch (Throwable $e) {
        $available = false;
    }
    return $available;
}
