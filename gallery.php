<?php
/**
 * Gallery page.
 *
 * Displays photographs in a responsive grid, with category filters and
 * pagination. Categories are selected via ?category=<slug>.
 *
 * Malcolm Lismore Photography
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/csrf.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle       = 'Gallery | Malcolm Lismore Photography';
$pageDescription = 'Browse Scottish landscape, wildlife, coastal bird, wedding and portrait photography by category.';

// Which category are we viewing? Empty string = all photos.
$activeSlug = isset($_GET['category']) ? trim((string) $_GET['category']) : '';

// Validate the slug against real categories (prevents arbitrary values).
$categories = get_categories();
$validSlugs = array_column($categories, 'slug');
if ($activeSlug !== '' && !in_array($activeSlug, $validSlugs, true)) {
    // Unknown category -> treat as "not found" style message.
    http_response_code(404);
}

// Human-readable name of the active category (if any).
$activeCategory = null;
if ($activeSlug !== '') {
    foreach ($categories as $cat) {
        if ($cat['slug'] === $activeSlug) {
            $activeCategory = $cat;
            break;
        }
    }
}

// Pagination.
$perPage = 9;
$page    = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$offset  = ($page - 1) * $perPage;

$result  = get_photos($activeSlug !== '' ? $activeSlug : null, $perPage, $offset);
$photos  = $result['rows'];
$total   = $result['total'];
$pages   = max(1, (int) ceil($total / $perPage));

require __DIR__ . '/includes/header.php';
?>

<!-- ================= PAGE HERO ================= -->
<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><a href="index.php">Home</a> &rsaquo; Gallery</div>
    <h1><?= $activeCategory ? e($activeCategory['name']) : 'The Gallery' ?></h1>
    <p>
      <?= $activeCategory && $activeCategory['description']
            ? e($activeCategory['description'])
            : 'A curated collection of photography from across the Scottish Highlands.' ?>
    </p>
  </div>
</section>

<!-- ================= GALLERY ================= -->
<section class="section section-sand">
  <div class="container">

    <!-- Category filters -->
    <nav class="filters" aria-label="Filter photographs by category">
      <a class="filter-btn <?= $activeSlug === '' ? 'active' : '' ?>" href="gallery.php">All</a>
      <?php foreach ($categories as $cat): ?>
        <a class="filter-btn <?= $activeSlug === $cat['slug'] ? 'active' : '' ?>"
           href="gallery.php?category=<?= e($cat['slug']) ?>">
          <?= e($cat['name']) ?>
        </a>
      <?php endforeach; ?>
    </nav>

    <?php if ($activeSlug !== '' && $activeCategory === null): ?>
      <div class="empty-state">
        <h3>Category not found</h3>
        <p>Sorry, that category doesn&rsquo;t exist. Please choose one from the filters above.</p>
        <a class="btn btn-gold mt-2" href="gallery.php">View all photographs</a>
      </div>
    <?php elseif (empty($photos)): ?>
      <div class="empty-state" data-filter-empty>
        <h3>No photographs yet</h3>
        <p>There are no photographs in this category just yet. Please check back soon.</p>
        <a class="btn btn-gold mt-2" href="gallery.php">View all photographs</a>
      </div>
    <?php else: ?>
      <div class="gallery-grid">
        <?php foreach ($photos as $photo): ?>
          <a class="photo-card" href="photo.php?id=<?= (int) $photo['photo_id'] ?>">
            <div class="photo-thumb">
              <img src="<?= e($photo['image_path']) ?>"
                   alt="<?= e($photo['alt_text']) ?>"
                   loading="lazy" width="1200" height="800">
              <span class="tag"><?= e($photo['category_name']) ?></span>
            </div>
            <div class="photo-body">
              <h3><?= e($photo['title']) ?></h3>
              <p><?= e(mb_strimwidth((string) $photo['description'], 0, 90, '…')) ?></p>
              <span class="photo-link">View photograph &rarr;</span>
            </div>
          </a>
        <?php endforeach; ?>
      </div>

      <!-- Pagination -->
      <?php if ($pages > 1): ?>
        <?php
        $qs = $activeSlug !== '' ? 'category=' . rawurlencode($activeSlug) . '&' : '';
        ?>
        <nav class="pagination" aria-label="Gallery pagination">
          <?php if ($page > 1): ?>
            <a href="?<?= $qs ?>page=<?= $page - 1 ?>">&laquo; Prev</a>
          <?php endif; ?>

          <?php for ($i = 1; $i <= $pages; $i++): ?>
            <?php if ($i === $page): ?>
              <span class="current" aria-current="page"><?= $i ?></span>
            <?php else: ?>
              <a href="?<?= $qs ?>page=<?= $i ?>"><?= $i ?></a>
            <?php endif; ?>
          <?php endfor; ?>

          <?php if ($page < $pages): ?>
            <a href="?<?= $qs ?>page=<?= $page + 1 ?>">Next &raquo;</a>
          <?php endif; ?>
        </nav>
      <?php endif; ?>
    <?php endif; ?>

  </div>
</section>

<!-- ================= CTA ================= -->
<section class="section section-teal">
  <div class="container">
    <div class="cta-banner">
      <h2>Inspired by what you see?</h2>
      <p>Malcolm takes on commissions and sells fine-art prints. Get in touch to talk about your ideas.</p>
      <div class="cta-actions">
        <a class="btn btn-gold" href="enquiry.php">Make an Enquiry</a>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
