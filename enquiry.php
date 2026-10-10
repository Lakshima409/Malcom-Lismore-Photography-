<?php
/**
 * Enquiry form page.
 *
 * Collects booking enquiries from potential customers. Validation is done
 * on both the client (JavaScript) and the server (submit-enquiry.php).
 *
 * Malcolm Lismore Photography
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/csrf.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle       = 'Make an Enquiry | Malcolm Lismore Photography';
$pageDescription = 'Get in touch to enquire about wedding, portrait, event or landscape photography with Malcolm Lismore.';

// Service options for the drop-down (kept in sync with the prices page data).
$serviceOptions = [
    'Wedding Photography',
    'Portrait Sessions',
    'Family Photography',
    'Special Events',
    'Corporate Events',
    'Landscape & Wildlife Commissions',
    'Something else',
];

// Pre-select a service when arriving from a package button (?service=...).
$preselect = isset($_GET['service']) ? (string) $_GET['service'] : '';

// Show a session flash error if the previous submission failed.
$flashError = get_flash('error');

require __DIR__ . '/includes/header.php';
?>

<!-- ================= PAGE HERO ================= -->
<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><a href="index.php">Home</a> &rsaquo; Enquiry</div>
    <h1>Make an Enquiry</h1>
    <p>Tell us a little about your plans and Malcolm will be in touch to discuss the details.</p>
  </div>
</section>

<!-- ================= FORM ================= -->
<section class="section section-ivory">
  <div class="container" style="max-width:820px;">
    <div class="form-card">
      <?php if ($flashError !== null): ?>
        <div class="alert alert-error" role="alert"><?= e($flashError) ?></div>
      <?php endif; ?>

      <h2 style="color:var(--teal-900);">Booking Enquiry</h2>
      <p style="color:var(--muted);">Fields marked <span class="req">*</span> are required.</p>

      <form action="submit-enquiry.php" method="post" data-validate novalidate>
        <?= csrf_field() ?>

        <div class="form-grid">
          <div class="form-field">
            <label for="name">Full name <span class="req">*</span></label>
            <input type="text" id="name" name="name" required autocomplete="name"
                   value="<?= e((string) ($_SESSION['old']['name'] ?? '')) ?>">
            <span class="error-text" aria-live="polite"></span>
          </div>

          <div class="form-field">
            <label for="email">Email address <span class="req">*</span></label>
            <input type="email" id="email" name="email" required autocomplete="email"
                   value="<?= e((string) ($_SESSION['old']['email'] ?? '')) ?>">
            <span class="error-text" aria-live="polite"></span>
          </div>

          <div class="form-field">
            <label for="phone">Telephone</label>
            <input type="tel" id="phone" name="phone" autocomplete="tel"
                   value="<?= e((string) ($_SESSION['old']['phone'] ?? '')) ?>">
            <span class="error-text" aria-live="polite"></span>
          </div>

          <div class="form-field">
            <label for="service_type">Photography service <span class="req">*</span></label>
            <select id="service_type" name="service_type" required>
              <option value="">Please choose&hellip;</option>
              <?php foreach ($serviceOptions as $opt): ?>
                <?php $selected = ($preselect === $opt || ($_SESSION['old']['service_type'] ?? '') === $opt) ? 'selected' : ''; ?>
                <option value="<?= e($opt) ?>" <?= $selected ?>><?= e($opt) ?></option>
              <?php endforeach; ?>
            </select>
            <span class="error-text" aria-live="polite"></span>
          </div>

          <div class="form-field">
            <label for="event_date">Wedding or event date</label>
            <input type="date" id="event_date" name="event_date"
                   value="<?= e((string) ($_SESSION['old']['event_date'] ?? '')) ?>">
            <span class="error-text" aria-live="polite"></span>
          </div>

          <div class="form-field">
            <label for="location">Event location</label>
            <input type="text" id="location" name="location" autocomplete="off"
                   placeholder="Town, city or venue"
                   value="<?= e((string) ($_SESSION['old']['location'] ?? '')) ?>">
            <span class="error-text" aria-live="polite"></span>
          </div>

          <div class="form-field full">
            <label for="budget">Approximate budget (optional)</label>
            <select id="budget" name="budget">
              <option value="">Prefer not to say</option>
              <?php
              $budgets = ['Under £200', '£200 - £500', '£500 - £1000', '£1000 - £2000', '£2000+', 'Not sure yet'];
              $oldBudget = $_SESSION['old']['budget'] ?? '';
              foreach ($budgets as $b):
              ?>
                <option value="<?= e($b) ?>" <?= $oldBudget === $b ? 'selected' : '' ?>><?= e($b) ?></option>
              <?php endforeach; ?>
            </select>
            <span class="error-text" aria-live="polite"></span>
          </div>

          <div class="form-field full">
            <label for="message">Your message &amp; special requirements <span class="req">*</span></label>
            <textarea id="message" name="message" required><?= e((string) ($_SESSION['old']['message'] ?? '')) ?></textarea>
            <span class="error-text" aria-live="polite"></span>
          </div>

          <div class="form-consent">
            <label style="display:flex;gap:0.6rem;align-items:flex-start;">
              <input type="checkbox" name="consent" value="1" required style="margin-top:0.3rem;width:auto;">
              <span>
                I agree that my details may be used to respond to my enquiry,
                in line with the <a href="#">privacy notice</a>. <span class="req">*</span>
              </span>
            </label>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-gold">Send Enquiry</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>

<?php
// Clear the sticky form values now that they have been rendered.
unset($_SESSION['old']);
require __DIR__ . '/includes/footer.php';
?>

