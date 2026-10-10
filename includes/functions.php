<?php
/**
 * Reusable helper functions.
 *
 * Malcolm Lismore Photography
 */

declare(strict_types=1);

require_once __DIR__ . '/db.php';

/**
 * Escape a value for safe output inside HTML.
 * Always use this when echoing user-supplied or database values.
 */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Get all active gallery categories ordered by name.
 *
 * @return array<int, array<string, mixed>>
 */
function get_categories(): array
{
    $stmt = db()->query('SELECT category_id, name, slug, description FROM categories ORDER BY name');
    return $stmt->fetchAll();
}

/**
 * Look up a single category by its slug.
 *
 * @return array<string, mixed>|null
 */
function get_category_by_slug(string $slug): ?array
{
    $stmt = db()->prepare('SELECT category_id, name, slug, description FROM categories WHERE slug = ? LIMIT 1');
    $stmt->execute([$slug]);
    $row = $stmt->fetch();
    return $row ?: null;
}

/**
 * Count how many photos belong to a category.
 */
function count_photos_in_category(int $categoryId): int
{
    $stmt = db()->prepare('SELECT COUNT(*) FROM photos WHERE category_id = ?');
    $stmt->execute([$categoryId]);
    return (int) $stmt->fetchColumn();
}

/**
 * Get photos, optionally filtered by category slug, with pagination.
 *
 * @return array{rows: array<int, array<string, mixed>>, total: int}
 */
function get_photos(?string $categorySlug = null, int $limit = 12, int $offset = 0): array
{
    if ($categorySlug !== null && $categorySlug !== '') {
        $countSql = 'SELECT COUNT(*) FROM photos p
                     JOIN categories c ON c.category_id = p.category_id
                     WHERE c.slug = ?';
        $countParams = [$categorySlug];

        $rowsSql = 'SELECT p.photo_id, p.title, p.description, p.image_path, p.alt_text,
                           p.location, p.created_at, c.name AS category_name, c.slug AS category_slug
                    FROM photos p
                    JOIN categories c ON c.category_id = p.category_id
                    WHERE c.slug = ?
                    ORDER BY p.created_at DESC, p.photo_id DESC
                    LIMIT ? OFFSET ?';
        $rowsParams = [$categorySlug, $limit, $offset];
    } else {
        $countSql = 'SELECT COUNT(*) FROM photos';
        $countParams = [];

        $rowsSql = 'SELECT p.photo_id, p.title, p.description, p.image_path, p.alt_text,
                           p.location, p.created_at, c.name AS category_name, c.slug AS category_slug
                    FROM photos p
                    JOIN categories c ON c.category_id = p.category_id
                    ORDER BY p.created_at DESC, p.photo_id DESC
                    LIMIT ? OFFSET ?';
        $rowsParams = [$limit, $offset];
    }

    $countStmt = db()->prepare($countSql);
    $countStmt->execute($countParams);
    $total = (int) $countStmt->fetchColumn();

    // LIMIT/OFFSET must be bound as integers.
    $rowsStmt = db()->prepare($rowsSql);
    foreach ($rowsParams as $i => $param) {
        $rowsStmt->bindValue($i + 1, $param, is_int($param) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
    $rowsStmt->execute();

    return ['rows' => $rowsStmt->fetchAll(), 'total' => $total];
}

/**
 * Get a single photo by id, joined with its category.
 *
 * @return array<string, mixed>|null
 */
function get_photo(int $photoId): ?array
{
    $stmt = db()->prepare(
        'SELECT p.photo_id, p.title, p.description, p.image_path, p.alt_text,
                p.location, p.created_at, c.name AS category_name, c.slug AS category_slug
         FROM photos p
         JOIN categories c ON c.category_id = p.category_id
         WHERE p.photo_id = ? LIMIT 1'
    );
    $stmt->execute([$photoId]);
    $row = $stmt->fetch();
    return $row ?: null;
}

/**
 * Get the previous and next photo ids within the same category (or overall).
 *
 * @return array{prev: int|null, next: int|null}
 */
function get_photo_neighbours(int $photoId, ?string $categorySlug = null): array
{
    if ($categorySlug !== null && $categorySlug !== '') {
        $prevStmt = db()->prepare(
            'SELECT p.photo_id FROM photos p JOIN categories c ON c.category_id = p.category_id
             WHERE c.slug = ? AND p.photo_id < ? ORDER BY p.photo_id DESC LIMIT 1'
        );
        $prevStmt->execute([$categorySlug, $photoId]);
        $prev = $prevStmt->fetchColumn();

        $nextStmt = db()->prepare(
            'SELECT p.photo_id FROM photos p JOIN categories c ON c.category_id = p.category_id
             WHERE c.slug = ? AND p.photo_id > ? ORDER BY p.photo_id ASC LIMIT 1'
        );
        $nextStmt->execute([$categorySlug, $photoId]);
        $next = $nextStmt->fetchColumn();
    } else {
        $prevStmt = db()->prepare('SELECT photo_id FROM photos WHERE photo_id < ? ORDER BY photo_id DESC LIMIT 1');
        $prevStmt->execute([$photoId]);
        $prev = $prevStmt->fetchColumn();

        $nextStmt = db()->prepare('SELECT photo_id FROM photos WHERE photo_id > ? ORDER BY photo_id ASC LIMIT 1');
        $nextStmt->execute([$photoId]);
        $next = $nextStmt->fetchColumn();
    }

    return [
        'prev' => $prev !== false ? (int) $prev : null,
        'next' => $next !== false ? (int) $next : null,
    ];
}

/**
 * Get all active services for the prices page.
 *
 * @return array<int, array<string, mixed>>
 */
function get_services(): array
{
    $stmt = db()->query(
        'SELECT service_id, name, description, starting_price, price_note, includes,
                image_path, alt_text
         FROM services
         WHERE is_active = 1
         ORDER BY sort_order, name'
    );
    return $stmt->fetchAll();
}

/**
 * Format a price for display, e.g. "£1,000".
 */
function format_price(?string $price): ?string
{
    if ($price === null || $price === '') {
        return null;
    }
    $val = (float) $price;
    $decimals = ($val == floor($val)) ? 0 : 2;
    return '£' . number_format($val, $decimals);
}

/**
 * Redirect to another page and stop execution.
 */
function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

/**
 * Safely handle an uploaded image file.
 *
 * Validates the upload with getimagesize(), checks the file extension
 * against an allow-list, enforces a maximum size, and stores it under
 * assets/uploads/ with a random, hard-to-guess filename. Returns either
 * ['path' => string] on success or ['error' => string] on failure.
 *
 * @param array<string, mixed> $file  An entry from $_FILES.
 * @return array{path?: string, error?: string}
 */
function handle_image_upload(array $file): array
{
    // Basic PHP upload error handling.
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['error' => 'Invalid upload.'];
    }
    switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return ['error' => 'The uploaded file is too large (max 5 MB).'];
        case UPLOAD_ERR_NO_FILE:
            return ['error' => 'No file was uploaded.'];
        default:
            return ['error' => 'Sorry, the upload failed. Please try again.'];
    }

    // Enforce a maximum size of 5 MB.
    $maxBytes = 5 * 1024 * 1024;
    if (!isset($file['size']) || (int) $file['size'] <= 0 || (int) $file['size'] > $maxBytes) {
        return ['error' => 'The uploaded file must be smaller than 5 MB.'];
    }

    // Verify the file is a genuine image (not just a renamed file).
    $imageInfo = @getimagesize((string) $file['tmp_name']);
    if ($imageInfo === false) {
        return ['error' => 'That file is not a valid image.'];
    }

    // Allow-list of permitted image types.
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp',
    ];
    $mime = $imageInfo['mime'] ?? '';
    if (!isset($allowed[$mime])) {
        return ['error' => 'Only JPG, PNG, GIF and WebP images are allowed.'];
    }

    // Generate a safe, unique filename using the allowed extension.
    $ext      = $allowed[$mime];
    $filename = bin2hex(random_bytes(12)) . '.' . $ext;

    // Destination directory (inside the web root, under assets/uploads).
    $uploadDir = dirname(__DIR__) . '/assets/uploads';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
        return ['error' => 'Could not create the upload directory.'];
    }

    $destination = $uploadDir . '/' . $filename;
    if (!move_uploaded_file((string) $file['tmp_name'], $destination)) {
        return ['error' => 'Could not save the uploaded file.'];
    }
    @chmod($destination, 0644);

    // Return a web-relative path (consistent with other image_path values).
    return ['path' => 'assets/uploads/' . $filename];
}


/**
 * Simple flash-message helper stored in the session.
 */
function set_flash(string $type, string $message): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['flash'][$type] = $message;
}

/**
 * Return and clear a flash message of a given type.
 */
function get_flash(string $type): ?string
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!empty($_SESSION['flash'][$type])) {
        $msg = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $msg;
    }
    return null;
}

