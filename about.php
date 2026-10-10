<?php
/**
 * About page.
 *
 * Introduces Malcolm the photographer, his philosophy and the services he
 * offers, to build trust with potential customers.
 *
 * Malcolm Lismore Photography
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/csrf.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle       = 'About Malcolm | Malcolm Lismore Photography';
$pageDescription = 'Meet Malcolm Lismore - a Scottish photographer passionate about the landscapes, wildlife and people of the North West Highlands.';

require __DIR__ . '/includes/header.php';
?>

<!-- ================= PAGE HERO ================= -->
<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><a href="index.php">Home</a> &rsaquo; About</div>
    <h1>About Malcolm</h1>
    <p>Photographer, walker and lifelong resident of the North West Highlands.</p>
  </div>
</section>

<!-- ================= INTRO ================= -->
<section class="section section-teal">
  <div class="container feature">
    <div>
      <h2 class="section-title">A Passion for Scotland</h2>
      <p>
        Malcolm Lismore has spent more than fifteen years exploring the hills,
        sea lochs and coastline of the Scottish Highlands. What began as a love
        of hill walking grew into a dedication to capturing the light, weather
        and wildlife that make this corner of the world so unique.
      </p>
      <p>
        His work balances two great loves: the patience of landscape and wildlife
        photography &mdash; waiting for the moment the light breaks over a ridge
        &mdash; and the warmth of documenting weddings, portraits and family
        celebrations with an unobtrusive, documentary eye.
      </p>
      <p>
        Every commission is treated with the same care: a calm working style,
        honest storytelling, and images that you will be proud to return to for
        years to come.
      </p>
      <div class="hero-actions">
        <a class="btn btn-gold" href="gallery.php">Explore the Gallery</a>
        <a class="btn btn-outline" href="enquiry.php">Make an Enquiry</a>
      </div>
    </div>
    <div class="feature-media">
      <img src="assets/images/malcolm.jpg" alt="Portrait of Malcolm Lismore holding a camera in the Highlands" width="800" height="800" loading="lazy">
    </div>
  </div>
</section>

<!-- ================= PHILOSOPHY / VALUES ================= -->
<section class="section section-ivory">
  <div class="container">
    <div class="text-center">
      <h2 class="section-title">Photography Philosophy</h2>
      <p class="section-intro" style="margin-inline:auto;">
        Three principles guide every shoot, from a windswept summit to a
        candlelit reception.
      </p>
    </div>
    <div class="value-grid">
      <div class="value-card">
        <div class="ic" aria-hidden="true">&#127793;</div>
        <h3>Respect for Nature</h3>
        <p>Wildlife and landscapes are photographed without disturbance, always prioritising the welfare of the subject and the environment.</p>
      </div>
      <div class="value-card">
        <div class="ic" aria-hidden="true">&#128247;</div>
        <h3>Honest Storytelling</h3>
        <p>Real moments over posed perfection. The best photographs are the ones that feel true to the people and places in them.</p>
      </div>
      <div class="value-card">
        <div class="ic" aria-hidden="true">&#10024;</div>
        <h3>Natural Light</h3>
        <p>Wherever possible Malcolm works with available light, from golden Highland sunsets to soft window light indoors.</p>
      </div>
    </div>
  </div>
</section>

<!-- ================= SERVICES SUMMARY ================= -->
<section class="section section-sand">
  <div class="container">
    <div class="text-center">
      <h2 class="section-title">What Malcolm Offers</h2>
      <p class="section-intro" style="margin-inline:auto;">
        A small range of services, each delivered with the same attention to detail.
      </p>
    </div>
    <div class="value-grid">
      <div class="value-card" style="background:var(--white);">
        <div class="ic" aria-hidden="true">&#128141;</div>
        <h3>Weddings</h3>
        <p>Full-day documentary coverage across Scotland&rsquo;s most beautiful venues, from preparations to the first dance.</p>
      </div>
      <div class="value-card" style="background:var(--white);">
        <div class="ic" aria-hidden="true">&#129492;</div>
        <h3>Portraits &amp; Families</h3>
        <p>Relaxed sessions on location or in the studio, using natural Highland light to create timeless portraits.</p>
      </div>
      <div class="value-card" style="background:var(--white);">
        <div class="ic" aria-hidden="true">&#127881;</div>
        <h3>Special Events</h3>
        <p>Discreet coverage of birthdays, engagements and private celebrations, big or small.</p>
      </div>
    </div>
    <div class="text-center mt-2">
      <a class="btn btn-teal" href="prices.php">See Pricing &amp; Packages</a>
    </div>
  </div>
</section>

<!-- ================= CTA ================= -->
<section class="section section-teal">
  <div class="container">
    <div class="cta-banner">
      <h2>Let&rsquo;s work together</h2>
      <p>Tell Malcolm about your plans and he will be in touch to discuss how to bring your vision to life.</p>
      <div class="cta-actions">
        <a class="btn btn-gold" href="enquiry.php">Make an Enquiry</a>
        <a class="btn btn-outline" href="gallery.php">Browse the Gallery</a>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
