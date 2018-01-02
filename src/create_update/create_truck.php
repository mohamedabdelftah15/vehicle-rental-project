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

    # Fetch the TRUCK
    $truck_query = oci_parse($connection, "SELECT * FROM TRUCK WHERE VEHICLE_ID = $id");
    oci_execute($truck_query);

    $truck = oci_fetch_array($truck_query, OCI_ASSOC + OCI_RETURN_NULLS);
}
?>

    <html>
    <body>

    <center>
        <h1>Truck</h1>

        <div class="create-form-container">
            <form action="create_truck.php" method="post" enctype="multipart/form-data">

                <?php
                $vehicle_type = 'TRUCK';
                include "utils/vehicle_form.php";
                ?>

                Bale Capacity <br>
                <input type="number" name="bale_capacity" value="<?php echo $truck['BALE_CAPACITY']; ?>"><br><br>

                Trailer Volume <br>
                <input type="number" name="trailer_volume" value="<?php echo $truck['TRAILER_VOLUME']; ?>"><br><br>

                Trailer-Type <br>
                <select name="trailer_type" required>
                    <option value=''>-- PLEASE SELECT --</option>
                    ";
                    <?php
                    $trailer_type_list = array (0 => 'OTHER', 1 => 'Lowboy', 2 => 'Side Kit', 3 => 'Flat Bed', 4 => 'Conestoga',
                        5 => 'Step Deck', 6 => 'Power Only', 7 => 'Dry Van (Enclosed)', 8 => 'Refrigerated (Reefer)',
                        9 => 'RGN (Removable Gooseneck)', 10 => 'Not Available');
                    for($i=0; $i<count($trailer_type_list);$i++){
                        if($truck['TRAILER_TYPE'] == $trailer_type_list[$i]){
                            echo "<option selected='selected' value=$trailer_type_list[$i]>$trailer_type_list[$i]</option>";
                        }
                        echo "<option value=$trailer_type_list[$i]>$trailer_type_list[$i]</option>";
                    }
                    ?>
                </select><br><br>

                <?php
                include "utils/branch_form.php"
                ?>

                <input style="display: none" type="text" name="id" value="<?php echo $vehicle['VEHICLE_ID']; ?>">
                <input type="submit" name="submit">
            </form>
        </div>
    </center>

    </body>
    </html>

<?php
if (isset($_POST['submit'])) {
    if ($_POST['id']) {
        $item_save_sql = oci_parse($connection, '
            BEGIN
                UPDATE_VEHICLE(:vehicle_id, :model_id, :kilometer, :plate, :year, :image_path);
                UPDATE_TRUCK(:vehicle_id, :bale_capacity, :trailer_volume, :trailer_type);
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
                INSERT_TRUCK(returned_vehicle_id, :bale_capacity, :trailer_volume, :trailer_type);
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
    oci_bind_by_name($item_save_sql, ":bale_capacity", $_POST['bale_capacity']);
    oci_bind_by_name($item_save_sql, ":trailer_volume", $_POST['trailer_volume']);
    oci_bind_by_name($item_save_sql, ":trailer_type", $_POST['trailer_type']);
    oci_bind_by_name($item_save_sql, ":branch_id", $_POST['branch_id']);
    oci_bind_by_name($item_save_sql, ":price", $_POST['price']);

    $is_available = ($_POST['is_available'] ? 1 : 0);
    oci_bind_by_name($item_save_sql, ":is_available", $is_available);

    oci_execute($item_save_sql);
}
?>