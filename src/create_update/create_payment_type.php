<?php
include "../common.php";
include "../connection.php";
include "../authentication/admin_user_required.php";

# If an Id provided from the URL, update its values
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $item_query = oci_parse($connection, "SELECT * FROM PAYMENT_TYPE WHERE ID = $id");
    oci_execute($item_query);

    $item = oci_fetch_array($item_query, OCI_ASSOC + OCI_RETURN_NULLS);
}
?>

    <html>
    <body>

    <center>
        <form action="create_payment_type.php" method="post">
            Payment Type <br>
            <input type="text" name="payment_type" value="<?php echo htmlspecialchars($item['NAME']); ?>"><br><br>
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
                    UPDATE_PAYMENT_TYPE(:id, :name);
                    COMMIT;
                END;'
            );

            oci_bind_by_name($item_save_sql, ":id", $_POST['id']);
        } else {
            $item_save_sql = oci_parse($connection, '
                BEGIN
                    INSERT_PAYMENT_TYPE(:name);
                    COMMIT;
                END;'
            );
        }

        # Add arguments
        oci_bind_by_name($item_save_sql, ":name", $_POST['payment_type']);

        oci_execute($item_save_sql);
    }
?>