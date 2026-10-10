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

// Collect and trim the input.
$old = [
    'name'         => trim((string) ($_POST['name'] ?? '')),
    'email'        => trim((string) ($_POST['email'] ?? '')),
    'phone'        => trim((string) ($_POST['phone'] ?? '')),
    'service_type' => trim((string) ($_POST['service_type'] ?? '')),
    'event_date'   => trim((string) ($_POST['event_date'] ?? '')),
    'location'     => trim((string) ($_POST['location'] ?? '')),
    'budget'       => trim((string) ($_POST['budget'] ?? '')),
    'message'      => trim((string) ($_POST['message'] ?? '')),
];

$errors = [];

// --- Validation -------------------------------------------------------
if ($old['name'] === '') {
    $errors['name'] = 'Please enter your name.';
} elseif (mb_strlen($old['name']) > 100) {
    $errors['name'] = 'Your name must be 100 characters or fewer.';
}

if ($old['email'] === '') {
    $errors['email'] = 'Please enter your email address.';
} elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Please enter a valid email address.';
}

if ($old['phone'] !== '' && !preg_match('/^[0-9+()\-\s]{7,25}$/', $old['phone'])) {
    $errors['phone'] = 'Please enter a valid telephone number.';
}

if ($old['service_type'] === '') {
    $errors['service_type'] = 'Please choose a photography service.';
}

if ($old['event_date'] !== '') {
    // Validate the date format (Y-m-d).
    $d = DateTime::createFromFormat('Y-m-d', $old['event_date']);
    if (!$d || $d->format('Y-m-d') !== $old['event_date']) {
        $errors['event_date'] = 'Please enter a valid date.';
    } else {
        $old['event_date'] = $d->format('Y-m-d');
    }
} else {
    $old['event_date'] = null;
}

if ($old['message'] === '') {
    $errors['message'] = 'Please tell us a little about your enquiry.';
}

if (empty($_POST['consent'])) {
    $errors['consent'] = 'Please accept the privacy notice to continue.';
}

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
