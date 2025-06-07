<?php
session_start();
include "server.php"; 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'service-provider') {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];

    $sql = "UPDATE bookings SET status = 'Cancelled' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);

    if ($stmt->execute()) {
        echo "Booking canceled successfully!";
    } else {
        echo "Error: Unable to cancel booking.";
    }

    $stmt->close();
    $conn->close();
    header("Location: provider_appointments.php"); // Redirect back to appointments page
    exit();
}
?>
