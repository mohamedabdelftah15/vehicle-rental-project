<?php
include __DIR__ . '/connection.php';
include "authentication/login_required.php";

$item_save_sql = oci_parse(
    $connection,
    "
            BEGIN
                RENT_VEHICLE(:user_id, :vehicle_id, :payment_type, to_date(:start_date, 'YYYY-MM-DD'), to_date(:due_date, 'YYYY-MM-DD'), :message);
                COMMIT;
            END;"
);

# Create a buffer for returned message from the stored procedure
$message = str_repeat('-', 100);



# Add arguments
session_start();
oci_bind_by_name($item_save_sql, ":user_id", $_SESSION['user_id']);
oci_bind_by_name($item_save_sql, ":vehicle_id", $_POST['vehicle_id']);
oci_bind_by_name($item_save_sql, ":payment_type", $_POST['payment_type']);
oci_bind_by_name($item_save_sql, ":start_date", $_POST['start_date']);
oci_bind_by_name($item_save_sql, ":due_date", $_POST['due_date']);
oci_bind_by_name($item_save_sql, ":message", $message);

oci_execute($item_save_sql);

header("Location: vehicle_detail.php?Id=" . $_POST['vehicle_id'] . "&message=$message");
exit();
?>