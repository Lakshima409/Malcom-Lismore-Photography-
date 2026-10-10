<?php
/**
 * Enquiry management (list).
 *
 * Lists all booking enquiries with optional search and status filtering.
 *
 * Malcolm Lismore Photography
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin-header.php';

$pageTitle = 'Enquiries';

$search = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
$status = isset($_GET['status']) ? trim((string) $_GET['status']) : '';
$validStatuses = ['new', 'contacted', 'booked', 'closed'];
if (!in_array($status, $validStatuses, true)) {
    $status = '';
}

// Build the query with optional filters (always parameterised).
$sql = 'SELECT enquiry_id, name, email, service_type, event_date, status, created_at
        FROM enquiries';
$where = [];
$params = [];

if ($search !== '') {
    $where[] = '(name LIKE ? OR email LIKE ? OR service_type LIKE ? OR location LIKE ?)';
    $like = '%' . $search . '%';
    array_push($params, $like, $like, $like, $like);
}
if ($status !== '') {
    $where[] = 'status = ?';
    $params[] = $status;
}
if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}
$sql .= ' ORDER BY created_at DESC';

$stmt = db()->prepare($sql);
$stmt->execute($params);
$enquiries = $stmt->fetchAll();
?>

<div class="admin-title">
  <h1 style="color:var(--teal-900);">Enquiries</h1>
</div>

<div class="panel">
  <div class="panel-head">
    <h2><?= count($enquiries) ?> enquir<?= count($enquiries) === 1 ? 'y' : 'ies' ?></h2>
    <form method="get" class="table-tools" action="enquiries.php">
      <label class="sr-only" for="q">Search</label>
      <input type="search" id="q" name="q" placeholder="Search name, email, service…"
             value="<?= e($search) ?>">
      <label class="sr-only" for="status">Status</label>
      <select id="status" name="status">
        <option value="">All statuses</option>
        <?php foreach ($validStatuses as $s): ?>
          <option value="<?= e($s) ?>" <?= $status === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-teal btn-sm" type="submit">Filter</button>
      <?php if ($search !== '' || $status !== ''): ?>
        <a class="btn btn-sm" href="enquiries.php" style="background:#e6e6e6;">Reset</a>
      <?php endif; ?>
    </form>
  </div>
  <div class="panel-body">
    <?php if (empty($enquiries)): ?>
      <p style="color:var(--muted);">No enquiries match your search.</p>
    <?php else: ?>
      <table class="data-table">
        <thead>
          <tr>
            <th>Name</th><th>Email</th><th>Service</th><th>Event date</th>
            <th>Status</th><th>Received</th><th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($enquiries as $row): ?>
            <tr>
              <td><?= e($row['name']) ?></td>
              <td><a class="link-btn" href="mailto:<?= e($row['email']) ?>"><?= e($row['email']) ?></a></td>
              <td><?= e((string) ($row['service_type'] ?? '—')) ?></td>
              <td><?= $row['event_date'] ? e(date('j M Y', strtotime((string) $row['event_date']))) : '—' ?></td>
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
