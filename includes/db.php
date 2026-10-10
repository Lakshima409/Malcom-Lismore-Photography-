<?php
/**
 * Database connection (PDO).
 *
 * Malcolm Lismore Photography
 *
 * This project was developed on XAMPP. The bundled MariaDB server is
 * reachable on 127.0.0.1:3307 with the default "root" user and no
 * password. Credentials are kept in this single file so they are easy
 * to change and are not scattered through the codebase.
 *
 * NOTE FOR PRODUCTION:
 * Move these credentials into environment variables or a configuration
 * file that lives OUTSIDE the web root so they are never publicly
 * accessible, and create a dedicated least-privilege database user.
 */

declare(strict_types=1);

// ---- Configuration ----------------------------------------------------
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3307');
define('DB_NAME', 'malcolm_lismore');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

/**
 * Create and return a shared PDO connection.
 * The connection is created once and reused for the whole request.
 */
function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        DB_HOST,
        DB_PORT,
        DB_NAME,
        DB_CHARSET
    );

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        // In production, log the real error and show a friendly message.
        http_response_code(500);
        exit('Sorry, we are experiencing a database connection problem. Please try again later.');
    }

    return $pdo;
}
