<?php
include "../common.php";
include "../connection.php";
include "../authentication/admin_user_required.php";

# If an Id provided from the URL, update its values
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $item_query = oci_parse($connection, "SELECT * FROM ENGINE WHERE ID = $id");
    oci_execute($item_query);

    $item = oci_fetch_array($item_query, OCI_ASSOC + OCI_RETURN_NULLS);
}
?>

    <html>
    <body>

    <center>
        <form action="create_engine.php" method="post">
            Volume <br>
            <input type="number" name="volume" value="<?php echo htmlspecialchars($item['VOLUME']); ?>"><br><br>
            Power <br>
            <input type="text" name="power" value="<?php echo htmlspecialchars($item['POWER']); ?>"><br><br>
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
                    UPDATE_ENGINE(:engine_id, :volume, :power);
                    COMMIT;
                END;'
        );

        oci_bind_by_name($item_save_sql, ":engine_id", $_POST['id']);
    } else {
        $item_save_sql = oci_parse($connection, '
            BEGIN
                INSERT_ENGINE(:volume, :power);
                COMMIT;
            END;'
        );
    }

    # Add arguments
    oci_bind_by_name($item_save_sql, ":volume", $_POST['volume']);
    oci_bind_by_name($item_save_sql, ":power", $_POST['power']);

    oci_execute($item_save_sql);
}
?>