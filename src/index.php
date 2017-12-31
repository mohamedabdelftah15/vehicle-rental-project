<?php
include "common.php";
?>

<html>

<div class="home-page-vehicle-container">
<?php

$vehicle_query = oci_parse($connection, "SELECT v.*, bv.PRICE
                FROM VEHICLE v, BRANCH_RLTD_VEHICLE bv
                WHERE v.ID = bv.VEHICLE_ID");
oci_execute($vehicle_query);

$count = 1;
$top = 0;

while ($row = oci_fetch_array($vehicle_query,OCI_ASSOC+OCI_RETURN_NULLS)) {
    $vehicle_id = $row['ID'];
    $image_path = $row['IMAGE_PATH'];
    $vehicle_photo = "../$image_path";

    if($count == 5){
        $count = 1;
        $top = $top + 150;
    }

    $left = 300 * $count;
    $left_position = "{$left}px";
    $top_position = "{$top}px";
    $detail_url = "location.href='vehicle_detail.php?Id=$vehicle_id'";

    echo "<div style='position:absolute;margin-left: $left_position; margin-top: $top_position;'>
            <img src='$vehicle_photo' style='width: 150px; height: 90px; position: fixed; cursor: pointer' onclick=$detail_url>
            <div class='triangle' style='position: fixed'></div>
            <p class='vehicle_prices' style='position: fixed'>".$row['PRICE']."₺</p>
          </div>";
    $count++;
}

?>
</div>

<?php
include "side_navigation.php";
?>

</html>