<?php
include "../common.php";
include "../connection.php";
include "../authentication/branch_user_required.php";
?>

<html>
<div class="update-header-container">
    <h1 class="update-header">Update Car</h1>
</div>

<div class="update-list-container">

    <?php

    $item_list_query = oci_parse($connection, "SELECT * FROM CAR");
    oci_execute($item_list_query);

    while ($row = oci_fetch_array($item_list_query, OCI_ASSOC + OCI_RETURN_NULLS)) {
        echo "<li class='list-item'>
                <a class='update-link' href='create_car.php?id=".$row['VEHICLE_ID']."'>
                    "."Vehicle: ".$row['VEHICLE_ID']."
                </a>
              </li>";
    }

    ?>
</div>
</html>