<?php
session_start();
include "server.php"; // Database connection

// Ensure only service providers can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'service-provider') {
    header("Location: login.html");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone']; // Capture phone number
    $pincode = $_POST['pincode'];
    $service_type = $_POST['service_type'];
    $price = $_POST['price'];
    $user_id = $_SESSION['user_id']; // Logged-in user ID

    $sql = "INSERT INTO services (user_id, user_role, name, phone, pincode, service_type, price, status) 
            VALUES (?, 'provider', ?, ?, ?, ?, ?, 'available')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssd", $user_id, $name, $phone, $pincode, $service_type, $price);

    if ($stmt->execute()) {
        echo "<script>alert('Service added successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Provider Dashboard</title>
    <link rel="stylesheet" href="product.css">
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
    </style> <!-- Ensure this CSS file exists -->
</head>
<body>
    <h2>Welcome, <?php echo $_SESSION['name']; ?>!</h2>
    <div class="navbar">
    <a href="#service@"><i class="fa fa-home"></i>Profile</a>
    <div class="nav-links">
    <a href="#service@"><i class="fa fa-home"></i>My Services</a>
    <a href="provider_appointments.php"><i class="fa fa-home"></i>My Appointments</a>
    <a href="logout.php" class="logout-btn"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>
</div>
    <h2>Enter Your Service Details</h2>
    <form method="POST">
    <div>
        <label>Name:</label>
        <input type="text" name="name" required>
    </div>

    <div>
        <label>Phone:</label>
        <input type="text" name="phone" required>
    </div>

    <div>
        <label>Pincode:</label>
        <input type="text" name="pincode" required>
    </div>

    <div>
        <label>Service Type:</label>
        <select name="service_type" required>
            <option value="Maid">Maid</option>
            <option value="Plumbing">Plumbing</option>
            <option value="Security">Security</option>
            <option value="Electrician">Electrician</option>
            <option value="Carpenter">Carpenter</option>
        </select>
    </div>

    <div>
        <label>Price (₹):</label>
        <input type="number" name="price" required>
    </div>

    <button type="submit">Submit</button>
</form>


    <h2 id ="service@">Your Services</h2>
    <table border="1">
        <tr>
            <th>Name</th>
           
            <th>Type</th>
            <th>Price</th>
            <th>Action</th>
        </tr>

        <?php
        include "server.php"; // Reconnect to fetch services
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT id, name, service_type, price FROM services WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                
                <td><?php echo $row['service_type']; ?></td>
                <td>₹<?php echo $row['price']; ?></td>
                <td>
                    <form method="POST" action="delete_service.php">
                        <input type="hidden" name="service_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this service?')">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>

    </table>
</body>
</html>
