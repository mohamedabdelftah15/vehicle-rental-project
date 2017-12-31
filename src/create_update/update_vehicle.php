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
        $id = $row['VEHICLE_ID'];
        $table_name = "VEHICLE";
        $page = "update_vehicle";
        if ($row['VEHICLE_TYPE'] == 'CAR') {
            $edit_url = "location.href='create_car.php?id=$id'";
        }
        else if ($row['VEHICLE_TYPE'] == 'BUS') {
            $edit_url = "location.href='create_bus.php?id=$id'";
        }
        else if ($row['VEHICLE_TYPE'] == 'TRUCK') {
            $edit_url = "location.href='create_truck.php?id=$id'";
        }
        else if ($row['VEHICLE_TYPE'] == 'MOTORCYCLE') {
            $edit_url = "location.href='create_motorcycle.php?id=$id'";
        }
        $del_url = "location.href='utils/delete_item.php?table=$table_name&id=$id&page=$page'";

        echo "<table class='list-item'>
                    <td class='list-item-info'>Vehicle Plate: ".$row['PLATE']."</td>
                    <td><button onclick=$edit_url>Edit</button></td>
                    <td><button onclick=$del_url>Delete</button></td>
                </table>";
    }

    ?>
</div>
</html>