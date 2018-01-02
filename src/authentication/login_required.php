<?php

// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect to the login page
if (!isset($_SESSION['user_type'])) {
    header("Location: /authentication/login.php");
    exit();
}
?>