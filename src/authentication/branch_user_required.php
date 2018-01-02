<?php

// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect to the login page
if (!isset($_SESSION['user_type'])) {
    header("Location: /authentication/login.php");
    exit();
} elseif ($_SESSION['user_type'] != $USER_TYPE_BRANCH and $_SESSION['user_type'] != $USER_TYPE_ADMIN) {
    // Display an error page
    header('HTTP/1.0 403 Forbidden');
    echo "
            <br><br><center><h1 style='color: red'>
                You must be a branch user or an admin user to be able to see this page!
            </h1></center>";
    exit();
} elseif ($_SESSION['user_type'] == $USER_TYPE_BRANCH and isset($_GET['id'])) {
    $id = $_GET['id'];

    # Fetch the USER
    $user_query = oci_parse(
        $connection,
        "SELECT USER_ID FROM BRANCH_DATA WHERE VEHICLE_ID = $id"
    );
    oci_execute($user_query);

    $user_id = oci_fetch_array($user_query, OCI_ASSOC + OCI_RETURN_NULLS);

    # Check whether the user is owner of the branch or not
    if ($_SESSION['user_id'] != $user_id['USER_ID']) {
        // Display an error page
        header('HTTP/1.0 403 Forbidden');
        echo "
            <br><br><center><h1 style='color: red'>
                You do not have permission to edit this vehicle!
            </h1></center>";
        exit();
    }
}
?>