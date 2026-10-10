<?php
/**
 * Enquiry detail view.
 *
 * Shows a single enquiry in full and provides a form to update its status.
 *
 * Malcolm Lismore Photography
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin-header.php';

$pageTitle = 'Enquiry Details';

$id  = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$stmt = db()->prepare('SELECT * FROM enquiries WHERE enquiry_id = ? LIMIT 1');
$stmt->execute([$id]);
$enquiry = $stmt->fetch();

if (!$enquiry) {
    set_flash('error', 'That enquiry could not be found.');
    redirect('enquiries.php');
}

$validStatuses = ['new', 'contacted', 'booked', 'closed'];
?>

<div class="admin-title">
  <h1 style="color:var(--teal-900);">Enquiry from <?= e($enquiry['name']) ?></h1>
  <a class="btn btn-sm" style="background:#e6e6e6;color:var(--ink);" href="enquiries.php">&larr; Back to list</a>
</div>

<div class="panel">
  <div class="panel-head">
    <h2>Details</h2>
    <span class="status-pill status-<?= e($enquiry['status']) ?>"><?= e($enquiry['status']) ?></span>
  </div>
  <div class="panel-body">
    <table class="data-table">
      <tbody>
        <tr><th style="width:180px;">Name</th><td><?= e($enquiry['name']) ?></td></tr>
        <tr><th>Email</th><td><a class="link-btn" href="mailto:<?= e($enquiry['email']) ?>"><?= e($enquiry['email']) ?></a></td></tr>
        <tr><th>Phone</th><td><?= e((string) ($enquiry['phone'] ?? '—')) ?></td></tr>
        <tr><th>Service</th><td><?= e((string) ($enquiry['service_type'] ?? '—')) ?></td></tr>
        <tr><th>Event date</th><td><?= $enquiry['event_date'] ? e(date('j F Y', strtotime((string) $enquiry['event_date']))) : '—' ?></td></tr>
        <tr><th>Location</th><td><?= e((string) ($enquiry['location'] ?? '—')) ?></td></tr>
        <tr><th>Budget</th><td><?= e((string) ($enquiry['budget'] ?? '—')) ?></td></tr>
        <tr><th>Received</th><td><?= e(date('j F Y, g:ia', strtotime((string) $enquiry['created_at']))) ?></td></tr>
        <tr><th>Message</th><td style="white-space:pre-line;"><?= e($enquiry['message']) ?></td></tr>
      </tbody>
    </table>
  </div>
</div>

<div class="panel mt-2">
  <div class="panel-head"><h2>Update Status</h2></div>
  <div class="panel-body">
    <form method="post" action="enquiry-update.php">
      <?= csrf_field() ?>
      <input type="hidden" name="enquiry_id" value="<?= (int) $enquiry['enquiry_id'] ?>">
      <div class="form-field" style="max-width:320px;">
        <label for="status">Status</label>
        <select id="status" name="status" required>
          <?php foreach ($validStatuses as $s): ?>
            <option value="<?= e($s) ?>" <?= $enquiry['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="btn btn-gold" style="margin-top:1rem;">Save Status</button>
    </form>
  </div>
</div>

<?php require __DIR__ . '/../includes/admin-footer.php'; ?>
