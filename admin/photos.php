<?php
/**
 * Gallery management (list).
 *
 * Lists all photographs with links to edit or delete each one.
 *
 * Malcolm Lismore Photography
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin-header.php';

$pageTitle = 'Manage Photos';

$photos = db()->query(
    'SELECT p.photo_id, p.title, p.image_path, p.created_at, c.name AS category_name
     FROM photos p
     JOIN categories c ON c.category_id = p.category_id
     ORDER BY p.created_at DESC, p.photo_id DESC'
)->fetchAll();
?>

<div class="admin-title">
  <h1 style="color:var(--teal-900);">Photographs</h1>
  <a class="btn btn-gold btn-sm" href="photo-add.php">+ Add Photo</a>
</div>

<div class="panel">
  <div class="panel-head">
    <h2><?= count($photos) ?> photograph<?= count($photos) === 1 ? '' : 's' ?></h2>
  </div>
  <div class="panel-body">
    <?php if (empty($photos)): ?>
      <p style="color:var(--muted);">No photographs yet. <a class="link-btn" href="photo-add.php">Add the first one</a>.</p>
    <?php else: ?>
      <table class="data-table">
        <thead>
          <tr><th style="width:90px;">Image</th><th>Title</th><th>Category</th><th>Added</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php foreach ($photos as $row): ?>
            <tr>
              <td><img src="../<?= e($row['image_path']) ?>" alt="" style="width:64px;height:48px;object-fit:cover;border-radius:6px;"></td>
              <td><?= e($row['title']) ?></td>
              <td><?= e($row['category_name']) ?></td>
              <td><?= e(date('j M Y', strtotime((string) $row['created_at']))) ?></td>
              <td>
                <div class="row-actions">
                  <a class="link-btn" href="photo-edit.php?id=<?= (int) $row['photo_id'] ?>">Edit</a>
                  <form method="post" action="photo-delete.php" style="display:inline;"
                        onsubmit="return confirm('Delete this photograph permanently?');">
                    <?= csrf_field() ?>
                    <input type="hidden" name="photo_id" value="<?= (int) $row['photo_id'] ?>">
                    <button type="submit" class="link-btn danger">Delete</button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/../includes/admin-footer.php'; ?>
