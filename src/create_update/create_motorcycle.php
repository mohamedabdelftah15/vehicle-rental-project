<?php
include "../common.php";
include "../connection.php";
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

    # Fetch the MOTORCYCLE
    $motorcycle_query = oci_parse($connection, "SELECT * FROM MOTORCYCLE WHERE VEHICLE_ID = $id");
    oci_execute($motorcycle_query);

    $motorcycle = oci_fetch_array($motorcycle_query, OCI_ASSOC + OCI_RETURN_NULLS);
}
?>

    <html>
    <body>

    <center>
        <h1>Motorcycle</h1>

        <form action="create_motorcycle.php" method="post" enctype="multipart/form-data">

            <?php
            $vehicle_type = 'MOTORCYCLE';
            include "utils/vehicle_form.php";
            ?>

            Motorcycle-Type <br><select name="motorcycle_type_id">
                <?php
                $motorcycle_type_query = oci_parse($connection, 'SELECT * FROM MOTORCYCLE_TYPE');
                oci_execute($motorcycle_type_query);

                while ($row = oci_fetch_array($motorcycle_type_query, OCI_ASSOC + OCI_RETURN_NULLS)) {
                    if ($row['ID'] == $motorcycle['MOTORCYCLE_TYPE_ID']) {
                        echo "<option selected='selected' value='".$row['ID']."'>".$row['NAME']."</option>";
                    } else {
                        echo "<option value='".$row['ID']."'>".$row['NAME']."</option>";
                    }
                }
                ?>
            </select><br><br>

            <?php
            include "utils/branch_form.php"
            ?>

            <input style="display: none" type="text" name="id" value="<?php echo $vehicle['VEHICLE_ID']; ?>">
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
                UPDATE_VEHICLE(:vehicle_id, :model_id, :kilometer, :plate, :year, :image_path);
                UPDATE_MOTORCYCLE(:vehicle_id, :motorcycle_type_id);
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
                INSERT_MOTORCYCLE(returned_vehicle_id, :motorcycle_type_id);
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
    oci_bind_by_name($item_save_sql, ":motorcycle_type_id", $_POST['motorcycle_type_id']);
    oci_bind_by_name($item_save_sql, ":branch_id", $_POST['branch_id']);
    oci_bind_by_name($item_save_sql, ":price", $_POST['price']);

    $is_available = ($_POST['is_available'] ? 1 : 0);
    oci_bind_by_name($item_save_sql, ":is_available", $is_available);

    oci_execute($item_save_sql);
}
?>