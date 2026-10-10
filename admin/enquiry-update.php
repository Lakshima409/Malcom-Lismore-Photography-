<?php
/**
 * Update an enquiry's status.
 *
 * POST-only, CSRF-protected. Redirects back to the enquiry view on success.
 *
 * Malcolm Lismore Photography
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin-header.php';

// Only accept POST with a valid CSRF token.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('enquiries.php');
}
csrf_check_or_abort();

$id     = isset($_POST['enquiry_id']) ? (int) $_POST['enquiry_id'] : 0;
$status = isset($_POST['status']) ? trim((string) $_POST['status']) : '';
$valid  = ['new', 'contacted', 'booked', 'closed'];

if ($id <= 0) {
    set_flash('error', 'Invalid enquiry.');
    redirect('enquiries.php');
}
if (!in_array($status, $valid, true)) {
    set_flash('error', 'Invalid status value.');
    redirect('enquiry-view.php?id=' . $id);
}

$stmt = db()->prepare('UPDATE enquiries SET status = ? WHERE enquiry_id = ?');
$stmt->execute([$status, $id]);

set_flash('success', 'Enquiry status updated to "' . $status . '".');
redirect('enquiry-view.php?id=' . $id);
