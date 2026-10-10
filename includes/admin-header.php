<?php
/**
 * Admin area header + authentication guard.
 *
 * Every admin page (except login.php) requires this file first. It starts
 * the session, enforces authentication, renders the shared admin chrome and
 * exposes the $adminCurrent variable for highlighting the active nav item.
 *
 * Malcolm Lismore Photography
 */

declare(strict_types=1);

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/csrf.php';
require_once __DIR__ . '/auth.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enforce login for every page that includes this file.
require_admin();

$pageTitle      = $pageTitle ?? 'Admin';
$adminCurrent   = basename((string) ($_SERVER['SCRIPT_NAME'] ?? ''));
$adminUsername  = $_SESSION['admin_username'] ?? 'Admin';

// Flash messages for the admin area.
$adminFlashSuccess = get_flash('success');
$adminFlashError   = get_flash('error');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle) ?> | Malcolm Lismore Admin</title>
  <link rel="icon" type="image/svg+xml" href="../assets/images/favicon.svg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="admin-body">
  <header class="site-header admin-header">
    <div class="container header-inner">
      <a class="brand" href="index.php">
        <span class="brand-name">Malcolm Lismore</span>
        <span class="brand-sub">Admin Panel</span>
      </a>
      <div class="header-spacer"></div>
      <nav class="admin-nav" aria-label="Admin navigation">
        <a href="index.php"    class="<?= $adminCurrent === 'index.php' ? 'active' : '' ?>">Dashboard</a>
        <a href="enquiries.php" class="<?= $adminCurrent === 'enquiries.php' || $adminCurrent === 'enquiry-view.php' ? 'active' : '' ?>">Enquiries</a>
        <a href="photos.php"    class="<?= in_array($adminCurrent, ['photos.php', 'photo-add.php', 'photo-edit.php'], true) ? 'active' : '' ?>">Photos</a>
        <a href="../index.php"  target="_blank">View Site &nearr;</a>
        <form action="logout.php" method="post" style="display:inline;">
          <?= csrf_field() ?>
          <button type="submit" class="link-btn" style="color:var(--sand);">Logout (<?= e((string) $adminUsername) ?>)</button>
        </form>
      </nav>
    </div>
  </header>

  <main id="main">
    <div class="container admin-wrap">
      <?php if ($adminFlashSuccess !== null): ?>
        <div class="alert alert-success" role="status"><?= e($adminFlashSuccess) ?></div>
      <?php endif; ?>
      <?php if ($adminFlashError !== null): ?>
        <div class="alert alert-error" role="alert"><?= e($adminFlashError) ?></div>
      <?php endif; ?>
