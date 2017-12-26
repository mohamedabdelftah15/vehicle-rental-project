<?php
include "../common.php";
include "../authentication/admin_user_required.php";

# If an Id provided from the URL, update its values
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $item_query = oci_parse($connection, "SELECT * FROM EQUIPMENT_PACKAGE WHERE ID = $id");
    oci_execute($item_query);

    $item = oci_fetch_array($item_query, OCI_ASSOC + OCI_RETURN_NULLS);
}
?>

    <html>
    <body>

    <center>
        <h1>Equipment-Package</h1>

        <form action="create_equipment_package.php" method="post">
            Package Name <br>
            <input type="text" name="package_name" value="<?php echo $item['PACKAGE_NAME']; ?>" required><br><br>
            Airbag Count <br>
            <input type="number" name="airbag_count" value="<?php echo $item['AIRBAG_COUNT']; ?>"><br><br>

            Alarm <br>
            <input type="checkbox" name="alarm" value="1"
                <?php echo($item['ALARM'] == 1 ? "checked" : '') ?>><br>
            ABS <br>
            <input type="checkbox" name="abs" value="1"
                <?php echo($item['ABS'] == 1 ? "checked" : '') ?>><br>
            ISOFIX <br>
            <input type="checkbox" name="isofix" value="1"
                <?php echo($item['ISOFIX'] == 1 ? "checked" : '') ?>><br>
            Immobilizer <br>
            <input type="checkbox" name="immobilizer" value="1"
                <?php echo($item['IMMOBILIZER'] == 1 ? "checked" : '') ?>><br>
            Trip Computer<br>
            <input type="checkbox" name="trip_computer" value="1"
                <?php echo($item['TRIP_COMPUTER'] == 1 ? "checked" : '') ?>><br>
            Start-Stop<br>
            <input type="checkbox" name="start_stop" value="1"
                <?php echo($item['START_STOP'] == 1 ? "checked" : '') ?>><br>
            Cruise Control<br>
            <input type="checkbox" name="cruise_control" value="1"
                <?php echo($item['CRUISE_CONTROL'] == 1 ? "checked" : '') ?>><br>
            Air Conditioning<br>
            <input type="checkbox" name="air_conditioning" value="1"
                <?php echo($item['AIR_CONDITIONING'] == 1 ? "checked" : '') ?>><br>
            Sunroof<br>
            <input type="checkbox" name="sunroof" value="1"
                <?php echo($item['SUNROOF'] == 1 ? "checked" : '') ?>><br>
            Parking Sensor<br>
            <input type="checkbox" name="parking_sensor" value="1"
                <?php echo($item['PARKING_SENSOR'] == 1 ? "checked" : '') ?>><br>
            Steel Wheel<br>
            <input type="checkbox" name="steel_wheel" value="1"
                <?php echo($item['STEEL_WHEEL'] == 1 ? "checked" : '') ?>><br>
            Rain Sensor<br>
            <input type="checkbox" name="rain_sensor" value="1"
                <?php echo($item['RAIN_SENSOR'] == 1 ? "checked" : '') ?>><br>
            Head Lights Sensor<br>
            <input type="checkbox" name="head_lights_sensor" value="1"
                <?php echo($item['HEAD_LIGHTS_SENSOR'] == 1 ? "checked" : '') ?>><br>
            Navigation<br>
            <input type="checkbox" name="navigation" value="1"
                <?php echo($item['NAVIGATION'] == 1 ? "checked" : '') ?>><br>
            Assistant Cameras<br>
            <input type="checkbox" name="assistant_cameras" value="1"
                <?php echo($item['ASSISTANT_CAMERAS'] == 1 ? "checked" : '') ?>><br>
            Hill Holder<br>
            <input type="checkbox" name="hill_holder" value="1"
                <?php echo($item['HILL_HOLDER'] == 1 ? "checked" : '') ?>><br><br>

            <input style="display: none" type="text" name="id" value="<?php echo $item['ID']; ?>">
            <input type="submit" name="submit">
        </form>
    </center>

    </body>
    </html>

<?php
if (isset($_POST['submit'])) {
    if ($_POST['id']) {
        $item_save_sql = oci_parse($connection, '
                BEGIN
                    UPDATE_EQUIPMENT_PACKAGE(:id, :name, :alarm, :abs, :isofix, :immobilizer, :airbag_count,
                        :trip_computer, :start_stop, :cruise_control, :air_conditioning, :sunroof, :parking_sensor,
                        :steel_wheel, :rain_sensor, :head_lights_sensor, :navigation, :assistant_cameras,
                        :hill_holder);
                    COMMIT;
                END;'
        );

        oci_bind_by_name($item_save_sql, ":id", $_POST['id']);
    } else {
        $item_save_sql = oci_parse($connection, '
            BEGIN
                INSERT_EQUIPMENT_PACKAGE(:name, :alarm, :abs, :isofix, :immobilizer, :airbag_count, :trip_computer, 
                    :start_stop, :cruise_control, :air_conditioning, :sunroof, :parking_sensor, :steel_wheel,
                    :rain_sensor, :head_lights_sensor, :navigation, :assistant_cameras, :hill_holder);
                COMMIT;
            END;'
        );
    }

    # Add arguments
    oci_bind_by_name($item_save_sql, ":name", $_POST['package_name']);
    oci_bind_by_name($item_save_sql, ":airbag_count", $_POST['airbag_count']);

    $alarm = ($_POST['alarm'] ? 1 : 0);
    oci_bind_by_name($item_save_sql, ":alarm", $alarm);

    $abs = ($_POST['abs'] ? 1 : 0);
    oci_bind_by_name($item_save_sql, ":abs", $abs);

    $isofix = ($_POST['isofix'] ? 1 : 0);
    oci_bind_by_name($item_save_sql, ":isofix", $isofix);

    $immobilizer = ($_POST['immobilizer'] ? 1 : 0);
    oci_bind_by_name($item_save_sql, ":immobilizer", $immobilizer);

    $trip_computer = ($_POST['trip_computer'] ? 1 : 0);
    oci_bind_by_name($item_save_sql, ":trip_computer", $trip_computer);

    $start_stop = ($_POST['start_stop'] ? 1 : 0);
    oci_bind_by_name($item_save_sql, ":start_stop", $start_stop);

    $cruise_control = ($_POST['cruise_control'] ? 1 : 0);
    oci_bind_by_name($item_save_sql, ":cruise_control", $cruise_control);

    $air_conditioning = ($_POST['air_conditioning'] ? 1 : 0);
    oci_bind_by_name($item_save_sql, ":air_conditioning", $air_conditioning);

    $sunroof = ($_POST['sunroof'] ? 1 : 0);
    oci_bind_by_name($item_save_sql, ":sunroof", $sunroof);

    $parking_sensor = ($_POST['parking_sensor'] ? 1 : 0);
    oci_bind_by_name($item_save_sql, ":parking_sensor", $parking_sensor);

    $steel_wheel = ($_POST['steel_wheel'] ? 1 : 0);
    oci_bind_by_name($item_save_sql, ":steel_wheel", $steel_wheel);

    $rain_sensor = ($_POST['rain_sensor'] ? 1 : 0);
    oci_bind_by_name($item_save_sql, ":rain_sensor", $rain_sensor);

    $head_lights_sensor = ($_POST['head_lights_sensor'] ? 1 : 0);
    oci_bind_by_name($item_save_sql, ":head_lights_sensor", $head_lights_sensor);

    $navigation = ($_POST['navigation'] ? 1 : 0);
    oci_bind_by_name($item_save_sql, ":navigation", $navigation);

    $assistant_cameras = ($_POST['assistant_cameras'] ? 1 : 0);
    oci_bind_by_name($item_save_sql, ":assistant_cameras", $assistant_cameras);

    $hill_holder = ($_POST['hill_holder'] ? 1 : 0);
    oci_bind_by_name($item_save_sql, ":hill_holder", $hill_holder);


    oci_execute($item_save_sql);
}
?>