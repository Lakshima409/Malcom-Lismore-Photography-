<?php
/**
 * Home page.
 *
 * Introduces Malcolm's services and encourages visitors to explore the
 * gallery or make an enquiry.
 *
 * Malcolm Lismore Photography
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/csrf.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle       = 'Malcolm Lismore Photography | Scottish Landscape & Wedding Photographer';
$pageDescription = 'Capturing the untamed beauty of Scotland\'s North West coast. Explore Scottish landscapes, wildlife, coastal birds, weddings and portraits.';

// Featured photographs for the home gallery teaser (one per key category).
$featured = get_photos(null, 4, 0)['rows'];

// A few category descriptions for the "featured categories" strip.
$categories = get_categories();

require __DIR__ . '/includes/header.php';
?>

<!-- ================= HERO ================= -->
<section class="hero">
  <div class="container hero-inner">
    <div class="hero-copy">
      <h1>Malcolm<br>Lismore<br>Studio</h1>
      <p class="lead">
        Welcome to Malcolm Lismore Photography &mdash; capturing the untamed
        beauty of Scotland's North West coast. Explore the rugged landscapes,
        witness majestic wildlife, and immerse yourself in the captivating
        essence of Scotland's coastal wonders.
      </p>
      <div class="hero-actions">
        <a class="btn btn-gold" href="gallery.php">Explore the Gallery</a>
        <a class="btn btn-outline" href="enquiry.php">Make an Enquiry</a>
      </div>
    </div>
    <div class="hero-media">
      <img src="assets/images/hero.jpg" alt="A photographer capturing the Scottish Highlands at golden hour" width="1200" height="820">
    </div>
  </div>
</section>

<!-- ================= ABOUT STUDIO ================= -->
<section class="section section-teal">
  <div class="container feature">
    <div>
      <h2 class="section-title">About Studio</h2>
      <p>
        Navigate through our site to discover the world of Malcolm Lismore.
        Learn about Malcolm's journey, his passion for photography, and his
        commitment to preserving the natural world.
      </p>
      <p>
        <strong>Portfolio:</strong> Dive into an extensive gallery showcasing
        stunning imagery &mdash; from serene landscapes to dynamic wildlife
        shots, each photograph tells a unique story.
      </p>
      <p>
        <strong>Services &amp; Pricing:</strong> Explore our packages for
        weddings, portraits and special events. <strong>Contact &amp; Booking:</strong>
        Ready to embark on your photography journey? Reach out for inquiries,
        bookings or any questions.
      </p>
      <a class="btn btn-gold" href="about.php">Read More</a>
    </div>
    <div class="feature-media">
      <img src="assets/images/about-camera.jpg" alt="Hands holding a professional camera" width="1000" height="700">
    </div>
  </div>
</section>

<!-- Curved divider into the sand gallery section -->
<div class="curve" aria-hidden="true">
  <svg viewBox="0 0 1440 120" preserveAspectRatio="none">
    <path d="M0,0 C240,120 480,120 720,80 C960,40 1200,20 1440,90 L1440,120 L0,120 Z" fill="#e7e0c6"/>
    <path d="M0,0 C240,120 480,120 720,80 C960,40 1200,20 1440,90" fill="none" stroke="#f2d24a" stroke-width="6"/>
  </svg>
</div>

<!-- ================= OUR GALLERY ================= -->
<section class="section section-sand" id="gallery-teaser">
  <div class="container">
    <div class="grid-2">
      <div>
        <h2 class="section-title">Our Gallery</h2>
        <p class="section-intro">
          A curated selection from across Scotland &mdash; landscapes, wildlife,
          coastal birds and more. Browse the full collection to find the image
          that speaks to you.
        </p>
        <a class="btn btn-gold" href="gallery.php">See More</a>
      </div>
      <div></div>
    </div>

    <div class="gallery-grid mt-2">
      <?php foreach (array_slice($featured, 0, 3) as $photo): ?>
        <a class="photo-card" href="photo.php?id=<?= (int) $photo['photo_id'] ?>">
          <div class="photo-thumb">
            <img src="<?= e($photo['image_path']) ?>" alt="<?= e($photo['alt_text']) ?>" loading="lazy" width="1200" height="800">
            <span class="tag"><?= e($photo['category_name']) ?></span>
          </div>
          <div class="photo-body">
            <h3><?= e($photo['title']) ?></h3>
            <p><?= e(mb_strimwidth((string) $photo['description'], 0, 90, 'â€¦')) ?></p>
            <span class="photo-link">View photograph &rarr;</span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Curved divider back to teal -->
<div class="curve" aria-hidden="true">
  <svg viewBox="0 0 1440 120" preserveAspectRatio="none">
    <path d="M0,120 C240,0 480,0 720,40 C960,80 1200,100 1440,30 L1440,0 L0,0 Z" fill="#0f3b47"/>
  </svg>
</div>

<!-- ================= FEATURED CATEGORIES ================= -->
<section class="section section-teal">
  <div class="container">
    <div class="text-center">
      <h2 class="section-title">Featured Categories</h2>
      <p class="section-intro" style="margin-inline:auto;">
        Five collections that capture the spirit of the Scottish Highlands.
      </p>
    </div>
    <div class="value-grid">
      <?php foreach ($categories as $cat): ?>
        <a class="value-card" href="gallery.php?category=<?= e($cat['slug']) ?>">
          <div class="ic" aria-hidden="true">&#128247;</div>
          <h3><?= e($cat['name']) ?></h3>
          <p><?= e(mb_strimwidth((string) $cat['description'], 0, 110, 'â€¦')) ?></p>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ================= TESTIMONIALS ================= -->
<section class="section section-teal">
  <div class="container">
    <div class="text-center">
      <h2 class="section-title">Kind Words</h2>
      <p class="section-intro" style="margin-inline:auto;">
        What couples, families and businesses say about working with Malcolm.
      </p>
    </div>

    <div class="testimonials mt-2" data-slider>
      <button class="slider-nav slider-prev" data-slider-prev type="button" aria-label="Previous testimonial">&larr;</button>
      <div class="testimonial-stage">
        <div class="testimonial-track" data-slider-track>
          <div class="testimonial" data-slide>
            <figure class="testimonial-card">
              <div class="testimonial-avatar">
                <img src="assets/images/avatar-1.jpg" alt="Portrait of Eilidh Fraser" width="200" height="200" loading="lazy">
              </div>
              <h3>Eilidh &amp; James</h3>
              <div class="role">Wedding &middot; Plockton</div>
              <blockquote>&ldquo;Malcolm made us feel completely at ease. The photographs are stunning &mdash; he caught moments we didn&rsquo;t even know were happening. Pure magic.&rdquo;</blockquote>
            </figure>
          </div>
          <div class="testimonial" data-slide>
            <figure class="testimonial-card">
              <div class="testimonial-avatar">
                <img src="assets/images/avatar-2.jpg" alt="Portrait of Callum MacLeod" width="200" height="200" loading="lazy">
              </div>
              <h3>Callum MacLeod</h3>
              <div class="role">Family Portrait &middot; Ullapool</div>
              <blockquote>&ldquo;Our family portrait session was relaxed and fun. The light in the final images is beautiful and they now hang proudly in our home.&rdquo;</blockquote>
            </figure>
          </div>
          <div class="testimonial" data-slide>
            <figure class="testimonial-card">
              <div class="testimonial-avatar">
                <img src="assets/images/avatar-3.jpg" alt="Portrait of Fiona Grant" width="200" height="200" loading="lazy">
              </div>
              <h3>Fiona Grant</h3>
              <div class="role">Special Event &middot; Inverness</div>
              <blockquote>&ldquo;Discreet, professional and genuinely lovely to work with. We have a full record of the evening without anyone feeling watched.&rdquo;</blockquote>
            </figure>
          </div>
        </div>
      </div>
      <button class="slider-nav slider-next" data-slider-next type="button" aria-label="Next testimonial">&rarr;</button>
      <div class="slider-dots" data-slider-dots></div>
    </div>
  </div>
</section>

<!-- ================= JOURNAL / BLOG TEASER ================= -->
<section class="section section-ivory">
  <div class="container">
    <div class="text-center">
      <h2 class="section-title">From the Journal</h2>
      <p class="section-intro" style="margin-inline:auto;">
        Stories, field notes and tips from behind the lens in the Scottish Highlands.
      </p>
    </div>
    <div class="blog-grid">
      <article class="blog-card">
        <div class="blog-thumb">
          <img src="assets/images/blog-1.jpg" alt="Misty Highland loch at sunrise" width="800" height="500" loading="lazy">
        </div>
        <div class="blog-card-body">
          <div class="blog-meta"><span>12 Jan 2025</span><span>Field Notes</span></div>
          <h3>Chasing First Light in the Highlands</h3>
          <p>How a 4am start on the shores of Loch Maree delivered the calmest, most atmospheric frames of the year.</p>
          <span class="read">Read article &rarr;</span>
        </div>
      </article>
      <article class="blog-card">
        <div class="blog-thumb">
          <img src="assets/images/blog-2.jpg" alt="Close-up of a coastal seabird" width="800" height="500" loading="lazy">
        </div>
        <div class="blog-card-body">
          <div class="blog-meta"><span>28 Nov 2024</span><span>Wildlife</span></div>
          <h3>Photographing Puffins Respectfully</h3>
          <p>A few principles for getting close to cliff-nesting seabirds without ever disturbing them or their burrows.</p>
          <span class="read">Read article &rarr;</span>
        </div>
      </article>
      <article class="blog-card">
        <div class="blog-thumb">
          <img src="assets/images/blog-3.jpg" alt="Wedding couple walking through golden light" width="800" height="500" loading="lazy">
        </div>
        <div class="blog-card-body">
          <div class="blog-meta"><span>03 Oct 2024</span><span>Weddings</span></div>
          <h3>Planning a Highland Wedding Shoot</h3>
          <p>Timing, weather and golden hour &mdash; what to consider when choosing a Scottish venue for your photographs.</p>
          <span class="read">Read article &rarr;</span>
        </div>
      </article>
    </div>
  </div>
</section>

<!-- ================= CTA BANNER ================= -->
<section class="section section-teal">
  <div class="container">
    <div class="cta-banner">
      <h2>Ready to capture your story?</h2>
      <p>Whether it&rsquo;s a wedding, a portrait session or a bespoke landscape commission, Malcolm would love to hear from you.</p>
      <div class="cta-actions">
        <a class="btn btn-gold" href="enquiry.php">Make an Enquiry</a>
        <a class="btn btn-outline" href="prices.php">View Packages</a>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>


