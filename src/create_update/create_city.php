<?php
include "../common.php";
include "../authentication/admin_user_required.php";

# If an Id provided from the URL, update its values
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $item_query = oci_parse($connection, "SELECT * FROM CITY WHERE ID = $id");
    oci_execute($item_query);

    $item = oci_fetch_array($item_query, OCI_ASSOC + OCI_RETURN_NULLS);
}
?>

    <html>
    <body>

    <center>
        <h1>City</h1>

        <div class="create-form-container">
            <form action="create_city.php" method="post">
                Country <br><select name="country">

                    <?php
                    $country_query = oci_parse($connection, 'SELECT * FROM COUNTRY');
                    oci_execute($country_query);

                    while ($row = oci_fetch_array($country_query, OCI_ASSOC + OCI_RETURN_NULLS)) {
                        if ($row['ID'] == $item['COUNTRY_ID']) {
                            echo "<option selected='selected' value='" . $row['ID'] . "'>" . $row['NAME'] . "</option>";
                        } else {
                            echo "<option value='" . $row['ID'] . "'>" . $row['NAME'] . "</option>";
                        }
                    }
                    ?>

                </select><br><br>
                City <br>
                <input type="text" name="city" value="<?php echo htmlspecialchars($item['NAME']); ?>"><br><br>
                Plate No <br>
                <input type="text" name="plate_no" value="<?php echo htmlspecialchars($item['PLATE_NO']); ?>"><br><br>
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
                UPDATE_CITY(:city_id, :city_name, :plate_no, :country_id);
                COMMIT;
            END;'
        );

        oci_bind_by_name($item_save_sql, ":city_id", $_POST['id']);
    } else {
        $item_save_sql = oci_parse($connection, '
            BEGIN
                INSERT_CITY(:city_name, :plate_no, :country_id);
                COMMIT;
            END;'
        );
    }

    # Add arguments
    oci_bind_by_name($item_save_sql, ":city_name", $_POST['city']);
    oci_bind_by_name($item_save_sql, ":plate_no", $_POST['plate_no']);
    oci_bind_by_name($item_save_sql, ":country_id", $_POST['country']);

    oci_execute($item_save_sql);
}
?>