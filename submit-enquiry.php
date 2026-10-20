<?php
/**
 * Server-side enquiry processing.
 *
 * Validates the submitted form, verifies the CSRF token, and inserts a new
 * enquiry into the database using a prepared statement. On success it
 * redirects to success.php; on failure it returns the user to the form with
 * a friendly error.
 *
 * Malcolm Lismore Photography
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/csrf.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Only accept POST requests.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('enquiry.php');
}

// Verify the CSRF token before doing anything else.
csrf_check_or_abort();

// Validate the submission using the shared, unit-tested validator.
$result = validate_enquiry($_POST);
$errors = $result['errors'];
$old    = $result['data'];

// If there are validation errors, return to the form with the values.
if (!empty($errors)) {
    $_SESSION['old']   = $old;
    $_SESSION['errors'] = $errors;
    set_flash('error', 'Please correct the highlighted fields and try again.');
    redirect('enquiry.php');
}

// --- Insert into the database ----------------------------------------
try {
    $stmt = db()->prepare(
        'INSERT INTO enquiries
            (name, email, phone, service_type, event_date, location, budget, message)
         VALUES
            (:name, :email, :phone, :service_type, :event_date, :location, :budget, :message)'
    );
    $stmt->execute([
        ':name'          => $old['name'],
        ':email'         => $old['email'],
        ':phone'         => $old['phone'] !== '' ? $old['phone'] : null,
        ':service_type'  => $old['service_type'] !== '' ? $old['service_type'] : null,
        ':event_date'    => $old['event_date'],
        ':location'      => $old['location'] !== '' ? $old['location'] : null,
        ':budget'        => $old['budget'] !== '' ? $old['budget'] : null,
        ':message'       => $old['message'],
    ]);

    // Record the new id so the success page can reference it.
    $enquiryId = (int) db()->lastInsertId();
    $_SESSION['last_enquiry_id'] = $enquiryId;
    unset($_SESSION['old'], $_SESSION['errors']);

    redirect('success.php');
} catch (PDOException $e) {
    // Log the real error in production; show a friendly message here.
    error_log('Enquiry insert failed: ' . $e->getMessage());
    $_SESSION['old'] = $old;
    set_flash('error', 'Sorry, something went wrong saving your enquiry. Please try again.');
    redirect('enquiry.php');
}
