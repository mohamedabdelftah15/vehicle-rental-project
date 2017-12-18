<?php
include "../common.php";
include "../connection.php";
include "../authentication/admin_user_required.php";

# If an Id provided from the URL, update its values
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $item_query = oci_parse($connection, "SELECT * FROM COUNTY WHERE ID = $id");
    oci_execute($item_query);

    $item = oci_fetch_array($item_query, OCI_ASSOC + OCI_RETURN_NULLS);
}
?>

    <html>
    <body>

    <center>
        <h1>County</h1>

        <form action="create_county.php" method="post">
            City <br><select name="city_id">

                <?php
                $city_query = oci_parse($connection, 'SELECT * FROM CITY');
                oci_execute($city_query);

                while ($row = oci_fetch_array($city_query, OCI_ASSOC + OCI_RETURN_NULLS)) {
                    if ($row['ID'] == $item['CITY_ID']) {
                        echo "<option selected='selected' value='" . $row['ID'] . "'>" . $row['NAME'] . "</option>";
                    } else {
                        echo "<option value='" . $row['ID'] . "'>" . $row['NAME'] . "</option>";
                    }
                }
                ?>

            </select><br><br>
            County <br>
            <input type="text" name="county" value="<?php echo htmlspecialchars($item['NAME']); ?>"><br><br>
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
                UPDATE_COUNTY(:id, :name, :city_id);
                COMMIT;
            END;'
        );

        oci_bind_by_name($item_save_sql, ":id", $_POST['id']);
    } else {
        $item_save_sql = oci_parse($connection, '
            BEGIN
                INSERT_COUNTY(:name, :city_id);
                COMMIT;
            END;'
        );
    }

    # Add arguments
    oci_bind_by_name($item_save_sql, ":name", $_POST['county']);
    oci_bind_by_name($item_save_sql, ":city_id", $_POST['city_id']);

    oci_execute($item_save_sql);
}
?>