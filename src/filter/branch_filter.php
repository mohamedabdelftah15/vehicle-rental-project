<?php
include "../common.php";
include "../connection.php";

$country =  null;
$city = null;
$county = null;
$branch = null;

if($_GET['Branch']){
    $country =  $_GET['Country'];
    $city = $_GET['City'];
    $county = $_GET['County'];
    $branch = $_GET['Branch'];
}
?>

<h3>Şubeler</h3>


<div class="left_menu">
    <?php

        $country_query = oci_parse($connection, "SELECT * FROM COUNTRY");
        oci_execute($country_query);

        $i = 0;
        $j = 0;
        $k = 0;
        while ($row = oci_fetch_array($country_query,OCI_ASSOC+OCI_RETURN_NULLS)) {

            $i++;
            $j = 0;
            $country_id = $row['ID'];
            echo "<a href='javascript:setView($i,1);'>".$row['NAME']."</a><br>";

            $city_query = oci_parse($connection, "SELECT * FROM CITY WHERE COUNTRY_ID = $country_id");
            oci_execute($city_query);

            while ($row = oci_fetch_array($city_query,OCI_ASSOC+OCI_RETURN_NULLS)) {

                $j++;
                $k = 0;
                $city_id = $row['ID'];

                if($country == $country_id){
                    echo "<a href='javascript:setView($i$j,1);' class='$i' style='display: block'>&emsp;".$row['NAME']."</a>";
                }
                else{
                    echo "<a href='javascript:setView($i$j,1);' class='$i' style='display: none'>&emsp;".$row['NAME']."</a>";
                }

                $county_query = oci_parse($connection, "SELECT * FROM COUNTY WHERE CITY_ID = $city_id");
                oci_execute($county_query);

                while ($row = oci_fetch_array($county_query,OCI_ASSOC+OCI_RETURN_NULLS)) {

                    $k++;
                    $county_id = $row['ID'];

                    if($country == $country_id && $city == $city_id){
                        echo "<a href='javascript:setView($i$j$k,1);' class='$i$j' style='display: block'>&emsp;&emsp;".$row['NAME']."</a>";
                    }
                    else{
                        echo "<a href='javascript:setView($i$j$k,1);' class='$i$j' style='display: none'>&emsp;&emsp;".$row['NAME']."</a>";
                    }

                    $branch_query = oci_parse($connection, "SELECT * FROM BRANCH WHERE COUNTY_ID = $county_id");
                    oci_execute($branch_query);

                    while ($row = oci_fetch_array($branch_query,OCI_ASSOC+OCI_RETURN_NULLS)) {

                        $branch_id = $row['ID'];

                        if($country == $country_id && $city == $city_id && $county == $county_id){
                            echo "<a href='?Country=$country_id&City=$city_id&County=$county_id&Branch=$branch_id' class='$i$j$k' style='display: block'>&emsp;&emsp;&emsp;".$row['NAME']."</a>";
                        }
                        else{
                            echo "<a href='?Country=$country_id&City=$city_id&County=$county_id&Branch=$branch_id' class='$i$j$k' style='display: none'>&emsp;&emsp;&emsp;".$row['NAME']."</a>";
                        }
                    }
                }
            }
        }
    ?>
</div>

<div class="vehicle_list">

    <?php
    include "fetch_vehicle_list.php";
    ?>

</div>


