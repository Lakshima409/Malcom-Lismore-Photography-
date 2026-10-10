<?php
/**
 * FAQ / Contact page.
 *
 * Answers common questions and provides a route to the enquiry form.
 * Uses an accessible accordion (see assets/js/script.js).
 *
 * Malcolm Lismore Photography
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/csrf.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle       = 'Contact &amp; FAQ | Malcolm Lismore Photography';
$pageDescription = 'Answers to common questions about booking, travel, delivery and prints, plus how to get in touch.';

$faqs = [
    ['How far in advance should I book?', 'For weddings, six to twelve months ahead is ideal, especially for summer weekends. Portraits and events can often be arranged with a few weeks notice, subject to availability.'],
    ['Do you travel outside the Highlands?', 'Yes. Malcolm is based in Ullapool and regularly works across Scotland and beyond. Travel is quoted transparently as part of your package.'],
    ['When will I receive my photographs?', 'A small selection of previews is shared within a week. The full edited gallery is usually delivered within four to six weeks for weddings, and sooner for portraits and events.'],
    ['Can we meet before booking?', 'Absolutely. A relaxed chat — in person or online — is encouraged so you can get to know Malcolm and be comfortable on the day.'],
    ['Do you offer prints and fine-art products?', 'Yes. High-quality fine-art prints and framed products are available, and landscape or wildlife commissions can be produced to your chosen size.'],
    ['What happens if the weather is bad?', 'Scottish weather is part of the adventure. Malcolm plans for all conditions and can often use dramatic skies to create striking, memorable images.'],
];

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><a href="index.php">Home</a> &rsaquo; Contact &amp; FAQ</div>
    <h1>Contact &amp; FAQ</h1>
    <p>Answers to common questions — and how to reach Malcolm directly.</p>
  </div>
</section>

<section class="section section-ivory">
  <div class="container">
    <div class="faq-list">
      <?php foreach ($faqs as $i => $faq): ?>
        <div class="faq-item" data-faq-item>
          <button class="faq-q" data-faq-q type="button" aria-expanded="false">
            <span><?= e($faq[0]) ?></span>
            <span class="chev" aria-hidden="true">&#9662;</span>
          </button>
          <div class="faq-a" data-faq-a>
            <div class="faq-a-inner"><?= e($faq[1]) ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="cta-banner mt-2">
      <h2>Still have a question?</h2>
      <p>Send an enquiry and Malcolm will get back to you personally, usually within two working days.</p>
      <div class="cta-actions">
        <a class="btn btn-gold" href="enquiry.php">Make an Enquiry</a>
        <a class="btn btn-outline" href="mailto:hello@malcolmlismore.co.uk">Email Us</a>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
