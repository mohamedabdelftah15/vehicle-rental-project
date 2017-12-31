<?php
    session_start();
    $username = $_SESSION['username'];
?>

<div class="navbar">
    <div div style="float: left;"><img src="/static/logo.png" style="width: 230px; height: 50px;"></div>
    <a class="<?php if(getPageName() == 'index.php'){ echo 'active';}?>" href="/index.php">Home</a>

    <div class="dropdown">
        <button class="dropbtn1" onclick="drop1func()" style="background-color: <?php if(getPageName() == 'brand_categories.php' || getPageName() == 'branch_categories.php'){ echo '#ff6600';}?>">Categories
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-content" id="categories">
            <a class="<?php if(getPageName() == 'brand_categories.php'){ echo 'active';}?>"href="/categorized/brand_categories.php">Brands</a>
            <a class="<?php if(getPageName() == 'branch_categories.php'){ echo 'active';}?>"href="/categorized/branch_categories.php">Branchs</a>
        </div>
    </div>

    <div class="dropdown">
        <button class="dropbtn2" onclick="drop2func()"
                style="background-color: <?php if(getPageName() == 'car_filter.php' || getPageName() == 'bus_filter.php'
                || getPageName() == 'motorcycle_filter.php' || getPageName() == 'truck_filter.php') { echo '#ff6600';}?>">Filtrations
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-content" id="filters">
            <a class="<?php if(getPageName() == 'car_filter.php'){ echo 'active';}?>"href="/filtration/car_filter.php">Car Filtration</a>
            <a class="<?php if(getPageName() == 'bus_filter.php'){ echo 'active';}?>"href="/filtration/bus_filter.php">Bus Filtration</a>
            <a class="<?php if(getPageName() == 'motorcycle_filter.php'){ echo 'active';}?>"href="/filtration/motorcycle_filter.php">Motorcycle Filtration</a>
            <a class="<?php if(getPageName() == 'truck_filter.php'){ echo 'active';}?>"href="/filtration/truck_filter.php">Truck Filtration</a>
        </div>
    </div>

    <a class="<?php if(getPageName() == 'statistics.php'){ echo 'active';}?>" href="/statistics.php">Statistics</a>


    <!-- Right side -->
    <div style="float: right; padding-top: 13px; padding-right: 20px;">
        <font style="color: white">
            <?php
                if ($username) {
                    echo "<div id='circle-green' style='float: left'></div> &nbsp; $username";
                    echo '<span style="color: white;"> &nbsp; | ';
                    echo '<a class="logout-link" style="padding-top: 0; float: right" href="/authentication/logout.php">Logout</a></span>';
                }
                else {
                    echo "<div id='circle-red' style='float: left'></div> &nbsp;";
                    echo '<span style="color: white;"> &nbsp; | ';
                    echo '<a class="logout-link" style="padding-top: 0; float: right" href="/authentication/login.php">Login</a></span>';
                }
            ?>
        </font>
    </div>

</div>

<div style="height: 30px;">

</div>

<script>
    /* When the user clicks on the button,
    toggle between hiding and showing the dropdown content */
    function drop1func() {
        document.getElementById("categories").classList.toggle("show");
    }

    function drop2func() {
        document.getElementById("filters").classList.toggle("show");
    }

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(e) {
        if (!e.target.matches('.dropbtn1')) {
            var categories = document.getElementById("categories");
            if (categories.classList.contains('show')) {
                categories.classList.remove('show');
            }
        }
        if (!e.target.matches('.dropbtn2')) {
            var filters = document.getElementById("filters");
            if (filters.classList.contains('show')) {
                filters.classList.remove('show');
            }
        }
    }
</script>

