<?php
include "../common.php";
include "../connection.php";
include "../authentication/admin_user_required.php";

# If an Id provided from the URL, update its values
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $item_query = oci_parse($connection, "SELECT * FROM FRAME_TYPE WHERE ID = $id");
    oci_execute($item_query);

    $item = oci_fetch_array($item_query, OCI_ASSOC + OCI_RETURN_NULLS);
}
?>

    <html>
    <body>

    <center>
        <form action="create_frame_type.php" method="post">
            Frame Type <br>
            <input type="text" name="frame_type" value="<?php echo htmlspecialchars($item['NAME']); ?>"><br><br>
            Door Count <br>
            <input type="number" name="door_count" value="<?php echo htmlspecialchars($item['DOOR_COUNT']); ?>"><br><br>
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
                    UPDATE_FRAME_TYPE(:frame_type_id, :frame_type_name, :door_count);
                    COMMIT;
                END;'
            );

            oci_bind_by_name($item_save_sql, ":frame_type_id", $_POST['id']);
        } else {
            $item_save_sql = oci_parse($connection, '
            BEGIN
                INSERT_FRAME_TYPE(:frame_type_name, :door_count);
                COMMIT;
            END;'
            );
        }

        # Add arguments
        oci_bind_by_name($item_save_sql, ":frame_type_name", $_POST['frame_type']);
        oci_bind_by_name($item_save_sql, ":door_count", $_POST['door_count']);

        oci_execute($item_save_sql);
    }
?>