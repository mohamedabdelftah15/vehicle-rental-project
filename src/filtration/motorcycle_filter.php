<?php
include "../common.php";
include "../connection.php";
include "../authentication/login_required.php";
?>

<div class="left_menu">
    <center>
        <form action="motorcycle_filter.php" method="post">
            Amount<br><input type="number" name="min_amount" style="width: 100px;"> - <input type="number" name="max_amount"style="width: 100px;"><br><br>
            Year<br><input type="number" name="min_year" style="width: 100px;"> - <input type="number" name="max_year"style="width: 100px;"><br><br>
            Kilometer<br><input type="number" name="min_km" style="width: 100px;"> - <input type="number" name="max_km"style="width: 100px;"><br><br>

            Fuel Type<br><select name="fuel_type">
                <option value= '%' selected="selected">All</option>;
                <option value='Diesel'>Diesel</option>;
                <option value='Hybrid'>Hybrid</option>;
                <option value='Gasoline'>Gasoline</option>;
                <option value='Electric'>Electric</option>;
                <option value='Gasoline + LPG'>Gasoline + LPG</option>;
            </select><br><br>

            Gear<br><select name="gear">
                <option value= 0 selected="selected">All</option>
                <?php
                $gear_query = oci_parse($connection, 'SELECT * FROM GEAR');
                oci_execute($gear_query);

                while ($row = oci_fetch_array($gear_query,OCI_ASSOC+OCI_RETURN_NULLS)) {
                    echo "<option value='".$row['ID']."'>".$row['TYPE']."-".$row['COUNT']."</option>";
                }
                ?>
            </select><br><br>

            Motorcycle Type<br><select name="motorcycle_type">
                <option value= '%' selected="selected">All</option>;
                <option value='Moped'>Moped</option>;
                <option value='Cub'>Cub</option>;
                <option value='Commuter'>Commuter</option>;
                <option value='Scooter'>Scooter</option>;
                <option value='Touring'>Touring</option>;
                <option value='Sport Touring'>Sport Touring</option>;
                <option value='Chopper'>Chopper</option>;
                <option value='Enduro'>Enduro</option>;
                <option value='Super Sport'>Super Sport</option>;
                <option value='Naked'>Naked</option>;
                <option value='Cross'>Cross</option>;
                <option value='Trial'>Trial</option>;
            </select><br><br>

            Country<br><select name="country" onchange="getCityCombobox(this.value, 'cityCombobox');">
                <option value= 0 selected="selected">All</option>
                <?php
                $country_query = oci_parse($connection, 'SELECT * FROM COUNTRY');
                oci_execute($country_query);

                while ($row = oci_fetch_array($country_query,OCI_ASSOC+OCI_RETURN_NULLS)) {
                    echo "<option value='".$row['ID']."'>".$row['NAME']."</option>";
                }
                ?>
            </select><br><br>

            <div id="cityCombobox"></div>

            <div id="countyCombobox"></div>

            <input type="submit" name="list_motorcycles" value="List Motorcycles">
        </form>
    </center>
</div>

<div class="vehicle_list">

    <?php

        $motorcycle_filter = true;
        $max_value = 9999999999;

        if($_POST["max_amount"])
            $max_amount = $_POST["max_amount"];
        else
            $max_amount = $max_value;
        if($_POST["min_amount"])
            $min_amount = $_POST["min_amount"];
        else
            $min_amount = -1 * $max_value;

        if($_POST["max_year"])
            $max_year = $_POST["max_year"];
        else
            $max_year =  $max_value;
        if($_POST["min_year"])
            $min_year = $_POST["min_year"];
        else
            $min_year = -1 * $max_value;

        if($_POST["max_km"])
            $max_km = $_POST["max_km"];
        else
            $max_km =  $max_value;
        if($_POST["min_km"])
            $min_km = $_POST["min_km"];
        else
            $min_km = -1 * $max_value;

        if($_POST["fuel_type"])
            $fuel_type = $_POST["fuel_type"];
        else
            $fuel_type = '%';

        if($_POST["motorcycle_type"])
            $motorcycle_type = $_POST["motorcycle_type"];
        else
            $motorcycle_type = '%';

        if($_POST["gear"] > 0){
            $temp = $_POST["gear"];
            $gear_condition = "m.GEAR_ID = $temp";
        }
        else
            $gear_condition = "m.GEAR_ID > 0";

        if($_POST["country"] > 0){
            $temp = $_POST["country"];
            $country_condition = "city.COUNTRY_ID = $temp";
        }
        else
            $country_condition = "city.COUNTRY_ID > 0";

        if($_POST["city"] > 0){
            $temp = $_POST["city"];
            $city_condition = "county.CITY_ID = $temp";
        }
        else
            $city_condition = "county.CITY_ID > 0";

        if($_POST["county"] > 0){
            $temp = $_POST["county"];
            $county_condition = "br.COUNTY_ID = $temp";
        }
        else
            $county_condition = "br.COUNTY_ID > 0";


    include "../vehicle_list.php";
    ?>

</div>
