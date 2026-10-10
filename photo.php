<?php
/**
 * Photograph detail page.
 *
 * Shows a single photograph with its description, metadata, category and
 * previous/next navigation within the same category.
 *
 * Malcolm Lismore Photography
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/csrf.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$photoId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$photo   = $photoId > 0 ? get_photo($photoId) : null;

if ($photo === null) {
    http_response_code(404);
}

$pageTitle       = $photo ? e($photo['title']) . ' | Malcolm Lismore Photography' : 'Photograph not found';
$pageDescription = $photo ? mb_strimwidth((string) $photo['description'], 0, 155, '…')
                         : 'The requested photograph could not be found.';

// Previous / next within the same category.
$neighbours = $photo ? get_photo_neighbours((int) $photo['photo_id'], (string) $photo['category_slug']) : ['prev' => null, 'next' => null];

require __DIR__ . '/includes/header.php';
?>

<?php if ($photo === null): ?>
  <!-- ================= NOT FOUND ================= -->
  <section class="page-hero">
    <div class="container">
      <h1>Photograph not found</h1>
      <p>Sorry, we couldn&rsquo;t find the photograph you were looking for.</p>
    </div>
  </section>
  <section class="section section-teal">
    <div class="container text-center">
      <a class="btn btn-gold" href="gallery.php">Back to the Gallery</a>
    </div>
  </section>
<?php else: ?>
  <!-- ================= PAGE HERO ================= -->
  <section class="page-hero">
    <div class="container">
      <div class="breadcrumb">
        <a href="index.php">Home</a> &rsaquo;
        <a href="gallery.php">Gallery</a> &rsaquo;
        <a href="gallery.php?category=<?= e($photo['category_slug']) ?>"><?= e($photo['category_name']) ?></a>
      </div>
      <h1><?= e($photo['title']) ?></h1>
    </div>
  </section>

  <!-- ================= DETAIL ================= -->
  <section class="section section-teal">
    <div class="container photo-detail">
      <div class="photo-detail-media">
        <img src="<?= e($photo['image_path']) ?>"
             alt="<?= e($photo['alt_text']) ?>"
             width="1200" height="800">
      </div>

      <div>
        <h2 class="section-title"><?= e($photo['title']) ?></h2>
        <p><?= nl2br(e((string) $photo['description'])) ?></p>

        <ul class="photo-meta-list">
          <li><strong>Category</strong> <a href="gallery.php?category=<?= e($photo['category_slug']) ?>"><?= e($photo['category_name']) ?></a></li>
          <?php if (!empty($photo['location'])): ?>
            <li><strong>Location</strong> <?= e($photo['location']) ?></li>
          <?php endif; ?>
          <li><strong>Added</strong> <?= e(date('j F Y', strtotime((string) $photo['created_at']))) ?></li>
        </ul>

        <a class="btn btn-gold" href="enquiry.php">Enquire about a commission</a>
        <a class="btn btn-outline mt-2" href="gallery.php?category=<?= e($photo['category_slug']) ?>">Back to category</a>

        <!-- Previous / next -->
        <div class="photo-nav">
          <?php if ($neighbours['prev'] !== null): ?>
            <a class="btn btn-teal btn-sm" href="photo.php?id=<?= (int) $neighbours['prev'] ?>">&laquo; Previous</a>
          <?php else: ?>
            <span></span>
          <?php endif; ?>

          <?php if ($neighbours['next'] !== null): ?>
            <a class="btn btn-teal btn-sm" href="photo.php?id=<?= (int) $neighbours['next'] ?>">Next &raquo;</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- ================= CTA ================= -->
  <section class="section section-sand">
    <div class="container">
      <div class="cta-banner">
        <h2>Love this photograph?</h2>
        <p>Fine-art prints and bespoke commissions are available on request.</p>
        <div class="cta-actions">
          <a class="btn btn-gold" href="enquiry.php">Make an Enquiry</a>
          <a class="btn btn-outline" href="gallery.php">Explore More Work</a>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
