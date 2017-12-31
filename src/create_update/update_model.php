<?php
include "../common.php";
include "../connection.php";
include "../authentication/admin_user_required.php";
?>

<html>
<div class="update-header-container">
    <h1 class="update-header">Update Model</h1>
</div>

<div class="update-list-container">

    <?php

    $item_list_query = oci_parse($connection, "SELECT m.*, e.VOLUME, e.POWER, g.TYPE, g.COUNT, b.NAME AS BRAND_NAME 
                                                FROM MODEL m, ENGINE e, GEAR g, BRAND b 
                                                WHERE M.ENGINE_ID = E.ID AND m.GEAR_ID = G.ID AND m.BRAND_ID = b.ID");
    oci_execute($item_list_query);

    while ($row = oci_fetch_array($item_list_query,OCI_ASSOC+OCI_RETURN_NULLS)) {
        $id = $row['ID'];
        $table_name = "MODEL";
        $page = "update_model";
        $edit_url = "location.href='create_model.php?id=$id'";
        $del_url = "location.href='utils/delete_item.php?table=$table_name&id=$id&page=$page'";

        echo "<table class='list-item'>
                    <td class='list-item-info'>".$row['BRAND_NAME']." - ".$row['NAME']." - Fuel: ".$row['FUEL_TYPE']." - Engine: ".$row['VOLUME']." ".$row['POWER']." - Gear: ".$row['TYPE']." ".$row['COUNT']."</td>
                    <td><button onclick=$edit_url>Edit</button></td>
                    <td><button onclick=$del_url>Delete</button></td>
                </table>";
    }

    ?>
</div>
</html>