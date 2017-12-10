<?php
include "../common.php";
include "../connection.php";
include "../authentication/admin_user_required.php";

# If an Id provided from the URL, update its values
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $item_query = oci_parse($connection, "SELECT * FROM BRANCH WHERE ID = $id");
    oci_execute($item_query);

    $item = oci_fetch_array($item_query, OCI_ASSOC + OCI_RETURN_NULLS);
}
?>

    <html>
    <body>

    <center>
        <form action="create_branch.php" method="post">
            County <br><select name="county_id">

                <?php
                $county_query = oci_parse($connection, 'SELECT * FROM COUNTY');
                oci_execute($county_query);

                while ($row = oci_fetch_array($county_query, OCI_ASSOC + OCI_RETURN_NULLS)) {
                    if ($row['ID'] == $item['COUNTY_ID']) {
                        echo "<option selected='selected' value='" . $row['ID'] . "'>" . $row['NAME'] . "</option>";
                    } else {
                        echo "<option value='" . $row['ID'] . "'>" . $row['NAME'] . "</option>";
                    }
                }
                ?>

            </select><br><br>
            Branch <br>
            <input type="text" name="branch" value="<?php echo htmlspecialchars($item['NAME']); ?>"><br><br>
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
                UPDATE_BRANCH(:id, :name, :county_id);
                COMMIT;
            END;'
        );

        oci_bind_by_name($item_save_sql, ":id", $_POST['id']);
    } else {
        $item_save_sql = oci_parse($connection, '
            BEGIN
                INSERT_BRANCH(:name, :county_id);
                COMMIT;
            END;'
        );
    }

    # Add arguments
    oci_bind_by_name($item_save_sql, ":name", $_POST['branch']);
    oci_bind_by_name($item_save_sql, ":county_id", $_POST['county_id']);

    oci_execute($item_save_sql);
}
?>