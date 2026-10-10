<?php
/**
 * Prices / packages page.
 *
 * Services are read from the database so prices are never hard-coded into
 * the page.
 *
 * Malcolm Lismore Photography
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/csrf.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle       = 'Pricing &amp; Packages | Malcolm Lismore Photography';
$pageDescription = 'Wedding, portrait, family and event photography packages. Clear pricing and a note that final quotes depend on your requirements.';

$services = get_services();

// Split the multi-line "includes" text into a list of bullet points.
function service_includes(?string $text): array
{
    if ($text === null || trim($text) === '') {
        return [];
    }
    $lines = preg_split('/\r\n|\r|\n/', $text);
    return array_values(array_filter(array_map('trim', $lines), static fn($l) => $l !== ''));
}

require __DIR__ . '/includes/header.php';
?>

<!-- ================= PAGE HERO ================= -->
<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><a href="index.php">Home</a> &rsaquo; Pricing</div>
    <h1>Pricing &amp; Packages</h1>
    <p>Transparent starting prices for every occasion. Final quotes are tailored to your specific requirements.</p>
  </div>
</section>

<!-- ================= PACKAGES ================= -->
<section class="section section-teal">
  <div class="container">

    <?php if (empty($services)): ?>
      <div class="empty-state">
        <h3>Packages coming soon</h3>
        <p>Our pricing information is being updated. Please <a href="enquiry.php">make an enquiry</a> and we&rsquo;ll be in touch.</p>
      </div>
    <?php else: ?>
      <div class="pricing-grid">
        <?php foreach ($services as $service): ?>
          <article class="price-card">
            <?php if (!empty($service['image_path'])): ?>
              <img src="<?= e($service['image_path']) ?>"
                   alt="<?= e((string) ($service['alt_text'] ?? $service['name'])) ?>"
                   loading="lazy" width="800" height="533">
            <?php endif; ?>
            <div class="price-card-body">
              <h3><?= e($service['name']) ?></h3>
              <p class="price">
                <?= e($service['price_note'] ?? '') ?>
                <?php if ($service['starting_price'] !== null): ?>
                  <span style="font-weight:400;color:var(--sand);font-size:0.95rem;">
                    (<?= e((string) format_price((string) $service['starting_price'])) ?>)
                  </span>
                <?php endif; ?>
              </p>
              <p class="desc"><?= e((string) $service['description']) ?></p>

              <?php $items = service_includes($service['includes']); ?>
              <?php if (!empty($items)): ?>
                <ul>
                  <?php foreach ($items as $item): ?>
                    <li><?= e($item) ?></li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>

              <a class="btn btn-gold"
                 href="enquiry.php?service=<?= urlencode($service['name']) ?>">Enquire Now</a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

      <p class="pricing-note">
        <strong>Please note:</strong> the prices shown are starting points. Every
        wedding, event and commission is unique, so final pricing depends on
        factors such as the length of coverage, travel, the number of
        photographers and how you would like your finished images delivered.
        <a href="enquiry.php" style="color:var(--gold);text-decoration:underline;">Make an enquiry</a>
        for a personalised quote.
      </p>
    <?php endif; ?>

  </div>
</section>

<!-- ================= CTA ================= -->
<section class="section section-sand">
  <div class="container">
    <div class="cta-banner">
      <h2>Not sure which package is right?</h2>
      <p>Tell Malcolm about your plans and he&rsquo;ll recommend the best option for your day and budget.</p>
      <div class="cta-actions">
        <a class="btn btn-gold" href="enquiry.php">Make an Enquiry</a>
        <a class="btn btn-outline" href="gallery.php">View the Gallery</a>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
