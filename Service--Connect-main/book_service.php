<?php
session_start();
include "server.php"; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['service_id'])) {
    $service_id = $_POST['service_id'];
    $seeker_id = $_SESSION['user_id'];

    if (!$seeker_id) {
        die("Error: User is not logged in.");
    }

    // Step 1: Get the provider_id from the services table
    $provider_sql = "SELECT user_id FROM services WHERE id = ?";
    $provider_stmt = $conn->prepare($provider_sql);
    $provider_stmt->bind_param("i", $service_id);
    $provider_stmt->execute();
    $provider_result = $provider_stmt->get_result();

    if ($provider_result->num_rows === 0) {
        die("Error: Service not found.");
    }

    $provider_row = $provider_result->fetch_assoc();
    $provider_id = $provider_row['user_id'];
    $provider_stmt->close();

    // Step 2: Insert booking with provider_id
    $sql = "INSERT INTO bookings (service_id, seeker_id, provider_id, booking_date) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error preparing query: " . $conn->error);
    }

    $stmt->bind_param("iii", $service_id, $seeker_id, $provider_id);

    if ($stmt->execute()) {
        echo "Service booked successfully!";
        header("refresh:2; url=booked_services.php");
        exit();
    } else {
        die("Error booking service: " . $stmt->error);
    }

    $stmt->close();
}

$conn->close();
?>
