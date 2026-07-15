<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];
    $user_id = $_SESSION['user']['id'] ?? null;

    // Check if it's the user's booking and can be cancelled
    $stmt = $pdo->prepare("
        SELECT * FROM bookings
        WHERE booking_id = ? AND user_id = ?
        AND status IN ('pending', 'approved')
        AND booking_date > NOW()
    ");
    $stmt->execute([$booking_id, $user_id]);
    $booking = $stmt->fetch();

    if ($booking) {
        $stmt = $pdo->prepare("UPDATE bookings SET status = 'cancelled' WHERE booking_id = ?");
        $stmt ->execute([$booking_id]);
        $_SESSION['flash'] = "Booking cancelled successfully.";
    } else {
        $_SESSION['flash'] = "Unable to cancel booking.";
    }
}

header("location: user_booking.php");
exit;
