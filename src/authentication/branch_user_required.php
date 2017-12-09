<?php
include "../common.php";

// Start the session
session_start();

// Redirect to the login page
if (!isset($_SESSION['user_type'])) {
    header("Location: /login.php");
    exit();
}
elseif ($_SESSION['user_type'] != $USER_TYPE_ADMIN or $_SESSION['user_type'] != $USER_TYPE_ADMIN) {
    // Display an error page
    header('HTTP/1.0 403 Forbidden');
    echo "
            <br><br><center><h1 style='color: red'>
                You must be a branch user or an admin user to be able to see this page!
            </h1></center>";
    exit();
}
?>