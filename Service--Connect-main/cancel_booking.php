<?php
session_start();
include "server.php"; // Ensure database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['service_id'])) {
    $service_id = $_POST['service_id'];
    $seeker_id = $_SESSION['user_id']; // Get the logged-in seeker's ID

    // Remove the booking from the `bookings` table
    $sql = "DELETE FROM bookings WHERE service_id = ? AND seeker_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $service_id, $seeker_id);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        // Optional: Update the service status to "available"
        $update_sql = "UPDATE services SET status = 'available' WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $service_id);
        $update_stmt->execute();
        $update_stmt->close();

        echo "Booking canceled successfully!";
        header("refresh:2; url=booked_services.php"); // Redirect back to booked services
        exit();
    } else {
        echo "Error: Unable to cancel booking.";
    }

    $stmt->close();
}
$conn->close();
?>
