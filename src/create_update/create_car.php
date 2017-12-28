<?php
include "../common.php";
include "../authentication/branch_user_required.php";

# If an Id provided from the URL, update its values
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    # Fetch the VEHICLE
    $vehicle_query = oci_parse(
        $connection,
        "SELECT * FROM VEHICLE V LEFT JOIN BRANCH_RLTD_VEHICLE BV ON V.ID = BV.VEHICLE_ID WHERE V.ID = $id"
    );
    oci_execute($vehicle_query);

    $vehicle = oci_fetch_array($vehicle_query, OCI_ASSOC + OCI_RETURN_NULLS);

    # Fetch the CAR
    $car_query = oci_parse($connection, "SELECT * FROM CAR WHERE VEHICLE_ID = $id");
    oci_execute($car_query);

    $car = oci_fetch_array($car_query, OCI_ASSOC + OCI_RETURN_NULLS);
}
?>

    <html>
    <body>

    <center>
        <h1>Car</h1>

        <form action="create_car.php" method="post" enctype="multipart/form-data">

            <?php
            $vehicle_type = 'CAR';
            include "utils/vehicle_form.php";
            ?>

            Frame-Type <br>
            <select name="frame_type_id">
                <?php
                $frame_type_query = oci_parse($connection, 'SELECT * FROM FRAME_TYPE');
                oci_execute($frame_type_query);

                while ($row = oci_fetch_array($frame_type_query, OCI_ASSOC + OCI_RETURN_NULLS)) {
                    if ($row['ID'] == $car['FRAME_TYPE_ID']) {
                        echo "<option selected='selected' value='".$row['ID']."'>"."Name: ".$row['NAME']." | Doors: ".$row['DOOR_COUNT']."</option>";
                    } else {
                        echo "<option value='".$row['ID']."'>"."Name: ".$row['NAME']." | Doors: ".$row['DOOR_COUNT']."</option>";
                    }
                }
                ?>
            </select><br><br>

            Segment <br>
            <select name="segment" required>
                <option value=''>-- PLEASE SELECT --</option>";
                <option value='A'>A</option>";
                <option value='B'>B</option>";
                <option value='C'>C</option>";
                <option value='D'>D</option>";
                <option value='E'>E</option>";
                <option value='F'>F</option>";
                <option value='G'>G</option>";
            </select><br><br>

            Equipment Package <br>
            <select name="equipment_package_id">
                <?php
                $equipment_package_query = oci_parse($connection, 'SELECT * FROM EQUIPMENT_PACKAGE');
                oci_execute($equipment_package_query);

                while ($row = oci_fetch_array($equipment_package_query, OCI_ASSOC + OCI_RETURN_NULLS)) {
                    if ($row['ID'] == $car['EQUIPMENT_PACKAGE_ID']) {
                        echo "<option selected='selected' value='".$row['ID']."'>".$row['PACKAGE_NAME']."</option>";
                    } else {
                        echo "<option value='" . $row['ID'] . "'>" . $row['PACKAGE_NAME'] . "</option>";
                    }
                }
                ?>
            </select><br><br>

            <?php
            include "utils/branch_form.php"
            ?>

            <input style="display: none" type="text" name="id" value="<?php echo $vehicle['VEHICLE_ID']; ?>">
            <input type="submit" name="submit" value="Submit">
        </form>
    </center>

    </body>
    </html>

<?php
if (isset($_POST['submit'])) {
    if ($_POST['id']) {
        $item_save_sql = oci_parse($connection, '
            BEGIN
                UPDATE_VEHICLE(:vehicle_id, :model_id, :kilometer, :plate, :year, :image_path);
                UPDATE_CAR(:vehicle_id, :frame_type_id, :segment, :equipment_package_id);
                UPDATE_BRANCH_RLTD_VEHICLE(:vehicle_id, :branch_id, :is_available, :price);
                COMMIT;
            END;'
        );

        oci_bind_by_name($item_save_sql, ":vehicle_id", $_POST['id']);
    } else {
        $item_save_sql = oci_parse($connection, '
            DECLARE
                returned_vehicle_id NUMBER;
            BEGIN
                INSERT_VEHICLE(:model_id, :kilometer, :plate, :year, :image_path, returned_vehicle_id);
                INSERT_CAR(returned_vehicle_id, :frame_type_id, :segment, :equipment_package_id);
                INSERT_BRANCH_RLTD_VEHICLE(returned_vehicle_id, :branch_id, :is_available, :price);
                COMMIT;
            END;'
        );
    }

    # Operations about the image input
    include "utils/image_upload.php";

    # Add arguments
    oci_bind_by_name($item_save_sql, ":model_id", $_POST['model_id']);
    oci_bind_by_name($item_save_sql, ":kilometer", $_POST['kilometer']);
    oci_bind_by_name($item_save_sql, ":plate", $_POST['plate']);
    oci_bind_by_name($item_save_sql, ":year", $_POST['year']);
    oci_bind_by_name($item_save_sql, ":image_path", $target_file);
    oci_bind_by_name($item_save_sql, ":frame_type_id", $_POST['frame_type_id']);
    oci_bind_by_name($item_save_sql, ":segment", $_POST['segment']);
    oci_bind_by_name($item_save_sql, ":equipment_package_id", $_POST['equipment_package_id']);
    oci_bind_by_name($item_save_sql, ":branch_id", $_POST['branch_id']);
    oci_bind_by_name($item_save_sql, ":price", $_POST['price']);

    $is_available = ($_POST['is_available'] ? 1 : 0);
    oci_bind_by_name($item_save_sql, ":is_available", $is_available);

    oci_execute($item_save_sql);
}
?>