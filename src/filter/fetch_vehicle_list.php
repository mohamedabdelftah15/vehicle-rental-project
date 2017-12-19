<?php

if($model){
    $vehicle_query = oci_parse($connection, "SELECT v.*, bv.PRICE
                FROM VEHICLE v, MODEL m, BRANCH_RLTD_VEHICLE bv
                WHERE v.MODEL_ID = m.ID AND m.NAME = '$model' AND m.VEHICLE_TYPE = '$type' AND bv.VEHICLE_ID = v.ID AND bv.IS_AVAILABLE = 1");
}
else if($branch){
    $vehicle_query = oci_parse($connection, "SELECT v.*, bv.PRICE
                FROM VEHICLE v, BRANCH_RLTD_VEHICLE bv
                WHERE v.ID = bv.VEHICLE_ID AND bv.BRANCH_ID = $branch AND bv.IS_AVAILABLE = 1");
}
else{
    $vehicle_query = oci_parse($connection, "SELECT v.*, bv.PRICE
                FROM VEHICLE v, BRANCH_RLTD_VEHICLE bv
                WHERE v.ID = bv.VEHICLE_ID AND bv.IS_AVAILABLE = 1");
}
oci_execute($vehicle_query);

while ($row = oci_fetch_array($vehicle_query,OCI_ASSOC+OCI_RETURN_NULLS)) {
    $vehicle_id = $row['ID'];
    $vehicle_photo = $row['IMAGE_PATH'];

    echo "<img src='$vehicle_photo' style='width: 70px; height: 50px;'>";
    echo "<a href='vehicle_detail.php?Id=$vehicle_id'>&emsp;&emsp;Plate: ".$row['PLATE']." | Kilometer:".$row['KILOMETER']."km. | Model Year:".$row['YEAR']." | Amount:".$row['PRICE']."TL </a><br><br>";
}

?>