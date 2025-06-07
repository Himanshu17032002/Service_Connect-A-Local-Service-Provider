<?php
session_start();
include "server.php";

// Only seekers can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'service-seeker') {
    header("Location: seekerprofile.php");
    exit();
}

$seeker_id = $_SESSION['user_id'];
$booked_services = [];

// Updated query to get provider name and phone
$sql = "SELECT s.id, s.name AS provider_name, s.service_type, s.pincode, s.price, b.booking_date, u.phone AS provider_phone, b.status
        FROM bookings b
        JOIN services s ON b.service_id = s.id
        JOIN users u ON s.user_id = u.id
        WHERE b.seeker_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $seeker_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $booked_services[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Booked Services</title>
    <link rel="stylesheet" href="seekerstyle.css">
    <style>
    body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #007bff;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            font-size: 18px;
        }
        .navbar a:hover {
            background-color: #0056b3;
            border-radius: 5px;
        }
        .nav-links {
            display: flex;
            gap: 20px;
        }
        .logout-btn {
            background-color: red;
            padding: 10px 15px;
            border-radius: 5px;
        }
        .logout-btn:hover {
            background-color: darkred;
        }
    </style>
    <link rel="stylesheet" href="product.css">
</head>
<body>
    <div class="navbar">
        <a href="seekerprofile.php">Home</a>
        <h1>Your Booked Services</h1>
        <div class="nav-links">
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <main>
        <h2>Services You Have Booked</h2>
        <?php if (!empty($booked_services)): ?>
        <table>
            <tr>
                <th>Provider Name</th>
                <th>Service Type</th>
                <th>Price (₹)</th>
                <th>Booking Date</th>
                <th>Provider Phone</th>
                <th>Satus</th>
                <th>Action</th>
            </tr>
            <?php foreach ($booked_services as $service): ?>
                <tr>
                    <td><?php echo htmlspecialchars($service['provider_name']); ?></td>
                    <td><?php echo htmlspecialchars($service['service_type']); ?></td>
                    <td><?php echo htmlspecialchars($service['price']); ?></td>
                    <td><?php echo htmlspecialchars($service['booking_date']); ?></td>
                    <td><?php echo htmlspecialchars($service['provider_phone']); ?></td>
                    <td><?php echo htmlspecialchars($service['status']); ?></td>
                    <td>
                        <form action="cancel_booking.php" method="POST">
                            <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                            <button id="cancel" type="submit" onclick="return confirm('Are you sure you want to cancel this booking?');">Cancel</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
            <p>You have not booked any services yet.</p>
        <?php endif; ?>
    </main>
</body>
</html>
