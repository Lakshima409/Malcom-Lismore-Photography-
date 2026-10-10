<?php
/**
 * Administrator authentication helpers.
 *
 * Provides session-based login checks, a login attempt handler and a secure
 * logout. Every admin page (except login.php) calls require_admin() through
 * includes/admin-header.php before doing anything else.
 *
 * Malcolm Lismore Photography
 */

declare(strict_types=1);

require_once __DIR__ . '/functions.php';

/**
 * Is an administrator currently logged in?
 */
function is_admin_logged_in(): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return !empty($_SESSION['admin_id']);
}

/**
 * Protect an admin page. Redirects to the login page if not authenticated.
 */
function require_admin(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['admin_id'])) {
        $_SESSION['intended'] = $_SERVER['REQUEST_URI'] ?? 'index.php';
        set_flash('error', 'Please log in to access the admin area.');
        redirect('login.php');
    }
}

/**
 * Attempt to log an administrator in.
 *
 * Verifies the submitted credentials against the stored password hash and,
 * on success, regenerates the session id to prevent session fixation.
 */
function attempt_login(string $username, string $password): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $stmt = db()->prepare('SELECT admin_id, username, password_hash FROM admins WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && is_string($admin['password_hash']) && password_verify($password, $admin['password_hash'])) {
        // Regenerate the session id on login to defeat session fixation.
        session_regenerate_id(true);
        $_SESSION['admin_id']       = (int) $admin['admin_id'];
        $_SESSION['admin_username'] = (string) $admin['username'];
        return true;
    }

    return false;
}

/**
 * Log the current administrator out and destroy the session.
 */
function admin_logout(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION = [];

    // Delete the session cookie as well.
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires'  => time() - 42000,
            'path'     => $params['path'],
            'domain'   => $params['domain'],
            'secure'   => $params['secure'],
            'httponly' => $params['httponly'],
            'samesite' => $params['samesite'] ?? 'Lax',
        ]);
    }

    session_destroy();
}
