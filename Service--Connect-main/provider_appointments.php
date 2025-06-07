<?php
session_start();
include "server.php"; 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'service-provider') {
    header("Location: login.html");
    exit();
}

$provider_id = $_SESSION['user_id']; // Get provider ID from session

// Fetch bookings where this provider's service was booked
$sql = "SELECT b.id, u.Name AS seeker_name, u.phone,s.service_type, b.booking_date, b.status
        FROM bookings b
        JOIN users u ON b.seeker_id = u.id
        JOIN services s ON b.service_id = s.id
        WHERE b.provider_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $provider_id);
$stmt->execute();
$result = $stmt->get_result();

// Debugging: Show how many rows were fetched


if ($result->num_rows === 0) {
    echo "<p>No bookings found.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Provider Appointments</title>
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
    <a href="product.php"><i class="fa fa-home"></i> Home</a>
    <div class="nav-links">
       
        <a href="logout.php" class="logout-btn"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>
</div>
    <h2>My Appointments</h2>
    <table border="1">
        <tr>
            <th>Seeker Name</th>
            <th>Service Type</th>
            <th>Customer Mobile No.</th>
            <th>Booking Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['seeker_name']); ?></td>
                <td><?php echo htmlspecialchars($row['service_type']); ?></td>
                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td>
                    <?php if ($row['status'] === 'Booked') { ?>
                        <form method="POST" action="providercancel_booking.php">
                            <input type="hidden" name="booking_id" value="<?php echo $row['id']; ?>">
                            <button type="submit">Cancel</button>
                        </form>
                    <?php } else { ?>
                        Cancelled
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
