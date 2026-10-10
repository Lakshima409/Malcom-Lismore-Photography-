<?php
/**
 * Blog / journal page.
 *
 * A simple editorial listing of field notes and photography articles.
 * (Content is static demonstration copy.)
 *
 * Malcolm Lismore Photography
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/csrf.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle       = 'Journal | Malcolm Lismore Photography';
$pageDescription = 'Field notes, photography tips and stories from behind the lens in the Scottish Highlands.';

$posts = [
    [
        'image'   => 'assets/images/blog-1.jpg',
        'alt'     => 'Misty Highland loch at sunrise',
        'date'    => '12 January 2025',
        'cat'     => 'Field Notes',
        'title'   => 'Chasing First Light in the Highlands',
        'excerpt' => 'How a 4am start on the shores of Loch Maree delivered the calmest, most atmospheric frames of the year.',
    ],
    [
        'image'   => 'assets/images/blog-2.jpg',
        'alt'     => 'Close-up of a coastal seabird',
        'date'    => '28 November 2024',
        'cat'     => 'Wildlife',
        'title'   => 'Photographing Puffins Respectfully',
        'excerpt' => 'A few principles for getting close to cliff-nesting seabirds without ever disturbing them or their burrows.',
    ],
    [
        'image'   => 'assets/images/blog-3.jpg',
        'alt'     => 'Wedding couple walking through golden light',
        'date'    => '3 October 2024',
        'cat'     => 'Weddings',
        'title'   => 'Planning a Highland Wedding Shoot',
        'excerpt' => 'Timing, weather and golden hour â€” what to consider when choosing a Scottish venue for your photographs.',
    ],
];

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><a href="index.php">Home</a> &rsaquo; Journal</div>
    <h1>The Journal</h1>
    <p>Stories, field notes and tips from behind the lens in the Scottish Highlands.</p>
  </div>
</section>

<section class="section section-ivory">
  <div class="container">
    <div class="blog-grid">
      <?php foreach ($posts as $post): ?>
        <article class="blog-card">
          <div class="blog-thumb">
            <img src="<?= e($post['image']) ?>" alt="<?= e($post['alt']) ?>" width="800" height="500" loading="lazy">
          </div>
          <div class="blog-card-body">
            <div class="blog-meta"><span><?= e($post['date']) ?></span><span><?= e($post['cat']) ?></span></div>
            <h3><?= e($post['title']) ?></h3>
            <p><?= e($post['excerpt']) ?></p>
            <span class="read">Read article &rarr;</span>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
