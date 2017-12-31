<?php
include "../common.php";
include "../authentication/admin_user_required.php";

# If an Id provided from the URL, update its values
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $item_query = oci_parse($connection, "SELECT * FROM MODEL WHERE ID = $id");
    oci_execute($item_query);

    $item = oci_fetch_array($item_query, OCI_ASSOC + OCI_RETURN_NULLS);
}
?>

    <html>
    <body>

    <center>
        <h1>Model</h1>

        <div class="create-form-container">
            <form action="create_model.php" method="post">
                Name <br>
                <input type="text" name="model_name" value="<?php echo htmlspecialchars($item['NAME']); ?>"><br><br>

                Brand <br><select name="brand_id">
                    <?php
                    $city_query = oci_parse($connection, 'SELECT * FROM BRAND');
                    oci_execute($city_query);

                    while ($row = oci_fetch_array($city_query, OCI_ASSOC + OCI_RETURN_NULLS)) {
                        if ($row['ID'] == $item['BRAND_ID']) {
                            echo "<option selected='selected' value='" . $row['ID'] . "'>" . $row['NAME'] . "</option>";
                        } else {
                            echo "<option value='" . $row['ID'] . "'>" . $row['NAME'] . "</option>";
                        }
                    }
                    ?>
                </select><br><br>

                Fuel-Type <br>
                <select name="fuel_type" required>
                    <option value=''>-- PLEASE SELECT --</option>
                    <option value='Diesel'>Diesel</option>
                    <option value='Hybrid'>Hybrid</option>
                    <option value='Gasoline'>Gasoline</option>
                    <option value='Electric'>Electric</option>
                    <option value='Gasoline + LPG'>Gasoline + LPG</option>
                </select><br><br>

                Engine <br><select name="engine_id">
                    <?php
                    $engine_query = oci_parse($connection, 'SELECT * FROM ENGINE');
                    oci_execute($engine_query);

                    while ($row = oci_fetch_array($engine_query, OCI_ASSOC + OCI_RETURN_NULLS)) {
                        if ($row['ID'] == $item['ENGINE_ID']) {
                            echo "<option selected='selected' value='" . $row['ID'] . "'>" . $row['VOLUME'] . "-" . $row['POWER'] . "</option>";
                        } else {
                            echo "<option value='" . $row['ID'] . "'>" . $row['VOLUME'] . " - " . $row['POWER'] . "</option>";
                        }
                    }
                    ?>
                </select><br><br>

                Gear <br><select name="gear_id">
                    <?php
                    $gear_query = oci_parse($connection, 'SELECT * FROM GEAR');
                    oci_execute($gear_query);

                    while ($row = oci_fetch_array($gear_query, OCI_ASSOC + OCI_RETURN_NULLS)) {
                        if ($row['ID'] == $item['GEAR_ID']) {
                            echo "<option selected='selected' value='" . $row['ID'] . "'>" . $row['TYPE'] . "-" . $row['COUNT'] . "</option>";
                        } else {
                            echo "<option value='" . $row['ID'] . "'>" . $row['TYPE'] . " - " . $row['COUNT'] . "</option>";
                        }
                    }
                    ?>
                </select><br><br>

                Vehicle-Type <br>
                <select name="vehicle_type" required>
                    <option value=''>-- PLEASE SELECT --</option>
                    <option value='<?php echo $VEHICLE_TYPE_CAR ?>'><?php echo $VEHICLE_TYPE_CAR ?></option>
                    <option value='<?php echo $VEHICLE_TYPE_BUS ?>'><?php echo $VEHICLE_TYPE_BUS ?></option>
                    <option value='<?php echo $VEHICLE_TYPE_TRUCK ?>'><?php echo $VEHICLE_TYPE_TRUCK ?></option>
                    <option value='<?php echo $VEHICLE_TYPE_MOTORCYCLE ?>'><?php echo $VEHICLE_TYPE_MOTORCYCLE ?></option>
                </select><br><br>

                <input style="display: none" type="text" name="id" value="<?php echo $item['ID']; ?>">
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
                UPDATE_MODEL(:id, :name, :brand_id, :engine_id, :gear_id, :fuel_type, :vehicle_type);
                COMMIT;
            END;'
        );

        oci_bind_by_name($item_save_sql, ":id", $_POST['id']);
    } else {
        $item_save_sql = oci_parse($connection, '
            BEGIN
                INSERT_MODEL(:name, :brand_id, :engine_id, :gear_id, :fuel_type, :vehicle_type);
                COMMIT;
            END;'
        );
    }

    # Add arguments
    oci_bind_by_name($item_save_sql, ":name", $_POST['model_name']);
    oci_bind_by_name($item_save_sql, ":brand_id", $_POST['brand_id']);
    oci_bind_by_name($item_save_sql, ":engine_id", $_POST['engine_id']);
    oci_bind_by_name($item_save_sql, ":gear_id", $_POST['gear_id']);
    oci_bind_by_name($item_save_sql, ":fuel_type", $_POST['fuel_type']);
    oci_bind_by_name($item_save_sql, ":vehicle_type", $_POST['vehicle_type']);

    oci_execute($item_save_sql);
}
?>