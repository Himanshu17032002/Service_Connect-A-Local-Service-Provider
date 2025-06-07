<?php
session_start();
session_destroy(); // Destroy session data
header("Location: home%20.html"); // Redirect to home page
exit();
?>