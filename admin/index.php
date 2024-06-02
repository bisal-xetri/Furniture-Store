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

        <div class="col-4 catagories">
            <?php
            $sql = "SELECT * FROM tbl_category";
            $res = mysqli_query($con, $sql);

            // Check if the query was successful
            if ($res) {
                $count = mysqli_num_rows($res);
            ?>
                <h1><?php echo $count; ?></h1>
                <br>
                <span>Categories</span>
            <?php
            } else {
                // Handle the query error
                echo "Error: " . mysqli_error($con);
            }
            ?>
        </div>

        <div class="col-4 Furnitures">
            <?php
            $sql2 = "SELECT * FROM tbl_furniture";
            $res2 = mysqli_query($con, $sql2);

            // Check if the query was successful
            if ($res2) {
                $count2 = mysqli_num_rows($res2);
            ?>
                <h1><?php echo $count2; ?></h1>
                <br>
                <span>Furnitures</span>
            <?php
            } else {
                // Handle the query error
                echo "Error: " . mysqli_error($con);
            }
            ?>
        </div>
        <div class="col-4 orders">
            <?php
            $sql3 = "SELECT * FROM tbl_order";
            $res3 = mysqli_query($con, $sql3);

            // Check if the query was successful
            if ($res3) {
                $count3 = mysqli_num_rows($res3);
            ?>
                <h1><?php echo $count3; ?></h1>
                <br>
                <span>Total Orders</span>
            <?php
            } else {
                // Handle the query error
                echo "Error: " . mysqli_error($con);
            }
            ?>
        </div>
        <div class="col-4 revenue">
            <?php
            $sql4 = "SELECT SUM(total) as Total FROM tbl_order where status ='Delivered'";
            $res4 = mysqli_query($con, $sql4);
            $row4 = mysqli_fetch_assoc($res4);
            $total_revenue = $row4['Total'];

            // Format the total revenue without decimal places
            $formatted_revenue = number_format($total_revenue, 0);

            ?>
            <h1>रू.<?php echo $formatted_revenue; ?></h1>
            <br>
            <span> Revenue Generation</span>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="charts">
        <div class="charts-card">
            <h2 class="chart-title">Top 5 Products</h2>
            <div id="bar-chart"></div>
        </div>

        <div class="charts-card">
            <h2 class="chart-title">Purchase and Sales Orders</h2>
            <div id="area-chart"></div>
        </div>
    </div>
</div>

<?php

// Fetch category and product count data for charts
$sql5 = "
    SELECT c.title, COUNT(f.id) AS product_count 
    FROM tbl_category c
    LEFT JOIN tbl_furniture f ON c.id = f.category_id
    GROUP BY c.title
    ORDER BY product_count DESC
    LIMIT 5
";
$res5 = mysqli_query($con, $sql5);

$categories = [];
$productCounts = [];

while ($row5 = mysqli_fetch_assoc($res5)) {
    $categories[] = $row5['title'];
    $productCounts[] = $row5['product_count'];
}

// Fetch purchase orders data for area chart
$sql6 = "
    SELECT MONTH(order_date) as month, COUNT(*) as order_count
    FROM tbl_order
   
    GROUP BY MONTH(order_date)
";
$res6 = mysqli_query($con, $sql6);

$purchaseOrders = array_fill(0, 12, 0); // Initialize an array with 12 zeroes for each month

while ($row6 = mysqli_fetch_assoc($res6)) {
    $month = $row6['month'];
    $purchaseOrders[$month - 1] = $row6['order_count']; // Months in PHP are 1-indexed, so subtract 1
}

// Fetch sales orders data for area chart
$sql7 = "
    SELECT MONTH(order_date) as month, COUNT(*) as order_count
    FROM tbl_order
    WHERE status = 'Delivered'
    GROUP BY MONTH(order_date)
";
$res7 = mysqli_query($con, $sql7);

$salesOrders = array_fill(0, 12, 0); // Initialize an array with 12 zeroes for each month

while ($row7 = mysqli_fetch_assoc($res7)) {
    $month = $row7['month'];
    $salesOrders[$month - 1] = $row7['order_count']; // Months in PHP are 1-indexed, so subtract 1
}

// Close the database connection
mysqli_close($con);

// Include footer.php
include('partials/footer.php');
?>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Fetch categories and product counts from PHP
        const categories = <?php echo json_encode($categories); ?>;
        const productCounts = <?php echo json_encode($productCounts); ?>;
        const purchaseOrders = <?php echo json_encode($purchaseOrders); ?>;
        const salesOrders = <?php echo json_encode($salesOrders); ?>;

        // Bar Chart Configuration
        const barChartOptions = {
            series: [{
                data: productCounts,
                name: 'Products',
            }],
            chart: {
                type: 'bar',
                background: 'transparent',
                height: 350,
                toolbar: {
                    show: false,
                },
            },
            colors: ['#2962ff', '#d50000', '#2e7d32', '#ff6d00', '#583cb3'],
            plotOptions: {
                bar: {
                    distributed: true,
                    borderRadius: 4,
                    horizontal: false,
                    columnWidth: '40%',
                },
            },
            dataLabels: {
                enabled: false,
            },
            fill: {
                opacity: 1,
            },
            grid: {
                borderColor: '#55596e',
                yaxis: {
                    lines: {
                        show: true,
                    },
                },
                xaxis: {
                    lines: {
                        show: true,
                    },
                },
            },
            legend: {
                labels: {
                    colors: '#f5f7ff',
                },
                show: true,
                position: 'top',
            },
            stroke: {
                colors: ['transparent'],
                show: true,
                width: 2,
            },
            tooltip: {
                shared: true,
                intersect: false,
                theme: 'dark',
            },
            xaxis: {
                categories: categories,
                title: {
                    style: {
                        color: '#f5f7ff',
                    },
                },
                axisBorder: {
                    show: true,
                    color: '#55596e',
                },
                axisTicks: {
                    show: true,
                    color: '#55596e',
                },
                labels: {
                    style: {
                        colors: '#f5f7ff',
                    },
                },
            },
            yaxis: {
                title: {
                    text: 'No of Furniture',
                    style: {
                        color: '#f5f7ff',
                    },
                },
                axisBorder: {
                    color: '#55596e',
                    show: true,
                },
                axisTicks: {
                    color: '#55596e',
                    show: true,
                },
                labels: {
                    style: {
                        colors: '#f5f7ff',
                    },
                },
            },
        };

        const barChart = new ApexCharts(document.querySelector('#bar-chart'), barChartOptions);
        barChart.render();

        // Area Chart Configuration
        const areaChartOptions = {
            series: [{
                    name: 'Purchase Orders',
                    data: purchaseOrders,
                },
                {
                    name: 'Sales Orders',
                    data: salesOrders,
                },
            ],
            chart: {
                type: 'area',
                background: 'transparent',
                height: 350,
                stacked: false,
                toolbar: {
                    show: false,
                },
            },
            colors: ['#00ab57', '#d50000'],
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            dataLabels: {
                enabled: false,
            },
            fill: {
                gradient: {
                    opacityFrom: 0.4,
                    opacityTo: 0.1,
                    shadeIntensity: 1,
                    stops: [0, 100],
                    type: 'vertical',
                },
                type: 'gradient',
            },
            grid: {
                borderColor: '#55596e',
                yaxis: {
                    lines: {
                        show: true,
                    },
                },
                xaxis: {
                    lines: {
                        show: true,
                    },
                },
            },
            legend: {
                labels: {
                    colors: '#f5f7ff',
                },
                show: true,
                position: 'top',
            },
            tooltip: {
                shared: true,
                intersect: false,
                theme: 'dark',
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                title: {
                    style: {
                        color: '#f5f7ff',
                    },
                },
                axisBorder: {
                    show: true,
                    color: '#55596e',
                },
                axisTicks: {
                    show: true,
                    color: '#55596e',
                },
                labels: {
                    style: {
                        colors: '#f5f7ff',
                    },
                },
            },
            yaxis: {
                title: {
                    text: 'Order/Sales',
                    style: {
                        color: '#f5f7ff',
                    },
                },
                axisBorder: {
                    color: '#55596e',
                    show: true,
                },
                axisTicks: {
                    color: '#55596e',
                    show: true,
                },
                labels: {
                    style: {
                        colors: '#f5f7ff',
                    },
                },
            },
        };

        const areaChart = new ApexCharts(document.querySelector('#area-chart'), areaChartOptions);
        areaChart.render();
    });
</script>