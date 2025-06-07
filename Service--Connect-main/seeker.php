<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seeker') {
    header("Location:seekerprofile.php");
    exit(); // ✅ Stops execution after redirection
}
?>
