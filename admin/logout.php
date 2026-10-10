<?php
/**
 * Secure logout.
 *
 * Accepts only a POST request with a valid CSRF token, logs the
 * administrator out, and returns them to the login page.
 *
 * Malcolm Lismore Photography
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/auth.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Only allow logout via POST with a valid token (prevents CSRF logouts).
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify($_POST['csrf_token'] ?? null)) {
    admin_logout();
    session_start(); // fresh session for the flash message
    set_flash('success', 'You have been logged out.');
    redirect('login.php');
}

// Fallback: direct access just returns to the login page.
redirect('login.php');
