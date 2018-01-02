<?php

include "connection.php";


if($_GET["type"] == "cityCombobox"){

    $countryId = $_POST["countryId"];
    $type = "countyCombobox";

    echo "City<br><select name='city' onchange=\"getCountyCombobox(this.value, 'countyCombobox');\">
                <option value= 0 selected='selected'>All</option>";

                $city_query = oci_parse($connection, "SELECT * FROM CITY WHERE COUNTRY_ID = $countryId");
                oci_execute($city_query);

                while ($row = oci_fetch_array($city_query,OCI_ASSOC+OCI_RETURN_NULLS)) {
                    echo "<option value='" . $row['ID'] . "'>" . $row['NAME'] . "</option>";
                }

    echo  "</select><br><br>";
}

else if($_GET["type"] == "countyCombobox"){

    $cityId = $_POST["cityId"];

    echo "County<br><select name='county'>
                <option value= 0 selected='selected'>All</option>";

    $county_query = oci_parse($connection, "SELECT * FROM COUNTY WHERE CITY_ID = $cityId");
    oci_execute($county_query);

    while ($row = oci_fetch_array($county_query,OCI_ASSOC+OCI_RETURN_NULLS)) {
        echo "<option value='" . $row['ID'] . "'>" . $row['NAME'] . "</option>";
    }

    echo  "</select><br><br>";
}


else if($_GET["type"] == "cityComboboxForCreate"){

    $countryId = $_POST["countryId"];
    $type = "countyCombobox";

    echo "City<br><select name='city' onchange=\"getCountyCombobox(this.value, 'countyComboboxForCreate');\" required>
        <option value= 0 selected='selected'>-- PLEASE SELECT --</option>";

    $city_query = oci_parse($connection, "SELECT * FROM CITY WHERE COUNTRY_ID = $countryId");
    oci_execute($city_query);

    while ($row = oci_fetch_array($city_query,OCI_ASSOC+OCI_RETURN_NULLS)) {
        echo "<option value='" . $row['ID'] . "'>" . $row['NAME'] . "</option>";
    }

    echo  "</select><br><br>";
}

else if($_GET["type"] == "countyComboboxForCreate"){

    $cityId = $_POST["cityId"];

    echo "County<br><select name='county' required>
        <option value= 0 selected='selected'>-- PLEASE SELECT --</option>";

    $county_query = oci_parse($connection, "SELECT * FROM COUNTY WHERE CITY_ID = $cityId");
    oci_execute($county_query);

    while ($row = oci_fetch_array($county_query,OCI_ASSOC+OCI_RETURN_NULLS)) {
        echo "<option value='" . $row['ID'] . "'>" . $row['NAME'] . "</option>";
    }

    echo  "</select><br><br>";
}


?>