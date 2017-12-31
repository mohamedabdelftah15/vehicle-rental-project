<?php
include "../common.php";
include "../connection.php";
include "../authentication/login_required.php";
?>

<div class="left_menu">
    <center>
        <form action="truck_filter.php" method="post">
            Amount<br><input type="number" name="min_amount" style="width: 100px;"> - <input type="number" name="max_amount"style="width: 100px;"><br><br>
            Year<br><input type="number" name="min_year" style="width: 100px;"> - <input type="number" name="max_year"style="width: 100px;"><br><br>
            Kilometer<br><input type="number" name="min_km" style="width: 100px;"> - <input type="number" name="max_km"style="width: 100px;"><br><br>

            Fuel Type<br><select name="fuel_type">
                <option value= '%' selected="selected">All</option>
                <option value='Diesel'>Diesel</option>
                <option value='Hybrid'>Hybrid</option>
                <option value='Gasoline'>Gasoline</option>
                <option value='Electric'>Electric</option>
                <option value='Gasoline + LPG'>Gasoline + LPG</option>
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

            Trailer-Type <br>
            <select name="trailer_type" required>
                <option value='%'>All</option>";
                <option value='OTHER'>OTHER</option>";
                <option value='Lowboy'>Lowboy</option>";
                <option value='Side Kit'>Side Kit</option>";
                <option value='Flat Bed'>Flat Bed</option>";
                <option value='Conestoga'>Conestoga</option>";
                <option value='Step Deck'>Step Deck</option>";
                <option value='Power Only'>Power Only</option>";
                <option value='Dry Van (Enclosed)'>Dry Van (Enclosed)</option>";
                <option value='Refrigerated (Reefer)'>Refrigerated (Reefer)</option>";
                <option value='RGN (Removable Gooseneck)'>RGN (Removable Gooseneck)</option>";
            </select><br><br>

            Trailer Volume<br><input type="number" name="min_trailer_volume" style="width: 100px;"> - <input type="number" name="max_trailer_volume"style="width: 100px;"><br><br>
            Bale Capacity<br><input type="number" name="min_bale_capacity" style="width: 100px;"> - <input type="number" name="max_bale_capacity"style="width: 100px;"><br><br>

            <input type="submit" name="list_trucks" value="List Trucks">
        </form>
    </center>
</div>

<div class="vehicle_list">

    <?php
    if (isset($_POST['list_trucks'])) {

        $truck_filter = true;
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

        if($_POST["max_balec_apacity"])
            $max_bale_capacity = $_POST["max_bale_capacity"];
        else
            $max_bale_capacity =  $max_value;
        if($_POST["min_bale_capacity"])
            $min_bale_capacity = $_POST["min_bale_capacity"];
        else
            $min_bale_capacity = -1 * $max_value;

        if($_POST["max_trailer_volume"])
            $max_trailer_volume = $_POST["max_trailer_volume"];
        else
            $max_trailer_volume =  $max_value;
        if($_POST["min_trailer_volume"])
            $min_trailer_volume = $_POST["min_trailer_volume"];
        else
            $min_trailer_volume = -1 * $max_value;

        $fuel_type = $_POST["fuel_type"];
        $trailer_type = $_POST["trailer_type"];

        if($_POST["gear"] > 0){
            $temp = $_POST["gear"];
            $gear_condition = "m.GEAR_ID = $temp";
        }
        else
            $gear_condition = "m.GEAR_ID > 0";

    }
    include "../vehicle_list.php";
    ?>

</div>
