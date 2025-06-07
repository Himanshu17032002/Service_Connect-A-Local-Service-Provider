<?php
session_start();
include "server.php";


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'service-seeker') {
    header("Location: login.html");
    exit();
}

$services = [];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pincode = $_POST['pincode'];
    $service_type = $_POST['service_type'];

    $sql = "SELECT id, name, price FROM services WHERE pincode = ? AND service_type = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $pincode, $service_type);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Seeker Dashboard</title>
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
</head>
<body>
    <header>
        <h1>Welcome, <?php echo $_SESSION['name']; ?>!</h1>
        </header>
       
   

    <nav class="navbar">
        <a href="seeker.php">Profile</a>
        <a href="seekerprofile.php">Available Services</a>
        <a href="booked_services.php">Booked Services</a>
        <div class="logout-btn">
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <main class="main-content">
        <h2>Find Services in Your Area</h2>
        <form method="POST">
            <label>Pincode:</label>
            <input type="text" name="pincode" required><br>

            <label>Service Type:</label>
            <select name="service_type" required>
                <option value="Maid">Maid</option>
                <option value="Plumbing">Plumbing</option>
                <option value="Security">Security</option>
                <option value="Electrician">Electrician</option>
                <option value="Carpenter">Carpenter</option>
            </select><br>

            <button type="submit">Search</button>
        </form>

        <h2>Available Services</h2>
        <?php if (!empty($services)): ?>
            <table border="1">
                <tr>
                    <th>Provider Name</th>
                    <th>Price (₹)</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($services as $service): ?>
                    <tr>
                        <td><?php echo $service['name']; ?></td>
                        <td><?php echo $service['price']; ?></td>
                        <td>
                            <form action="book_service.php" method="POST">
                                <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                                <button type="submit">Book</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No services found for this pincode.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2024 Service Connect. All rights reserved.</p>
    </footer>
</body>
</html>
