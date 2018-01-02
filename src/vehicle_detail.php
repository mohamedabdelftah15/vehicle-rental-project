<?php
include "common.php";
include "connection.php";

$vehicle_id = $_GET["Id"];

# Alert the message to the user
if (isset($_GET['message'])) {
    $message = $_GET['message'];
    echo "<script type='text/javascript'>alert('$message');</script>";
}
?>

<?php

# Fetch common vehicle details
$vehicle_query = oci_parse($connection, "SELECT * FROM VEHICLE_DETAILS WHERE ID = $vehicle_id");

oci_execute($vehicle_query);
$vehicle = oci_fetch_array($vehicle_query, OCI_ASSOC + OCI_RETURN_NULLS);

$vehicle_type = $vehicle['VEHICLE_TYPE'];

if ($vehicle_type == "CAR") {
    $vehicle_sub_query = oci_parse(
        $connection,
        "SELECT vt.*, ft.NAME as FRAME_TYPE, ft.DOOR_COUNT, e.*
          FROM $vehicle_type vt, FRAME_TYPE ft, EQUIPMENT_PACKAGE e
          WHERE vt.VEHICLE_ID = $vehicle_id AND vt.FRAME_TYPE_ID = ft.ID AND vt.EQUIPMENT_PACKAGE_ID = e.ID"
    );
} else {
    $vehicle_sub_query = oci_parse($connection, "SELECT vt.* FROM $vehicle_type vt
                WHERE vt.VEHICLE_ID = $vehicle_id");
}

oci_execute($vehicle_sub_query);
$vehicle_sub = oci_fetch_array($vehicle_sub_query, OCI_ASSOC + OCI_RETURN_NULLS);

?>

<html>
<center>
    <div class="vehicle-detail-container">
        <img src="<?php echo $vehicle['IMAGE_PATH']; ?>" style="width: 640px; height: 360px;">

        <table class="vehicle-detail-table">

            <tr>
                <th>Vehicle Details</th>
                <th>Rental Details</th>
            </tr>

            <tr>
                <!-- Vehicle Details Column -->
                <td>
                    <b>Branch Name: </b> <?php echo $vehicle['BRANCH_NAME']; ?>
                    <br> <b>Branch Address: </b> <?php echo $vehicle['BRANCH_ADDRESS']; ?>
                    <br> <b>Country - City - County: </b>
                    <?php echo $vehicle['COUNTRY'] . ' - ' . $vehicle['CITY'] . ' - ' . $vehicle['COUNTY']; ?>
                    <br> <b>Amount(1 day): </b> <?php echo $vehicle['PRICE'] . ' TL(₺)'; ?>
                    <br> <b>Brand: </b> <?php echo $vehicle['BRAND_NAME']; ?>
                    <br> <b>Model: </b> <?php echo $vehicle['MODEL_NAME']; ?>
                    <br> <b>Year: </b> <?php echo $vehicle['YEAR']; ?>
                    <br> <b>Kilometer: </b> <?php echo $vehicle['KILOMETER']; ?>
                    <br> <b>Engine-Volume: </b> <?php echo $vehicle['VOLUME'] . ' l'; ?>
                    <br> <b>Engine-Power: </b> <?php echo $vehicle['POWER']; ?>
                    <br> <b>Fuel: </b> <?php echo $vehicle['FUEL_TYPE']; ?>
                    <br> <b>Gear: </b> <?php echo $vehicle['GEAR_TYPE']; ?>

                    <!-- Motorcycle specific details -->
                    <?php
                    if ($vehicle_type == $VEHICLE_TYPE_MOTORCYCLE) {
                        echo '<br> <b>Type: </b>' . $vehicle_sub['MOTORCYCLE_TYPE'];
                    }
                    ?>

                    <!-- Bus specific details -->
                    <?php
                    if ($vehicle_type == $VEHICLE_TYPE_BUS) {
                        echo '<br> <b>Passanger Capacity: </b>' . $vehicle_sub['PASSENGER_CAPACITY'];
                    }
                    ?>

                    <!-- Truck specific details -->
                    <?php
                    if ($vehicle_type == $VEHICLE_TYPE_TRUCK) {
                        echo '<br> <b>Trailer Type: </b>' . $vehicle_sub['TRAILER_TYPE'];
                        echo '<br> <b>Trailer Volume (l): </b>' . $vehicle_sub['TRAILER_VOLUME'];
                        echo '<br> <b>Bale Capacity (kg): </b>' . $vehicle_sub['BALE_CAPACITY'];
                    }
                    ?>

                    <!-- Car specific details -->
                    <?php
                    if ($vehicle_type == $VEHICLE_TYPE_CAR) {
                        echo '<br> <b>Segment: </b>' . $vehicle_sub['SEGMENT'];
                        echo '<br> <b>Frame-Type: </b>' . $vehicle_sub['FRAME_TYPE'] . ' Doors: ' . $vehicle_sub['DOOR_COUNT'];

                        echo '<hr>';

                        echo '<b>Equipment Package: </b>' . $vehicle_sub['PACKAGE_NAME'];
                        echo '<br> <b>Airbag Count: </b>' . $vehicle_sub['AIRBAG_COUNT'];
                        echo '<br> <b>Alarm: </b>' . ($vehicle_sub['ALARM'] == 1 ? 'YES' : 'NO');
                        echo '<br> <b>ABS: </b>' . ($vehicle_sub['ABS'] == 1 ? 'YES' : 'NO');
                        echo '<br> <b>ISOFIX: </b>' . ($vehicle_sub['ISOFIX'] == 1 ? 'YES' : 'NO');
                        echo '<br> <b>Immobilizer: </b>' . ($vehicle_sub['IMMOBILIZER'] == 1 ? 'YES' : 'NO');
                        echo '<br> <b>Trip Computer: </b>' . ($vehicle_sub['TRIP_COMPUTER'] == 1 ? 'YES' : 'NO');
                        echo '<br> <b>Start-Stop: </b>' . ($vehicle_sub['START_STOP'] == 1 ? 'YES' : 'NO');
                        echo '<br> <b>Cruise Control: </b>' . ($vehicle_sub['CRUISE_CONTROL'] == 1 ? 'YES' : 'NO');
                        echo '<br> <b>Air Conditioning: </b>' . ($vehicle_sub['AIR_CONDITIONING'] == 1 ? 'YES' : 'NO');
                        echo '<br> <b>Sunroof: </b>' . ($vehicle_sub['SUNROOF'] == 1 ? 'YES' : 'NO');
                        echo '<br> <b>Steel-Wheel: </b>' . ($vehicle_sub['STEEL_WHEEL'] == 1 ? 'YES' : 'NO');
                        echo '<br> <b>Rain Sensor: </b>' . ($vehicle_sub['RAIN_SENSOR'] == 1 ? 'YES' : 'NO');
                        echo '<br> <b>Parking Sensor: </b>' . ($vehicle_sub['PARKING_SENSOR'] == 1 ? 'YES' : 'NO');
                        echo '<br> <b>Head-Lights Sensor: </b>' . ($vehicle_sub['HEAD_LIGHTS_SENSOR'] == 1 ? 'YES' : 'NO');
                        echo '<br> <b>Navigation: </b>' . ($vehicle_sub['NAVIGATION'] == 1 ? 'YES' : 'NO');
                        echo '<br> <b>Assistant Cameras: </b>' . ($vehicle_sub['ASSISTANT_CAMERAS'] == 1 ? 'YES' : 'NO');
                        echo '<br> <b>Hill Holder: </b>' . ($vehicle_sub['HILL_HOLDER'] == 1 ? 'YES' : 'NO');
                    }
                    ?>
                </td>

                <!-- Rental Details Column -->
                <td>
                    <center>
                        <?php
                        # Check vehicle availability
                        if ($vehicle['IS_AVAILABLE'] != 1) {
                            echo '<h3 style="color: red">This vehicle is currently not available!</h3>';
                        }
                        ?>

                        <form action="rent_vehicle.php" method="post">
                            <label>Start Date</label><br>
                            <input type="date" name="start_date" required> <br>

                            <label>Due Date</label><br>
                            <input type="date" name="due_date" required> <br>

                            <label>Payment Type</label><br>
                            <select name="payment_type" required>
                                <option value=''>-- PLEASE SELECT --</option>
                                <option value='Credit Card'>Credit Card</option>
                                <option value='Debit Card'>Debit Card</option>
                                <option value='Credit Card'>PayPal</option>
                                <option value='EFT'>EFT</option>
                            </select><br><br>

                            <input type="number" name="vehicle_id" value="<?php echo $vehicle_id ?>" hidden>

                            <input type="submit" value="Rent">
                        </form>

                        <br>
                        <h3>Active Rents</h3>

                        <?php
                        $rents_query = oci_parse(
                            $connection,
                            "SELECT * FROM RENT_DETAILS WHERE VEHICLE_ID = $vehicle_id ORDER BY START_DATE ASC"
                        );

                        oci_execute($rents_query);

                        while ($row = oci_fetch_array($rents_query, OCI_ASSOC + OCI_RETURN_NULLS)) {
                            echo $row['START_DATE'] . ' - ' . $row['DUE_DATE'] . ' --- ';
                            echo $row['FIRST_NAME'] . ' ' . $row['LAST_NAME'];
                            echo ' (' . $row['USERNAME'] . ')';
                            echo '<br>';
                        }
                        ?>
                    </center>
                </td>
            </tr>
        </table>
    </div>
</center>

</html>
