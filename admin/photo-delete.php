<?php
/**
 * Delete a photograph.
 *
 * POST-only and CSRF-protected. Removes the photo record and, if the file
 * lives under assets/uploads/, deletes the physical file too.
 *
 * Malcolm Lismore Photography
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin-header.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('photos.php');
}
csrf_check_or_abort();

$id = isset($_POST['photo_id']) ? (int) $_POST['photo_id'] : 0;
if ($id <= 0) {
    set_flash('error', 'Invalid photograph.');
    redirect('photos.php');
}

// Fetch the record so we can clean up an uploaded file if present.
$stmt = db()->prepare('SELECT image_path FROM photos WHERE photo_id = ? LIMIT 1');
$stmt->execute([$id]);
$photo = $stmt->fetch();

if ($photo) {
    // Delete the database record (categories FK uses ON DELETE CASCADE, but
    // photos is the child here, so a simple delete is fine).
    $del = db()->prepare('DELETE FROM photos WHERE photo_id = ?');
    $del->execute([$id]);

    // Remove the uploaded file only if it lives in our uploads directory.
    $path = (string) $photo['image_path'];
    if (str_starts_with($path, 'assets/uploads/')) {
        $full = dirname(__DIR__) . '/' . $path;
        if (is_file($full)) {
            @unlink($full);
        }
    }

    set_flash('success', 'Photograph deleted.');
} else {
    set_flash('error', 'That photograph could not be found.');
}

redirect('photos.php');
