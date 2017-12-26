<?php
include "../common.php";
include "../authentication/admin_user_required.php";
?>

<html>
<div class="update-header-container">
    <h1 class="update-header">Update Brand</h1>
</div>

<div class="update-list-container">

    <?php

    $item_list_query = oci_parse($connection, "SELECT * FROM BRAND");
    oci_execute($item_list_query);

    while ($row = oci_fetch_array($item_list_query,OCI_ASSOC+OCI_RETURN_NULLS)) {
        echo "<li class='list-item'><a class='update-link' href='create_brand.php?id=".$row['ID']."'>".$row['NAME']."</a></li>";
    }

    ?>
</div>
</html>
