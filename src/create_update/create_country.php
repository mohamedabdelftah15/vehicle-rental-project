<?php
include "../common.php";
include "../connection.php";
include "../authentication/admin_user_required.php";

# If an Id provided from the URL, update its values
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $item_query = oci_parse($connection, "SELECT * FROM COUNTRY WHERE ID = $id");
    oci_execute($item_query);

    $item = oci_fetch_array($item_query, OCI_ASSOC + OCI_RETURN_NULLS);
}
?>

    <html>

    <center>
        <form action="create_country.php" method="post">
            Country <br><input type="text" name="country" value="<?php echo htmlspecialchars($item['NAME']); ?>"><br><br>
            <input style="display: none" type="text" name="id" value="<?php echo $item['ID']; ?>">
            <input type="submit" name="submit">
        </form>
    </center>

    </html>

<?php
if (isset($_POST['submit'])) {
    if ($_POST['id']) {
        $item_save_sql = oci_parse($connection, '
                BEGIN
                    UPDATE_COUNTRY(:country_id, :country_name);
                    COMMIT;
                END;'
        );

        oci_bind_by_name($item_save_sql, ":country_id", $_POST['id']);
    } else {
        $item_save_sql = oci_parse($connection, '
                BEGIN
                    INSERT_COUNTRY(:country_name);
                    COMMIT;
                END;'
        );
    }

    # Add arguments
    oci_bind_by_name($item_save_sql, ":country_name", $_POST['country']);

    oci_execute($item_save_sql);
}
?>