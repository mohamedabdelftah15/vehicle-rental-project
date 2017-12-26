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
else if($car_filter){
    $vehicle_query = oci_parse($connection, "SELECT v.*, bv.PRICE
                FROM VEHICLE v, CAR car, MODEL m, BRANCH_RLTD_VEHICLE bv
                WHERE v.ID = bv.VEHICLE_ID AND v.ID = car.VEHICLE_ID AND v.MODEL_ID = m.ID AND bv.IS_AVAILABLE = 1 
                AND bv.PRICE <= $max_amount AND bv.PRICE >= $min_amount AND v.YEAR <= $max_year AND v.YEAR >= $min_year AND 
                v.KILOMETER <= $max_km AND v.KILOMETER >= $min_km AND m.FUEL_TYPE LIKE '$fuel_type' AND car.SEGMENT LIKE '$segment' AND 
                $frame_type_condition AND $equipment_package_condition AND $gear_condition
                ");
}
else if($bus_filter){
    $vehicle_query = oci_parse($connection, "SELECT v.*, bv.PRICE
                FROM VEHICLE v, BUS bus, MODEL m, BRANCH_RLTD_VEHICLE bv
                WHERE v.ID = bv.VEHICLE_ID AND v.ID = bus.VEHICLE_ID AND v.MODEL_ID = m.ID AND bv.IS_AVAILABLE = 1 
                AND bv.PRICE <= $max_amount AND bv.PRICE >= $min_amount AND v.YEAR <= $max_year AND v.YEAR >= $min_year AND 
                v.KILOMETER <= $max_km AND v.KILOMETER >= $min_km AND bus.PASSENGER_CAPACITY <= $max_capacity AND 
                bus.PASSENGER_CAPACITY >= $min_capacity AND m.FUEL_TYPE LIKE '$fuel_type' AND $gear_condition
                ");
}
else if($motorcycle_filter){
    $vehicle_query = oci_parse($connection, "SELECT v.*, bv.PRICE
                FROM VEHICLE v, MOTORCYCLE motocycle, MODEL m, BRANCH_RLTD_VEHICLE bv
                WHERE v.ID = bv.VEHICLE_ID AND v.ID = motocycle.VEHICLE_ID AND v.MODEL_ID = m.ID AND bv.IS_AVAILABLE = 1 
                AND bv.PRICE <= $max_amount AND bv.PRICE >= $min_amount AND v.YEAR <= $max_year AND v.YEAR >= $min_year AND 
                v.KILOMETER <= $max_km AND v.KILOMETER >= $min_km AND motocycle.MOTORCYCLE_TYPE LIKE '$motorcycle_type'AND 
                m.FUEL_TYPE LIKE '$fuel_type' AND $gear_condition
                ");
}
else if($truck_filter){
    $vehicle_query = oci_parse($connection, "SELECT v.*, bv.PRICE
                FROM VEHICLE v, TRUCK truck, MODEL m, BRANCH_RLTD_VEHICLE bv
                WHERE v.ID = bv.VEHICLE_ID AND v.ID = truck.VEHICLE_ID AND v.MODEL_ID = m.ID AND bv.IS_AVAILABLE = 1 
                AND bv.PRICE <= $max_amount AND bv.PRICE >= $min_amount AND v.YEAR <= $max_year AND v.YEAR >= $min_year AND 
                v.KILOMETER <= $max_km AND v.KILOMETER >= $min_km AND truck.TRAILER_TYPE LIKE '$trailer_type' AND 
                truck.BALE_CAPACITY <= $max_bale_capacity AND truck.BALE_CAPACITY >= $min_bale_capacity AND 
                truck.TRAILER_VOLUME <= $max_trailer_volume AND truck.TRAILER_VOLUME >= $min_trailer_volume AND 
                m.FUEL_TYPE LIKE '$fuel_type' AND $gear_condition
                ");
}
else{
    $vehicle_query = oci_parse($connection, "SELECT v.*, bv.PRICE
                FROM VEHICLE v, BRANCH_RLTD_VEHICLE bv
                WHERE v.ID = bv.VEHICLE_ID AND bv.IS_AVAILABLE = 1");
}
oci_execute($vehicle_query);

while ($row = oci_fetch_array($vehicle_query,OCI_ASSOC+OCI_RETURN_NULLS)) {
    $vehicle_id = $row['ID'];
    $image_path = $row['IMAGE_PATH'];
    $vehicle_photo = "../$image_path";

    echo "<img src='$vehicle_photo' style='width: 70px; height: 50px;'>";
    echo "<a href='../vehicle_detail.php?Id=$vehicle_id'>&emsp;&emsp;Plate: ".$row['PLATE']." | Kilometer:".$row['KILOMETER']."km. | Model Year:".$row['YEAR']." | Amount:".$row['PRICE']."TL</a><br><br>";
}

?>