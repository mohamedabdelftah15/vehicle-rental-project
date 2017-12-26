<?php
include "common.php";
?>

<center>
    <h1>Statistics</h1> <br>

    <table class="statistic-table">
        <tr>
            <th>The most rented three vehicles (count)</th>
            <th>The most rented three vehicles (duration)</th>
        </tr>
        <tr>
            <td>
                <?php
                $query = oci_parse(
                        $connection,
                        'SELECT * FROM VEHICLE_RENT_COUNTS WHERE ROWNUM <= 3'
                );
                oci_execute($query);

                while ($row = oci_fetch_array($query, OCI_ASSOC + OCI_RETURN_NULLS)) {
                    $vehicle_id = $row['VEHICLE_ID'];
                    echo "<a class='detail-link' href='vehicle_detail.php?Id=$vehicle_id'>
                            Plate: ".$row['PLATE']." | Rent-Count: ".$row['RENT_COUNT']."
                          </a><br>";
                }
                ?>
            </td>

            <td>
                <?php
                $query = oci_parse(
                    $connection,
                    'SELECT * FROM VEHICLE_RENT_DURATIONS WHERE ROWNUM <= 3'
                );
                oci_execute($query);

                while ($row = oci_fetch_array($query, OCI_ASSOC + OCI_RETURN_NULLS)) {
                    $vehicle_id = $row['VEHICLE_ID'];
                    echo "<a class='detail-link' href='vehicle_detail.php?Id=$vehicle_id'>
                            Plate: ".$row['PLATE']." | Rent-Duration (days): ".$row['RENT_DURATION']."
                          </a><br>";
                }
                ?>
            </td>
        </tr>
    </table>

    <table class="statistic-table">
        <tr>
            <th>Three vehicles with the oldest production</th>
            <th>Three vehicles with the longest mileage</th>
        </tr>
        <tr>
            <td>
                <?php
                $query = oci_parse(
                    $connection,
                    'SELECT * FROM VEHICLE WHERE ROWNUM <= 3 ORDER BY YEAR ASC '
                );
                oci_execute($query);

                while ($row = oci_fetch_array($query, OCI_ASSOC + OCI_RETURN_NULLS)) {
                    $vehicle_id = $row['ID'];
                    echo "<a class='detail-link' href='vehicle_detail.php?Id=$vehicle_id'>
                            Plate: ".$row['PLATE']." | Production Year: ".$row['YEAR']."
                          </a><br>";
                }
                ?>
            </td>

            <td>
                <?php
                $query = oci_parse(
                    $connection,
                    'SELECT * FROM VEHICLE WHERE ROWNUM <= 3 ORDER BY KILOMETER DESC'
                );
                oci_execute($query);

                while ($row = oci_fetch_array($query, OCI_ASSOC + OCI_RETURN_NULLS)) {
                    $vehicle_id = $row['ID'];
                    echo "<a class='detail-link' href='vehicle_detail.php?Id=$vehicle_id'>
                            Plate: ".$row['PLATE']." | Mileage (km): ".$row['KILOMETER']."
                          </a><br>";
                }
                ?>
            </td>
        </tr>
    </table>

    <table class="statistic-table">
        <tr>
            <th>Vehicle with the highest price</th>
            <th>Vehicle with the lowest price</th>
        </tr>
        <tr>
            <td>
                <?php
                $query = oci_parse(
                    $connection,
                    "SELECT * FROM MOST_EXPENSIVE_VEHICLE"
                );
                oci_execute($query);

                while ($row = oci_fetch_array($query, OCI_ASSOC + OCI_RETURN_NULLS)) {
                    $vehicle_id = $row['VEHICLE_ID'];
                    echo "<a class='detail-link' href='vehicle_detail.php?Id=$vehicle_id'>
                            Plate: ".$row['PLATE']." | Price: ".$row['PRICE']."
                          </a><br>";
                }
                ?>
            </td>
            <td>
                <?php
                $query = oci_parse(
                    $connection,
                    "SELECT * FROM CHEAPEST_VEHICLE"
                );
                oci_execute($query);

                while ($row = oci_fetch_array($query, OCI_ASSOC + OCI_RETURN_NULLS)) {
                    $vehicle_id = $row['VEHICLE_ID'];
                    echo "<a class='detail-link' href='vehicle_detail.php?Id=$vehicle_id'>
                            Plate: ".$row['PLATE']." | Price: ".$row['PRICE']."
                          </a><br>";
                }
                ?>
            </td>
        </tr>
    </table>

    <table class="statistic-table">
        <tr>
            <th>Branch with the highest average vehicle price</th>
            <th>Branch with the lowest average vehicle price</th>
        </tr>
        <tr>
            <td>
                <?php
                $query = oci_parse(
                    $connection,
                    "SELECT * FROM BRANCH_AVG_PRICES ORDER BY AVG_PRICE DESC"
                );
                oci_execute($query);

                $row = oci_fetch_array($query, OCI_ASSOC + OCI_RETURN_NULLS);
                echo "Branch: " . $row['BRANCH_NAME'] . " | Average-Price: " . $row['AVG_PRICE'];
                ?>
            </td>
            <td>
                <?php
                $query = oci_parse(
                    $connection,
                    "SELECT * FROM BRANCH_AVG_PRICES ORDER BY AVG_PRICE ASC"
                );
                oci_execute($query);

                $row = oci_fetch_array($query, OCI_ASSOC + OCI_RETURN_NULLS);
                echo "Branch: " . $row['BRANCH_NAME'] . " | Average-Price: " . $row['AVG_PRICE'];
                ?>
            </td>
        </tr>
    </table>

    <table class="statistic-table">
        <tr>
            <th>Most preferred brand for each country</th>
            <th>Most preferred fuel type for each country</th>
        </tr>
        <tr>
            <td>
                <?php
                $query = oci_parse(
                    $connection,
                    "SELECT * FROM COUNTRY_PREFERRED_BRAND"
                );
                oci_execute($query);

                while ($row = oci_fetch_array($query, OCI_ASSOC + OCI_RETURN_NULLS)) {
                    echo "Country: " . $row['COUNTRY_NAME'] . " | Most-Preferred Brand: " . $row['BRAND_NAME'] . "<br>";
                }
                ?>
            </td>
            <td>
                <?php
                $query = oci_parse(
                    $connection,
                    "SELECT * FROM COUNTRY_PREFFERED_FUEL"
                );
                oci_execute($query);

                while ($row = oci_fetch_array($query, OCI_ASSOC + OCI_RETURN_NULLS)) {
                    echo "Country: " . $row['COUNTRY_NAME'] . " | Most-Preferred Fuel: " . $row['FUEL_TYPE'] . "<br>";
                }
                ?>
            </td>
        </tr>
    </table>

</center>