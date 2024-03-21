<?php include('partials/menu.php') ?>
<div class="main-content">
    <div class="wrapper">
        <h1>DASHBOARD</h1>
        <br />
        <?php
        if (isset($_SESSION['login'])) {
            echo $_SESSION['login'];
            unset($_SESSION['login']);
        }
        ?>
        <br />


        <div class="col-4">
            <?php
            $sql = "SELECT * FROM tbl_category";
            $res = mysqli_query($con, $sql);

            // Check if the query was successful
            if ($res) {
                $count = mysqli_num_rows($res);
            ?>
                <h1><?php echo $count; ?></h1>
                <br>
                Categories
            <?php
            } else {
                // Handle the query error
                echo "Error: " . mysqli_error($con);
            }
            ?>
        </div>

        <div class="col-4">
            <?php
            $sql2 = "SELECT * FROM tbl_furniture";
            $res2 = mysqli_query($con, $sql2);

            // Check if the query was successful
            if ($res2) {
                $count2 = mysqli_num_rows($res2);
            ?>
                <h1><?php echo $count2; ?></h1>
                <br>
                Furnitures
            <?php
            } else {
                // Handle the query error
                echo "Error: " . mysqli_error($con);
            }
            ?>
        </div>
        <div class="col-4">
            <?php
            $sql3 = "SELECT * FROM tbl_order";
            $res3 = mysqli_query($con, $sql3);

            // Check if the query was successful
            if ($res3) {
                $count3 = mysqli_num_rows($res3);
            ?>
                <h1><?php echo $count3; ?></h1>
                <br>
                Total Order
            <?php
            } else {
                // Handle the query error
                echo "Error: " . mysqli_error($con);
            }
            ?>
        </div>
        <div class="col-4">
            <?php
            $sql4 = "SELECT SUM(total) as Total FROM tbl_order where status ='Delivered'";
            $res4 = mysqli_query($con, $sql4);
            $row4 = mysqli_fetch_assoc($res4);
            $total_revenue = $row4['Total'];

            ?>
            <h1>Rs.<?php echo $total_revenue; ?></h1>
            <br>
            Revenue Generation
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<?php
// Close the database connection
mysqli_close($con);

// Include footer.php
include('partials/footer.php');
?>