<?php
/**
 * Enquiry confirmation page.
 *
 * Only displays a genuine "thank you" when an enquiry was just recorded.
 * If a visitor reaches this page directly (no recent enquiry in the
 * session), it does NOT claim success and instead points them back to the
 * enquiry form.
 *
 * Malcolm Lismore Photography
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/csrf.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// A genuine submission sets this session value then redirects here.
$confirmed = isset($_SESSION['last_enquiry_id']) && (int) $_SESSION['last_enquiry_id'] > 0;

// Look up the confirmed enquiry so we can personalise the message.
$enquiry = null;
if ($confirmed) {
    $stmt = db()->prepare('SELECT name, service_type FROM enquiries WHERE enquiry_id = ? LIMIT 1');
    $stmt->execute([(int) $_SESSION['last_enquiry_id']]);
    $enquiry = $stmt->fetch() ?: null;
}

// Consume the one-time confirmation marker so the page cannot be refreshed
// to falsely re-confirm, and so a direct visit shows the "no enquiry" state.
$hadConfirmation = $confirmed && $enquiry !== null;
unset($_SESSION['last_enquiry_id']);

if ($hadConfirmation) {
    $pageTitle       = 'Thank You | Malcolm Lismore Photography';
    $pageDescription = 'Your enquiry has been received. Thank you for getting in touch with Malcolm Lismore Photography.';
} else {
    $pageTitle       = 'No Enquiry Found | Malcolm Lismore Photography';
    $pageDescription = 'No recent enquiry was found on this device.';
}

require __DIR__ . '/includes/header.php';
?>

<?php if ($hadConfirmation): ?>
  <!-- ================= CONFIRMED ================= -->
  <section class="page-hero">
    <div class="container">
      <div class="breadcrumb"><a href="index.php">Home</a> &rsaquo; Enquiry Sent</div>
      <h1>Thank You<?= $enquiry && $enquiry['name'] ? ', ' . e(explode(' ', (string) $enquiry['name'])[0]) : '' ?>!</h1>
      <p>Your enquiry has been received and saved to our records.</p>
    </div>
  </section>

  <section class="section section-ivory">
    <div class="container" style="max-width:760px;">
      <div class="form-card text-center">
        <div class="alert alert-success" role="status" style="margin-bottom:1.5rem;">
          We&rsquo;ve received your enquiry<?= $enquiry && $enquiry['service_type'] ? ' about <strong>' . e((string) $enquiry['service_type']) . '</strong>' : '' ?>.
        </div>
        <h2 style="color:var(--teal-900);">What happens next?</h2>
        <ol style="text-align:left;color:var(--muted);max-width:46ch;margin-inline:auto;line-height:2;">
          <li>Malcolm will review your enquiry personally.</li>
          <li>You&rsquo;ll receive a reply by email, usually within two working days.</li>
          <li>We&rsquo;ll arrange a quick chat to discuss availability and a tailored quote.</li>
        </ol>
        <div class="cta-actions">
          <a class="btn btn-gold" href="index.php">Back to Home</a>
          <a class="btn btn-outline" href="gallery.php">Explore the Gallery</a>
        </div>
      </div>
    </div>
  </section>
<?php else: ?>
  <!-- ================= NO ENQUIRY FOUND ================= -->
  <section class="page-hero">
    <div class="container">
      <h1>No Enquiry Found</h1>
      <p>We couldn&rsquo;t find a recent enquiry on this device.</p>
    </div>
  </section>

  <section class="section section-teal">
    <div class="container" style="max-width:640px;">
      <div class="form-card text-center">
        <div class="alert alert-info">
          This page only confirms an enquiry that has just been submitted on
          this device. If you believe this is a mistake, please send your
          enquiry again.
        </div>
        <a class="btn btn-gold" href="enquiry.php">Go to the Enquiry Form</a>
      </div>
    </div>
  </section>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
