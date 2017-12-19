<?php
include "../common.php";
include "../connection.php";

$vehicle_id = $_GET["Id"];
?>

<?php

$vehicle_query = oci_parse($connection, "SELECT v.*, bv.PRICE, bh.NAME as BRANCH_NAME, m.NAME as MODEL_NAME, 
m.VEHICLE_TYPE, e.VOLUME, e.POWER, ft.NAME as FUEL_TYPE_NAME, g.TYPE as GEAR_TYPE, bd.NAME as BRAND_NAME
FROM VEHICLE v, BRANCH_RLTD_VEHICLE bv, BRANCH bh, MODEL m, BRAND bd, ENGINE e, FUEL_TYPE ft, GEAR g  
WHERE v.ID = $vehicle_id AND bv.VEHICLE_ID = v.ID AND bv.BRANCH_ID = bh.ID AND v.MODEL_ID = m.ID AND bd.ID = m.BRAND_ID
AND m.ENGINE_ID = e.ID AND m.FUEL_TYPE_ID = ft.ID AND m.GEAR_ID = g.ID");

oci_execute($vehicle_query);
$vehicle = oci_fetch_array($vehicle_query,OCI_ASSOC+OCI_RETURN_NULLS);

$vehicle_type = $vehicle['VEHICLE_TYPE'];

if($vehicle_type == "CAR"){
    $vehicle_sub_query = oci_parse($connection, "SELECT vt.*, s.NAME as SEGMENT, ft.NAME as FRAME_TYPE, ft.DOOR_COUNT,
    e.PACKAGE_NAME as EQUIPMENT_PACKAGE 
    FROM $vehicle_type vt, SEGMENT s, FRAME_TYPE ft, EQUIPMENT_PACKAGE e
    WHERE vt.VEHICLE_ID = $vehicle_id AND vt.SEGMENT_ID = s.ID AND vt.FRAME_TYPE_ID = ft.ID AND vt.EQUIPMENT_PACKAGE_ID = e.ID");
}
else if($vehicle_type == "MOTORCYCLE"){
    $vehicle_sub_query = oci_parse($connection, "SELECT vt.*,mt.NAME as MOTORCYCLE_TYPE 
    FROM $vehicle_type vt, MOTORCYCLE_TYPE mt
    WHERE vt.VEHICLE_ID = $vehicle_id AND vt.MOTORCYCLE_TYPE_ID = mt.ID");
}
else{
    $vehicle_sub_query = oci_parse($connection, "SELECT vt.* FROM $vehicle_type vt
                WHERE vt.VEHICLE_ID = $vehicle_id");
}

oci_execute($vehicle_sub_query);
$vehicle_sub = oci_fetch_array($vehicle_sub_query,OCI_ASSOC+OCI_RETURN_NULLS);

?>

<div>
    <table>
        <tr>
            <td rowspan="11"><img src="<?php echo $vehicle['IMAGE_PATH']; ?>" style="width: 700px; height: 400px;"></td>
            <th>Branch Info:</th>
            <td><?php echo $vehicle['BRANCH_NAME']; ?></td>
        </tr>
        <tr>
            <th>Brand:</th>
            <td><?php echo $vehicle['BRAND_NAME']; ?></td>
            <td width="50px"></td>
            <th>Segment:</th>
            <td><?php if($vehicle_type == "CAR"){echo $vehicle_sub['SEGMENT'];}else echo "-"; ?></td>
        </tr>
        <tr>
            <th>Model:</th>
            <td><?php echo $vehicle['MODEL_NAME']; ?></td>
            <td></td>
            <th>Frame Type:</th>
            <td><?php if($vehicle_type == "CAR"){ echo $vehicle_sub['FRAME_TYPE']; echo " Door:";echo $vehicle_sub['DOOR_COUNT'];}else echo "-"; ?></td>
        </tr>
        <tr>
            <th>Year:</th>
            <td><?php echo $vehicle['YEAR']; ?></td>
            <td></td>
            <th>Equipment Package:</th>
            <td><?php if($vehicle_type == "CAR"){echo $vehicle_sub['EQUIPMENT_PACKAGE'];}else echo "-"; ?></td>
        </tr>
        <tr>
            <th>Kilometer:</th>
            <td><?php echo $vehicle['KILOMETER']; ?></td>
            <td></td>
            <th>Passenger Capacity:</th>
            <td><?php if($vehicle_type == "BUS"){echo $vehicle_sub['PASSENGER_CAPACITY'];}else echo "-"; ?></td>
        </tr>
        <tr>
            <th>Engine Volume:</th>
            <td><?php echo $vehicle['VOLUME']; ?></td>
            <td></td>
            <th>Bale Capacity:</th>
            <td><?php if($vehicle_type == "TRUCK"){echo $vehicle_sub['BALE_CAPACITY'];}else echo "-"; ?></td>
        </tr>
        <tr>
            <th>Engine Power:</th>
            <td><?php echo $vehicle['POWER']; ?></td>
            <td></td>
            <th>Trailer Volume:</th>
            <td><?php if($vehicle_type == "TRUCK"){echo $vehicle_sub['TRAILER_VOLUME'];}else echo "-"; ?></td>
        </tr>
        <tr>
            <th>Fuel:</th>
            <td><?php echo $vehicle['FUEL_TYPE_NAME']; ?></td>
            <td></td>
            <th>Trailer Type:</th>
            <td><?php if($vehicle_type == "TRUCK"){echo $vehicle_sub['TRAILER_TYPE'];}else echo "-"; ?></td>
        </tr>
        <tr>
            <th>Gear:</th>
            <td><?php echo $vehicle['GEAR_TYPE']; ?></td>
            <td></td>
            <th>Type:</th>
            <td><?php if($vehicle_type == "MOTORCYCLE"){echo $vehicle_sub['MOTORCYCLE_TYPE'];}else echo "-"; ?></td>
        </tr>
        <tr>
            <th>Amount:</th>
            <td><?php echo $vehicle['PRICE']; echo " TL";?></td>

        </tr>
        <tr>
            <td></td>
            <td><input type="button" value="Kirala" /></td>
        </tr>

    </table>

</div>
