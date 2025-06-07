<?php
session_start();
include "server.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone=$_POST['phone']; // Secure password hashing

    // Insert into database
    $sql = "INSERT INTO users (role, Name, email, Password,phone) VALUES (?, ?, ?, ?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $role, $name, $email, $password,$phone);

    if ($stmt->execute()) {
        echo "Registration successful! <a href='login.html'>Login here</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
