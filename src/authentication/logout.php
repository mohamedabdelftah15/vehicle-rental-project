<?php

# Clean the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
session_destroy();

# Redirect to the homepage
header("Location: /index.php");
exit();