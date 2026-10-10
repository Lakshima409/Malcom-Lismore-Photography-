<?php
/**
 * Custom 404 "not found" page.
 *
 * Returns a real HTTP 404 status and offers links to help visitors recover.
 *
 * Malcolm Lismore Photography
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/csrf.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure the server reports a genuine 404 status.
http_response_code(404);

$pageTitle       = 'Page Not Found (404) | Malcolm Lismore Photography';
$pageDescription = 'Sorry, the page you were looking for could not be found.';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <h1>404</h1>
    <p>Sorry, the page you were looking for doesn&rsquo;t exist or may have moved.</p>
  </div>
</section>

<section class="section section-teal">
  <div class="container text-center">
    <p style="color:var(--sand);max-width:52ch;margin-inline:auto;">
      You can head back to the home page, or browse the gallery to explore
      Malcolm&rsquo;s photography from across the Scottish Highlands.
    </p>
    <div class="cta-actions">
      <a class="btn btn-gold" href="index.php">Back to Home</a>
      <a class="btn btn-outline" href="gallery.php">Explore the Gallery</a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
