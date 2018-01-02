<?php
include __DIR__ . "/connection.php";

if (getPageName() != 'login.php' and getPageName() != 'user_register.php') {
    include __DIR__ . "/navigation_bar.php";
}

# Disable error messages on server
# error_reporting(0);

# Different user types defined here to be use easily in code
$USER_TYPE_ADMIN = "ADMIN";
$USER_TYPE_BRANCH = "BRANCH";
$USER_TYPE_MEMBER = "MEMBER";

# Different vehicle types defined here to be use easily in code
$VEHICLE_TYPE_CAR = 'CAR';
$VEHICLE_TYPE_BUS = 'BUS';
$VEHICLE_TYPE_TRUCK = 'TRUCK';
$VEHICLE_TYPE_MOTORCYCLE = 'MOTORCYCLE';

# Inserts a log entry for the current user into database
function create_user_log($description) {
    global $connection;

    # Check the session whether already started or not
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    # Check the ID of the current user
    if (isset($_SESSION['user_id']) and !is_null($_SESSION['user_id']) and $_SESSION['user_id'] != '') {
        $user_log_insert_sql = oci_parse($connection, '
            BEGIN
                INSERT_USER_LOG(:user_id, :description);
                COMMIT;
            END;'
        );

        oci_bind_by_name($user_log_insert_sql, ":user_id", $_SESSION['user_id']);
        oci_bind_by_name($user_log_insert_sql, ":description", $description);

        oci_execute($user_log_insert_sql);
    }
}

# Returns the name of the page
function getPageName(){
    return basename($_SERVER['PHP_SELF']);
}
?>

<head>
    <link rel="stylesheet" type="text/css" href="/static/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>

<script>
    function setView(val, type) {

        var elements = document.getElementsByClassName(val);

        for (var i = 0; i < elements.length; i++) {

            if (elements[i].style.display == "none" && type == 1) {
                elements[i].style.display = "block";
            }
            else {
                elements[i].style.display = "none";
                for (var j = 1; j < 100; j++) {
                    var x = val.toString() + j.toString();
                    var y = parseInt(x);
                    var subs = document.getElementsByClassName(y);
                    if (subs.length > 0)
                        setView(y, 2);
                    else
                        break;
                }
            }
        }
    }

    function getCityCombobox(val, type){
        $.ajax({
            type: "POST",
            url: "/ajax_utils.php?type="+type,
            data: 'countryId='+val,
            success: function(data) {
                $('#cityCombobox').html(data);
            }
        })
    }

    function getCountyCombobox(val, type){
        $.ajax({
            type: "POST",
            url: "/ajax_utils.php?type="+type,
            data: 'cityId='+val,
            success: function(data) {
                $('#countyCombobox').html(data);
            }
        })
    }
</script>