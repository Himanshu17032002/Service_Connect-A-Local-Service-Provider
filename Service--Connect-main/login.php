<?php
session_start();
include "server.php"; // Ensure database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Get the selected role from the login form

    // Fetch the user only if the email and role match
    $sql = "SELECT id, name, role, password FROM users WHERE email=? AND role=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $name, $db_role, $hashed_password);

    if ($stmt->fetch()) {
        if (password_verify($password, $hashed_password)) {
            // Store user details in session
            $_SESSION['user_id'] = $id;
            $_SESSION['name'] = $name;
            $_SESSION['role'] = $db_role;

            // Redirect based on role
            if ($db_role === 'service-provider') {
                header("Location: product.php");
            } else if ($db_role === 'service-seeker') {
                header("Location: seekerprofile.php");
            } else {
                echo "Invalid role assigned. Contact support.";
            }
            exit();
        } else {
            echo "Incorrect password! <a href='login.html'>Try again</a>";
        }
    } else {
        echo "No account found with this email and role! <a href='register.html'>Register here</a>";
    }

    $stmt->close();
}
$conn->close();
?>
