<?php
include "../common.php";
include "../connection.php";
include "../authentication/branch_user_required.php";
?>

<html>
<div class="update-header-container">
    <h1 class="update-header">Update Vehicle</h1>
</div>

<div class="update-list-container">

    <?php

    $item_list_query = oci_parse(
            $connection,
            "SELECT V.ID AS VEHICLE_ID, PLATE, VEHICLE_TYPE FROM VEHICLE V JOIN MODEL ON V.MODEL_ID = MODEL.ID"
    );
    oci_execute($item_list_query);

    while ($row = oci_fetch_array($item_list_query, OCI_ASSOC + OCI_RETURN_NULLS)) {
        if ($row['VEHICLE_TYPE'] == 'CAR') {
            echo "<li class='list-item'><a class='update-link' href='create_car.php?id=".$row['VEHICLE_ID']."'>
                    "."Vehicle Plate: ".$row['PLATE']."
                  </a></li>";
        }
        elseif ($row['VEHICLE_TYPE'] == 'BUS') {
            echo "<li class='list-item'><a class='update-link' href='create_bus.php?id=".$row['VEHICLE_ID']."'>
                    "."Vehicle Plate: ".$row['PLATE']."
                  </a></li>";
        }
        elseif ($row['VEHICLE_TYPE'] == 'TRUCK') {
            echo "<li class='list-item'><a class='update-link' href='create_truck.php?id=".$row['VEHICLE_ID']."'>
                    "."Vehicle Plate: ".$row['PLATE']."
                  </a></li>";
        }
        elseif ($row['VEHICLE_TYPE'] == 'MOTORCYCLE') {
            echo "<li class='list-item'><a class='update-link' href='create_motorcycle.php?id=".$row['VEHICLE_ID']."'>
                    "."Vehicle Plate: ".$row['PLATE']."
                  </a></li>";
        }
    }

    ?>
</div>
</html>