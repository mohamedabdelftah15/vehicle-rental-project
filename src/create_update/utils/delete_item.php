<?php
include "../../common.php";
include "../../authentication/branch_user_required.php";
include "../../authentication/admin_user_required.php";
?>

<?php

$page = $_GET['page'];
$table_name = $_GET['table'];
$id = $_GET['id'];

$item_delete_sql = oci_parse($connection, "
                BEGIN
                    DELETE_$table_name(:id);
                    COMMIT;
                END;"
);

oci_bind_by_name($item_delete_sql, ":id", $id);
oci_execute($item_delete_sql);

header("Location:$page.php");
?>