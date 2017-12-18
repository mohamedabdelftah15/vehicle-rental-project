<?php
include "../common.php";
include "../connection.php";
include "../authentication/branch_user_required.php";
?>

<html>
<div class="update-header-container">
    <h1 class="update-header">Update Bus</h1>
</div>

<div class="update-list-container">

    <?php

    $item_list_query = oci_parse($connection, "SELECT * FROM BUS");
    oci_execute($item_list_query);

    while ($row = oci_fetch_array($item_list_query, OCI_ASSOC + OCI_RETURN_NULLS)) {
        echo "<li class='list-item'>
                <a class='update-link' href='create_bus.php?id=".$row['VEHICLE_ID']."'>
                    "."Vehicle: ".$row['VEHICLE_ID']." - Passenger Capacity: ".$row['PASSENGER_CAPACITY']."
                </a>
              </li>";
    }

    ?>
</div>
</html>