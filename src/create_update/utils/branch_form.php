Branch <br><select name="branch_id">
    <?php
    if ($_SESSION['user_type'] == $USER_TYPE_ADMIN) {
        $branch_query = oci_parse(
            $connection,
            'SELECT * FROM BRANCH B LEFT JOIN BRANCH_RLTD_USER BU ON B.ID = BU.BRANCH_ID');
    }
    else {
        # BRANCH USER
        $branch_query = oci_parse(
            $connection,
            'SELECT * FROM BRANCH B LEFT JOIN BRANCH_RLTD_USER BU ON B.ID = BU.BRANCH_ID WHERE USER_ID = :user_id'
        );
        oci_bind_by_name($branch_query, ":user_id", $_SESSION['user_id']);
    }

    oci_execute($branch_query);

    while ($row = oci_fetch_array($branch_query, OCI_ASSOC + OCI_RETURN_NULLS)) {
        if ($row['BRANCH_ID'] == $vehicle['BRANCH_ID']) {
            echo "<option selected='selected' value='".$row['BRANCH_ID']."'>".$row['NAME']."</option>";
        } else {
            echo "<option value='".$row['BRANCH_ID']."'>".$row['NAME']."</option>";
        }
    }
    ?>
</select><br><br>

Price <br>
<input type="number" name="price" value="<?php echo $vehicle['PRICE']; ?>"><br><br>

Is Available <br>
<input type="checkbox" name="is_available" value="1" <?php echo($vehicle['IS_AVAILABLE'] == 1 ? "checked" : '') ?>><br><br>
