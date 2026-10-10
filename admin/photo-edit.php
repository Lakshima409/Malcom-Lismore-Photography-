<?php
/**
 * Edit a photograph's details.
 *
 * Malcolm Lismore Photography
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin-header.php';

$pageTitle = 'Edit Photograph';

$id        = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$stmt      = db()->prepare('SELECT * FROM photos WHERE photo_id = ? LIMIT 1');
$stmt->execute([$id]);
$photo     = $stmt->fetch();

if (!$photo) {
    set_flash('error', 'That photograph could not be found.');
    redirect('photos.php');
}

$categories = get_categories();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check_or_abort();

    $title       = trim((string) ($_POST['title'] ?? ''));
    $description = trim((string) ($_POST['description'] ?? ''));
    $altText     = trim((string) ($_POST['alt_text'] ?? ''));
    $location    = trim((string) ($_POST['location'] ?? ''));
    $categoryId  = (int) ($_POST['category_id'] ?? 0);
    $imagePath   = trim((string) ($_POST['image_path'] ?? ''));

    if ($title === '')    { $errors[] = 'Please enter a title.'; }
    if ($altText === '')  { $errors[] = 'Please enter alt text.'; }
    if ($categoryId <= 0) { $errors[] = 'Please choose a category.'; }

    // Handle optional replacement upload.
    if (!empty($_FILES['image_file']) && is_array($_FILES['image_file']) && ($_FILES['image_file']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
        $result = handle_image_upload($_FILES['image_file']);
        if (isset($result['error'])) {
            $errors[] = $result['error'];
        } else {
            $imagePath = $result['path'];
        }
    }

    if ($imagePath === '') {
        $errors[] = 'Please provide an image path or upload a file.';
    }

    if (empty($errors)) {
        $stmt = db()->prepare(
            'UPDATE photos
             SET category_id = ?, title = ?, description = ?, image_path = ?, alt_text = ?, location = ?
             WHERE photo_id = ?'
        );
        $stmt->execute([
            $categoryId, $title,
            $description !== '' ? $description : null,
            $imagePath, $altText,
            $location !== '' ? $location : null,
            $id,
        ]);
        set_flash('success', 'Photograph updated successfully.');
        redirect('photos.php');
    }

    // Keep the user's edits in the form on error.
    $photo['title']       = $title;
    $photo['description'] = $description;
    $photo['alt_text']    = $altText;
    $photo['location']    = $location;
    $photo['image_path']  = $imagePath;
}
?>

<div class="admin-title">
  <h1 style="color:var(--teal-900);">Edit Photograph</h1>
  <a class="btn btn-sm" style="background:#e6e6e6;color:var(--ink);" href="photos.php">&larr; Back</a>
</div>

<div class="panel">
  <div class="panel-body">
    <?php foreach ($errors as $err): ?>
      <div class="alert alert-error" role="alert"><?= e($err) ?></div>
    <?php endforeach; ?>

    <form method="post" action="photo-edit.php?id=<?= (int) $photo['photo_id'] ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="form-grid">
        <div class="form-field full">
          <label for="title">Title <span class="req">*</span></label>
          <input type="text" id="title" name="title" required value="<?= e((string) $photo['title']) ?>">
        </div>

        <div class="form-field">
          <label for="category_id">Category <span class="req">*</span></label>
          <select id="category_id" name="category_id" required>
            <option value="">Please choose…</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= (int) $cat['category_id'] ?>"
                <?= (int) $photo['category_id'] === (int) $cat['category_id'] ? 'selected' : '' ?>>
                <?= e($cat['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-field">
          <label for="location">Location</label>
          <input type="text" id="location" name="location" value="<?= e((string) ($photo['location'] ?? '')) ?>">
        </div>

        <div class="form-field full">
          <label for="description">Description</label>
          <textarea id="description" name="description"><?= e((string) ($photo['description'] ?? '')) ?></textarea>
        </div>

        <div class="form-field full">
          <label for="alt_text">Alt text <span class="req">*</span></label>
          <input type="text" id="alt_text" name="alt_text" required value="<?= e((string) $photo['alt_text']) ?>">
        </div>

        <div class="form-field full">
          <label for="image_file">Replace image (optional)</label>
          <input type="file" id="image_file" name="image_file" accept="image/*">
        </div>

        <div class="form-field full">
          <label for="image_path">Image path</label>
          <input type="text" id="image_path" name="image_path" value="<?= e((string) $photo['image_path']) ?>">
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-gold">Save Changes</button>
          <a class="btn" style="background:#e6e6e6;color:var(--ink);" href="photos.php">Cancel</a>
        </div>
      </div>
    </form>
  </div>
</div>

<?php require __DIR__ . '/../includes/admin-footer.php'; ?>
