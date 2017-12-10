<?php
include "../common.php";
include "../connection.php";
include "../authentication/admin_user_required.php";

# If an Id provided from the URL, update its values
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $item_query = oci_parse($connection, "SELECT * FROM BRAND WHERE ID = $id");
    oci_execute($item_query);

    $item = oci_fetch_array($item_query, OCI_ASSOC + OCI_RETURN_NULLS);
}
?>

    <html>
    <body>

    <center>
        <form action="create_brand.php" method="post">
            Name <br>
            <input type="text" name="brand_name" value="<?php echo htmlspecialchars($item['NAME']); ?>"><br><br>
            Nationality <br>
            <input type="text" name="nationality" value="<?php echo htmlspecialchars($item['NATIONALITY']); ?>"><br><br>
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
                    UPDATE_BRAND(:id, :name, :nationality);
                    COMMIT;
                END;'
        );

        oci_bind_by_name($item_save_sql, ":id", $_POST['id']);
    } else {
        $item_save_sql = oci_parse($connection, '
                BEGIN
                    INSERT_BRAND(:name, :nationality);
                    COMMIT;
                END;'
        );
    }

    # Add arguments
    oci_bind_by_name($item_save_sql, ":name", $_POST['brand_name']);
    oci_bind_by_name($item_save_sql, ":nationality", $_POST['nationality']);

    oci_execute($item_save_sql);
}
?>