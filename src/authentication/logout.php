<?php

# Clean the session
session_start();
session_destroy();

# Redirect to the homepage
header("Location: /index.php");
exit();