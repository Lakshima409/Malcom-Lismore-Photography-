<?php
/**
 * Administrator login.
 *
 * Standalone page (does not use the auth guard). Verifies credentials on
 * POST and, on success, redirects to the dashboard.
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

// If already logged in, go straight to the dashboard.
if (is_admin_logged_in()) {
    redirect('index.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token for the login attempt.
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        http_response_code(419);
        $errors[] = 'Your session expired. Please try again.';
    } else {
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if ($username === '' || $password === '') {
            $errors[] = 'Please enter both a username and a password.';
        } elseif (attempt_login($username, $password)) {
            // Redirect to the originally requested page, if any.
            $intended = $_SESSION['intended'] ?? 'index.php';
            unset($_SESSION['intended']);
            redirect($intended);
        } else {
            $errors[] = 'Invalid username or password.';
            // Small delay to slow down brute-force attempts.
            usleep(500000);
        }
    }
}

$pageTitle = 'Admin Login';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle) ?> | Malcolm Lismore Photography</title>
  <link rel="icon" type="image/svg+xml" href="../assets/images/favicon.svg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <div class="login-wrap">
    <div class="login-card">
      <h1>Admin Login</h1>

      <?php foreach ($errors as $err): ?>
        <div class="alert alert-error" role="alert"><?= e($err) ?></div>
      <?php endforeach; ?>

      <form action="login.php" method="post" novalidate>
        <?= csrf_field() ?>
        <div class="form-field" style="margin-bottom:1rem;">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" required autofocus
                 value="<?= e((string) ($_POST['username'] ?? '')) ?>">
        </div>
        <div class="form-field" style="margin-bottom:1.4rem;">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required autocomplete="current-password">
        </div>
        <button type="submit" class="btn btn-gold" style="width:100%;">Log In</button>
      </form>

      <p style="text-align:center;margin-top:1.2rem;font-size:0.85rem;">
        <a href="../index.php" style="color:var(--teal-600);">&larr; Back to website</a>
      </p>
    </div>
  </div>
</body>
</html>
