<?php
include "../common.php";
include "../connection.php";

$model =  null;
$type = null;
$brand = null;

if($_GET['Model']){
    $model =  $_GET['Model'];
    $type = $_GET['Type'];
    $brand = $_GET['Brand'];
}
?>

<h3>VEHICLES</h3>

<div class="left_menu">
<?php

$vehicle_type_list = array (0 => "CAR", 1 => "BUS", 2 => "TRUCK", 3 => "MOTORCYCLE");

$j = 0;
$j = 0;
for($i = 0; $i < count($vehicle_type_list); $i++){

    $j = $i+1;
    $k = 0;
    $vehicle_type = $vehicle_type_list[$i];
    echo "<a href='javascript:setView($j,1);'>$vehicle_type_list[$i]</a><br>";

    $brand_query = oci_parse($connection, "SELECT DISTINCT b.NAME, b.ID FROM Model m, Brand b 
    WHERE m.VEHICLE_TYPE = '$vehicle_type' AND m.BRAND_ID = b.ID");
    oci_execute($brand_query);

    while ($row = oci_fetch_array($brand_query,OCI_ASSOC+OCI_RETURN_NULLS)) {

        $k++;
        $brand_id = $row['ID'];
        if($vehicle_type == $type){
            echo "<a href='javascript:setView($j$k,1);' class='$j' style='display: block'>&emsp;".$row['NAME']."</a>";
        }
        else{
            echo "<a href='javascript:setView($j$k,1);' class='$j' style='display: none'>&emsp;".$row['NAME']."</a>";
        }

        $model_query = oci_parse($connection, "SELECT DISTINCT m.NAME FROM Model m 
        WHERE m.VEHICLE_TYPE = '$vehicle_type' AND m.BRAND_ID = $brand_id");
        oci_execute($model_query);

        while ($row = oci_fetch_array($model_query,OCI_ASSOC+OCI_RETURN_NULLS)) {
            $model_name = $row['NAME'];

            if($vehicle_type == $type && $brand_id == $brand){
                echo "<a href='?Brand=$brand_id&Model=$model_name&Type=$vehicle_type' class='$j$k' style='display: block'>&emsp;&emsp;".$row['NAME']."</a>";
            }
            else{
                echo "<a href='?Brand=$brand_id&Model=$model_name&Type=$vehicle_type' class='$j$k' style='display: none'>&emsp;&emsp;".$row['NAME']."</a>";
            }
        }
    }
}

?>
</div>

<div class="vehicle_list">

    <?php
        include "fetch_vehicle_list.php";
    ?>

</div>