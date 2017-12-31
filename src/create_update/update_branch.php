<?php
include "../common.php";
include "../authentication/admin_user_required.php";
?>

<html>
<div class="update-header-container">
    <h1 class="update-header">Update Branch</h1>
</div>

<div class="update-list-container">

    <?php

    $item_list_query = oci_parse($connection, "SELECT branch.*, county.NAME as COUNTY_NAME, city.NAME as CITY_NAME, country.NAME as COUNTRY_NAME 
                                                        FROM BRANCH branch, COUNTY county, CITY city, COUNTRY country
                                                        WHERE branch.COUNTY_ID = county.ID AND county.CITY_ID = city.ID AND city.COUNTRY_ID = country.ID");
    oci_execute($item_list_query);

    while ($row = oci_fetch_array($item_list_query,OCI_ASSOC+OCI_RETURN_NULLS)) {
        $id = $row['ID'];
        $table_name = "BRANCH";
        $page = "update_branch";
        $edit_url = "location.href='create_branch.php?id=$id'";
        $del_url = "location.href='utils/delete_item.php?table=$table_name&id=$id&page=$page'";

        echo "<table class='list-item'>
                    <td class='list-item-info'>".$row['COUNTRY_NAME']." - ".$row['CITY_NAME']." - ".$row['COUNTY_NAME']." - ".$row['NAME']."</td>
                    <td><button onclick=$edit_url>Edit</button></td>
                    <td><button onclick=$del_url>Delete</button></td>
                </table>";
    }

    ?>
</div>
</html>
