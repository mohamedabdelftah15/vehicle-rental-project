<?php

if($model){
    $vehicle_query = oci_parse($connection, "SELECT v.*, bv.PRICE
                FROM VEHICLE v, MODEL m, BRANCH_RLTD_VEHICLE bv
                WHERE v.MODEL_ID = m.ID AND m.NAME = '$model' AND m.VEHICLE_TYPE = '$type' AND bv.VEHICLE_ID = v.ID");
}
else if($branch){
    $vehicle_query = oci_parse($connection, "SELECT v.*, bv.PRICE
                FROM VEHICLE v, BRANCH_RLTD_VEHICLE bv
                WHERE v.ID = bv.VEHICLE_ID AND bv.BRANCH_ID = $branch");
}
else if($car_filter){
    $vehicle_query = oci_parse($connection, "SELECT v.*, bv.PRICE
                FROM VEHICLE v, CAR car, MODEL m, BRANCH_RLTD_VEHICLE bv
                WHERE v.ID = bv.VEHICLE_ID AND v.ID = car.VEHICLE_ID AND v.MODEL_ID = m.ID 
                AND bv.PRICE <= $max_amount AND bv.PRICE >= $min_amount AND v.YEAR <= $max_year AND v.YEAR >= $min_year AND 
                v.KILOMETER <= $max_km AND v.KILOMETER >= $min_km AND m.FUEL_TYPE LIKE '$fuel_type' AND car.SEGMENT LIKE '$segment' AND 
                $frame_type_condition AND $equipment_package_condition AND $gear_condition
                ");

    $vehicle_type = 'Car';
}
else if($bus_filter){
    $vehicle_query = oci_parse($connection, "SELECT v.*, bv.PRICE
                FROM VEHICLE v, BUS bus, MODEL m, BRANCH_RLTD_VEHICLE bv
                WHERE v.ID = bv.VEHICLE_ID AND v.ID = bus.VEHICLE_ID AND v.MODEL_ID = m.ID
                AND bv.PRICE <= $max_amount AND bv.PRICE >= $min_amount AND v.YEAR <= $max_year AND v.YEAR >= $min_year AND 
                v.KILOMETER <= $max_km AND v.KILOMETER >= $min_km AND bus.PASSENGER_CAPACITY <= $max_capacity AND 
                bus.PASSENGER_CAPACITY >= $min_capacity AND m.FUEL_TYPE LIKE '$fuel_type' AND $gear_condition
                ");

    $vehicle_type = 'Bus';
}
else if($motorcycle_filter){
    $vehicle_query = oci_parse($connection, "SELECT v.*, bv.PRICE
                FROM VEHICLE v, MOTORCYCLE motocycle, MODEL m, BRANCH_RLTD_VEHICLE bv
                WHERE v.ID = bv.VEHICLE_ID AND v.ID = motocycle.VEHICLE_ID AND v.MODEL_ID = m.ID 
                AND bv.PRICE <= $max_amount AND bv.PRICE >= $min_amount AND v.YEAR <= $max_year AND v.YEAR >= $min_year AND 
                v.KILOMETER <= $max_km AND v.KILOMETER >= $min_km AND motocycle.MOTORCYCLE_TYPE LIKE '$motorcycle_type'AND 
                m.FUEL_TYPE LIKE '$fuel_type' AND $gear_condition
                ");

    $vehicle_type = 'Motorcycle';
}
else if($truck_filter){
    $vehicle_query = oci_parse($connection, "SELECT v.*, bv.PRICE
                FROM VEHICLE v, TRUCK truck, MODEL m, BRANCH_RLTD_VEHICLE bv
                WHERE v.ID = bv.VEHICLE_ID AND v.ID = truck.VEHICLE_ID AND v.MODEL_ID = m.ID 
                AND bv.PRICE <= $max_amount AND bv.PRICE >= $min_amount AND v.YEAR <= $max_year AND v.YEAR >= $min_year AND 
                v.KILOMETER <= $max_km AND v.KILOMETER >= $min_km AND truck.TRAILER_TYPE LIKE '$trailer_type' AND 
                truck.BALE_CAPACITY <= $max_bale_capacity AND truck.BALE_CAPACITY >= $min_bale_capacity AND 
                truck.TRAILER_VOLUME <= $max_trailer_volume AND truck.TRAILER_VOLUME >= $min_trailer_volume AND 
                m.FUEL_TYPE LIKE '$fuel_type' AND $gear_condition
                ");

    $vehicle_type = 'Truck';
}
else{
    $vehicle_query = oci_parse($connection, "SELECT v.*, bv.PRICE
                FROM VEHICLE v, BRANCH_RLTD_VEHICLE bv
                WHERE v.ID = bv.VEHICLE_ID");
}
oci_execute($vehicle_query);

$content = '';
$content_html = "
                 <style>
                    body {
                        overflow: auto;
                    }
                    h1 {
                        margin-top: 30px;
                    }
                    table {
                        text-align: center;
                        table-layout: fixed;
                        width: 80%;
                        margin-top: 60px;
                    }
                    table, table th, table td {
                        border: 1px solid #ff6600;
                    }
                    table th, table td {
                        padding: 10px 10px 10px 10px;
                    }
                 </style>
                 <center>
                 <h1>$vehicle_type List</h1>
                 <table>
                    <tr>
                        <th><b>Plate</b></th>
                        <th><b>Kilometer</b></th>
                        <th><b>Model Year</b></th>
                        <th><b>Amount</b></th>
                    </tr>
                ";

while ($row = oci_fetch_array($vehicle_query,OCI_ASSOC+OCI_RETURN_NULLS)) {
    $vehicle_id = $row['ID'];
    $image_path = $row['IMAGE_PATH'];
    $vehicle_photo = "../$image_path";

    echo "<img src='$vehicle_photo' style='width: 10%; height: 8%;'>";
    echo "<a href='../vehicle_detail.php?Id=$vehicle_id'>&emsp;&emsp;Plate: ".$row['PLATE']." | Kilometer:".$row['KILOMETER']."km. | Model Year:".$row['YEAR']." | Amount:".$row['PRICE']."TL</a><br><br>";

    # Generate str data for txt files
    $content .= str_pad("Plate: " . $row['PLATE'], 25);
    $content .= str_pad("Kilometer: " . $row['KILOMETER'] . "km", 25);
    $content .= str_pad("Model Year: " . $row['YEAR'], 25);
    $content .= str_pad("Amount: " . $row['PRICE'] . "TL", 25);
    $content .= "\n";

    # Genrate str data for html files
    $content_html .= "<tr>";
    $content_html .= "<td>" . $row['PLATE'] . "</td>";
    $content_html .= "<td>" . $row['KILOMETER'] . "km" . "</td>";
    $content_html .= "<td>" . $row['YEAR'] . "</td>";
    $content_html .= "<td>" . $row['PRICE'] . "TL" . "</td>";
    $content_html .= "</tr>";
}

$content_html .= "</table></center>";

echo "<form action='../download_file.php' method='post' style='float: left; padding-left: 15px;'>";
echo "<input name='data' type='hidden' value='$content'>";
echo "<input name='file_type' type='hidden' value='txt'>";
echo "<input name='submit' type='submit' value='Download TXT'>";
echo "</form>";

echo "<form action='../download_file.php' method='post' style='float: left; padding-left: 15px;'>";
echo "<input name='data' type='text' hidden value='$content_html'>";
echo "<input name='file_type' type='hidden' value='html'>";
echo "<input name='submit' type='submit' value='Download HTML'>";
echo "</form>";

echo "<form action='../download_file.php' method='post' style='float: left; padding-left: 15px;'>";
echo "<input name='data' type='hidden' value='$content'>";
echo "<input name='file_type' type='hidden' value='pdf'>";
echo "<input name='submit' type='submit' value='Download PDF'>";
echo "</form>";
?>
