<?php
include "common.php";
include "authentication/admin_user_required.php";
?>

<html>

<div class="update-header-container">
    <h1 class="update-header">User Logs</h1>
</div>

<div class="user-log-container">
    <ul class="user-log-list">
        <?php

        $user_logs_query = oci_parse(
            $connection,
            "SELECT
                        USERNAME,
                        FIRST_NAME,
                        LAST_NAME,
                        DESCRIPTION, 
                        TO_CHAR(LOG_DATE, 'HH24:MI:SS Day') LOG_DATE 
                     FROM USER_LOG UL JOIN \"USER\" U ON UL.USER_ID = U.ID ORDER BY LOG_DATE DESC"
        );
        oci_execute($user_logs_query);

        while ($row = oci_fetch_array($user_logs_query, OCI_ASSOC + OCI_RETURN_NULLS)) {
            echo "<li class='user-log-list-item'>
                    <b>" . $row['FIRST_NAME'] . " " . $row['LAST_NAME'] . " (" . $row['USERNAME'] . ") at </b>" . $row['LOG_DATE'] . "<br>
                    &emsp; " . $row['DESCRIPTION'] . "
                  </li>";

            echo "<br>";
        }
        ?>
    </ul>
</div>
</html>