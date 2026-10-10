<?php
/**
 * Shared site header and navigation (public pages).
 *
 * Expects $pageTitle and (optionally) $pageDescription to be set by the
 * including page BEFORE this file is required.
 *
 * Malcolm Lismore Photography
 */

declare(strict_types=1);

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/csrf.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Work out the base URL so links work whether we are in the site root or
// in a sub-folder such as /admin/.
$scriptDir  = rtrim(str_replace('\\', '/', dirname((string) ($_SERVER['SCRIPT_NAME'] ?? '/'))), '/');
$baseUrl    = rtrim(str_replace('\\', '/', dirname(__DIR__)), '/'); // project root as filesystem path
$inAdmin    = str_contains($scriptDir, '/admin') || basename($scriptDir) === 'admin';
// Prefix used for links from admin pages back to the public root.
$prefix     = $inAdmin ? '../' : '';

$pageTitle       = $pageTitle       ?? 'Malcolm Lismore Photography';
$pageDescription = $pageDescription ?? 'Malcolm Lismore Photography - capturing the untamed beauty of Scotland\'s North West coast.';
$navCurrent      = basename((string) ($_SERVER['SCRIPT_NAME'] ?? ''));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle) ?></title>
  <meta name="description" content="<?= e($pageDescription) ?>">
  <link rel="icon" type="image/svg+xml" href="<?= e($prefix) ?>assets/images/favicon.svg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= e($prefix) ?>assets/css/style.css">
</head>
<body class="<?= $inAdmin ? 'admin-body' : '' ?>">
  <a class="skip-link" href="#main">Skip to content</a>

  <header class="site-header <?= $inAdmin ? 'admin-header' : '' ?>">
    <div class="container header-inner">
      <button class="nav-toggle" data-nav-toggle aria-label="Open menu" aria-expanded="false" aria-controls="nav-overlay">
        <span></span><span></span><span></span>
      </button>

      <a class="brand" href="<?= e($prefix) ?>index.php">
        <span class="brand-name">Malcolm Lismore</span>
        <span class="brand-sub">Photography</span>
      </a>

      <div class="header-spacer"></div>

      <nav class="top-links" aria-label="Quick links">
        <a class="top-link" href="<?= e($prefix) ?>admin/login.php">Login</a>
        <a class="top-link" href="<?= e($prefix) ?>gallery.php" aria-label="Search the gallery">&#128269;</a>
      </nav>
    </div>
  </header>

  <?php if (!$inAdmin): ?>
  <!-- Fullscreen overlay menu -->
  <div class="nav-overlay" id="nav-overlay" data-nav-overlay aria-hidden="true">
    <button class="nav-close" data-nav-close aria-label="Close menu">&times;</button>
    <nav aria-label="Main navigation">
      <ul>
        <li><a href="<?= e($prefix) ?>index.php"    class="<?= $navCurrent === 'index.php' ? 'active' : '' ?>">Home</a></li>
        <li><a href="<?= e($prefix) ?>about.php"    class="<?= $navCurrent === 'about.php' ? 'active' : '' ?>">About</a></li>
        <li><a href="<?= e($prefix) ?>gallery.php"  class="<?= $navCurrent === 'gallery.php' ? 'active' : '' ?>">Gallery</a></li>
        <li><a href="<?= e($prefix) ?>blog.php"     class="<?= $navCurrent === 'blog.php' ? 'active' : '' ?>">Blog</a></li>
        <li><a href="<?= e($prefix) ?>prices.php"   class="<?= $navCurrent === 'prices.php' ? 'active' : '' ?>">Pricing</a></li>
        <li><a href="<?= e($prefix) ?>enquiry.php"  class="<?= $navCurrent === 'enquiry.php' ? 'active' : '' ?>">Enquiry</a></li>
        <li><a href="<?= e($prefix) ?>faq.php"      class="<?= $navCurrent === 'faq.php' ? 'active' : '' ?>">Contact</a></li>
      </ul>
    </nav>
  </div>
  <?php endif; ?>

  <main id="main">
