<?php
include "../common.php";

// Start the session
session_start();

// Redirect to the login page
if (!isset($_SESSION['user_type'])) {
    header("Location: /authentication/login.php");
    exit();
}
?>