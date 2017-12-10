<?php
include "../common.php";
include "../connection.php";
include "../authentication/admin_user_required.php";

# If an Id provided from the URL, update its values
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $item_query = oci_parse($connection, "SELECT * FROM GEAR WHERE ID = $id");
    oci_execute($item_query);

    $item = oci_fetch_array($item_query, OCI_ASSOC + OCI_RETURN_NULLS);
}
?>

    <html>
    <body>

    <center>
        <form action="create_gear.php" method="post">
            Gear Type <br>
            <input type="text" name="gear_type" value="<?php echo htmlspecialchars($item['TYPE']); ?>"><br><br>
            Gear Count <br>
            <input type="number" name="gear_count" value="<?php echo htmlspecialchars($item['COUNT']); ?>"><br><br>
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
                    UPDATE_GEAR(:gear_id, :gear_type, :gear_count);
                    COMMIT;
                END;'
        );

        oci_bind_by_name($item_save_sql, ":gear_id", $_POST['id']);
    } else {
        $item_save_sql = oci_parse($connection, '
                BEGIN
                    INSERT_GEAR(:gear_type, :gear_count);
                    COMMIT;
                END;'
        );
    }

    # Add arguments
    oci_bind_by_name($item_save_sql, ":gear_type", $_POST['gear_type']);
    oci_bind_by_name($item_save_sql, ":gear_count", $_POST['gear_count']);

    oci_execute($item_save_sql);
}
?>