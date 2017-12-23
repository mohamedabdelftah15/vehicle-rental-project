<?php

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
?>

<head>
    <link rel="stylesheet" type="text/css" href="/static/style.css">
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
</script>