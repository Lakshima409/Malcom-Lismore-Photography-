<?php
/**
 * Add a gallery photograph.
 *
 * Presents a form to create a new photo record. Supports either choosing an
 * existing image path or uploading a new image file (which is validated and
 * stored under assets/uploads/).
 *
 * Malcolm Lismore Photography
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin-header.php';

$pageTitle = 'Add Photograph';

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

    // --- Validation -----------------------------------------------------
    if ($title === '')       { $errors[] = 'Please enter a title.'; }
    if ($altText === '')     { $errors[] = 'Please enter alt text (important for accessibility).'; }
    if ($categoryId <= 0)    { $errors[] = 'Please choose a category.'; }

    // Handle optional upload.
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

    // --- Insert ---------------------------------------------------------
    if (empty($errors)) {
        $stmt = db()->prepare(
            'INSERT INTO photos (category_id, title, description, image_path, alt_text, location)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $categoryId, $title,
            $description !== '' ? $description : null,
            $imagePath, $altText,
            $location !== '' ? $location : null,
        ]);
        set_flash('success', 'Photograph added successfully.');
        redirect('photos.php');
    }
}
?>

<div class="admin-title">
  <h1 style="color:var(--teal-900);">Add Photograph</h1>
  <a class="btn btn-sm" style="background:#e6e6e6;color:var(--ink);" href="photos.php">&larr; Back</a>
</div>

<div class="panel">
  <div class="panel-body">
    <?php foreach ($errors as $err): ?>
      <div class="alert alert-error" role="alert"><?= e($err) ?></div>
    <?php endforeach; ?>

    <form method="post" action="photo-add.php" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="form-grid">
        <div class="form-field full">
          <label for="title">Title <span class="req">*</span></label>
          <input type="text" id="title" name="title" required
                 value="<?= e((string) ($_POST['title'] ?? '')) ?>">
        </div>

        <div class="form-field">
          <label for="category_id">Category <span class="req">*</span></label>
          <select id="category_id" name="category_id" required>
            <option value="">Please chooseâ€¦</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= (int) $cat['category_id'] ?>"
                <?= (int) ($_POST['category_id'] ?? 0) === (int) $cat['category_id'] ? 'selected' : '' ?>>
                <?= e($cat['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-field">
          <label for="location">Location</label>
          <input type="text" id="location" name="location"
                 value="<?= e((string) ($_POST['location'] ?? '')) ?>">
        </div>

        <div class="form-field full">
          <label for="description">Description</label>
          <textarea id="description" name="description"><?= e((string) ($_POST['description'] ?? '')) ?></textarea>
        </div>

        <div class="form-field full">
          <label for="alt_text">Alt text (describe the image) <span class="req">*</span></label>
          <input type="text" id="alt_text" name="alt_text" required
                 value="<?= e((string) ($_POST['alt_text'] ?? '')) ?>">
        </div>

        <div class="form-field full">
          <label for="image_file">Upload image (JPG, PNG, GIF or WebP, max 5&nbsp;MB)</label>
          <input type="file" id="image_file" name="image_file" accept="image/*">
        </div>

        <div class="form-field full">
          <label for="image_path">&hellip; or use an existing image path</label>
          <input type="text" id="image_path" name="image_path"
                 placeholder="e.g. assets/images/landscape-1.jpg"
                 value="<?= e((string) ($_POST['image_path'] ?? '')) ?>">
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-gold">Add Photograph</button>
          <a class="btn" style="background:#e6e6e6;color:var(--ink);" href="photos.php">Cancel</a>
        </div>
      </div>
    </form>
  </div>
</div>

<?php require __DIR__ . '/../includes/admin-footer.php'; ?>
