<?php
// Set page title
$pageTitle = 'Processing Booking';

// Include configuration and required functions
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/includes/station-functions.php';
require_once dirname(__DIR__) . '/includes/booking-functions.php';

// Require login
requireLogin();

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    setFlashMessage('error', 'Invalid request method.');
    redirect('bookings.php');
}

// Get form data
$stationId = isset($_POST['station_id']) ? (int)$_POST['station_id'] : null;
$chargingPointId = isset($_POST['charging_point_id']) ? (int)$_POST['charging_point_id'] : null;
$date = isset($_POST['date']) ? $_POST['date'] : null;
$startTime = isset($_POST['start_time']) ? $_POST['start_time'] : null;
$endTime = isset($_POST['end_time']) ? $_POST['end_time'] : null;
$duration = isset($_POST['duration']) ? (int)$_POST['duration'] : 60;

// Validate required fields
if (!$stationId || !$chargingPointId || !$date || !$startTime || !$endTime) {
    setFlashMessage('error', 'Please fill in all required fields.');
    redirect('bookings.php');
}

// Create booking datetime
$bookingDatetime = $date . ' ' . $startTime;

// Create booking
$bookingId = createBooking($_SESSION['user_id'], $chargingPointId, $bookingDatetime);

if ($bookingId) {
    setFlashMessage('success', 'Booking created successfully!');
    redirect('dashboard.php');
} else {
    setFlashMessage('error', 'Failed to create booking. Please try again.');
    redirect('bookings.php?station_id=' . $stationId);
}