<?php
/**
 * Admin dashboard.
 *
 * Shows summary statistics (new enquiries, total photos, recent activity)
 * and quick links to the management areas.
 *
 * Malcolm Lismore Photography
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin-header.php';

$pageTitle = 'Dashboard';

// Summary counts.
$newEnquiries  = (int) db()->query("SELECT COUNT(*) FROM enquiries WHERE status = 'new'")->fetchColumn();
$totalEnquiry  = (int) db()->query('SELECT COUNT(*) FROM enquiries')->fetchColumn();
$totalPhotos   = (int) db()->query('SELECT COUNT(*) FROM photos')->fetchColumn();
$totalCats     = (int) db()->query('SELECT COUNT(*) FROM categories')->fetchColumn();

// Most recent enquiries.
$recent = db()->query(
    'SELECT enquiry_id, name, service_type, status, created_at
     FROM enquiries ORDER BY created_at DESC LIMIT 5'
)->fetchAll();
?>

<div class="admin-title">
  <h1 style="color:var(--teal-900);">Dashboard</h1>
  <div style="display:flex;gap:0.6rem;flex-wrap:wrap;">
    <a class="btn btn-teal btn-sm" href="photo-add.php">+ Add Photo</a>
    <a class="btn btn-gold btn-sm" href="enquiries.php">View Enquiries</a>
  </div>
</div>

<div class="stat-grid">
  <div class="stat-card">
    <div class="num"><?= $newEnquiries ?></div>
    <div class="lbl">New enquiries</div>
  </div>
  <div class="stat-card">
    <div class="num"><?= $totalEnquiry ?></div>
    <div class="lbl">Total enquiries</div>
  </div>
  <div class="stat-card">
    <div class="num"><?= $totalPhotos ?></div>
    <div class="lbl">Photographs</div>
  </div>
  <div class="stat-card">
    <div class="num"><?= $totalCats ?></div>
    <div class="lbl">Categories</div>
  </div>
</div>

<div class="panel">
  <div class="panel-head">
    <h2>Recent Enquiries</h2>
    <a class="link-btn" href="enquiries.php">View all &rarr;</a>
  </div>
  <div class="panel-body">
    <?php if (empty($recent)): ?>
      <p style="color:var(--muted);">No enquiries have been submitted yet.</p>
    <?php else: ?>
      <table class="data-table">
        <thead>
          <tr><th>Name</th><th>Service</th><th>Status</th><th>Received</th><th></th></tr>
        </thead>
        <tbody>
          <?php foreach ($recent as $row): ?>
            <tr>
              <td><?= e($row['name']) ?></td>
              <td><?= e((string) ($row['service_type'] ?? '—')) ?></td>
              <td><span class="status-pill status-<?= e($row['status']) ?>"><?= e($row['status']) ?></span></td>
              <td><?= e(date('j M Y', strtotime((string) $row['created_at']))) ?></td>
              <td><a class="link-btn" href="enquiry-view.php?id=<?= (int) $row['enquiry_id'] ?>">View</a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/../includes/admin-footer.php'; ?>
