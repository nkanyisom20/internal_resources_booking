<?php 
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user']['employee_id'] ?? null;
    $resource_id = $_POST['resource_id'] ?? null;
    $booking_date = $_POST['booking_date'] ?? '';
    $start_time = $_POST['start_time'] ?? '';
    $end_time = $_POST['end_time'] ?? '';
    $purpose = $_POST['purpose'] ?? '';

    if (!$user_id || !$resource_id || !$booking_date || !$start_time || !$end_time || !$purpose) {
        die("Missing required fields.");
    }

    // Combine date and time
    $start_datetime = "$booking_date $start_time:00";
    $end_datetime = "$booking_date $end_time:00";

    if (strtotime($end_datetime) <= strtotime($start_datetime)) {
        die("End time must be after start time.");
    }

    // Check for conflicting bookings
    $conflict = $pdo->prepare("
        SELECT COUNT(*) FROM bookings
        WHERE resource_id = ?
          AND booking_date = ?
          AND status != 'rejected'
          AND (
              (? BETWEEN start_time AND end_time) OR
              (? BETWEEN start_time AND end_time) OR
              (start_time <= ? AND end_time >= ?)
          )
    ");
    $conflict->execute([
        $resource_id,
        $booking_date,
        $start_datetime,
        $end_datetime,
        $start_datetime,
        $end_datetime,
    ]);

    if ($conflict->fetchColumn() > 0) {
        $_SESSION['error'] = "This resource is already booked during the requested time. Please choose another slot or resource, or adjust your times.";
        header('Location: ../../resources.php');
        exit;
        
    }

    // Insert booking
    $stmt = $pdo->prepare("
        INSERT INTO bookings (user_id, resource_id, booking_date, start_time, end_time, purpose, status)
        VALUES (?, ?, ?, ?, ?, ?, 'pending')
    ");
    $stmt->execute([
        $user_id,
        $resource_id,
        $booking_date,
        $start_datetime,
        $end_datetime,
        $purpose
    ]);

    // Redirect or success message
    header("location: ../../user_booking.php?booking=success");
    exit;
} else {
    die("Invalid request method.");
}
