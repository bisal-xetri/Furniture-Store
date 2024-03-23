<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Himalayan Furniture</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="CSS/style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <link rel="stylesheet" href="CSS/furniture-list.css">
    <link rel="stylesheet" href="CSS/chair.css">

    <link rel="stylesheet" href="CSS/furniture-details.css">
    <link rel="stylesheet" href="CSS/checkout.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

</head>
<?php
// Assuming $con is your database connection
if (isset($_SESSION['username'])) {
    $cust_id = $_SESSION['id'];
    $query = "SELECT * FROM cart WHERE user_id=$cust_id";
    $run = mysqli_query($con, $query);

    if ($run) {
        $count = mysqli_num_rows($run);
    } else {
        // Handle query execution failure
        echo "Error: " . mysqli_error($con);
        $count = 0;
    }
} else {
    $count = 0;
}
?>


<body>

    <!-- <div class="loader ldr">
        <h1>WELCOME</h1>
        <h1>TO</h1>
        <h1>THE</h1>
        <h1>HIMALAYAN</h1>
        <h1>FURNITURE</h1>
    </div> -->
    <div class="header">
        <div class="title">
            <h1><a href="<?php echo SITEURL; ?>">Himlayan Furniture</a></h1>
        </div>

        <form class="search" action="<?php echo SITEURL; ?>furniture-search.php" method="POST">
            <input class="search-box" name="search" type="search" placeholder="Find everything for your home" required />
            <button class="search-button" type="submit" name="submit">
                <img class="search-icon" src="Image/searchicon.png" alt="" />
            </button>
        </form>

        <div class="navbar">
            <li><a href="<?php echo SITEURL; ?>">Home</a></li>
            <li><a href="<?php echo SITEURL; ?>products.php">Products</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="<?php echo SITEURL; ?>contact-us.php">Contact Us</a></li>
            <?php if (!isset($_SESSION['username'])) {


            ?>
                <li><a href="<?php echo SITEURL; ?>user-signup.php">Sign In</a></li>
                <li><a href="<?php echo SITEURL; ?>user-login.php">Log in</a></li>
            <?php
            }

            if (isset($_SESSION['username'])) {


            ?>
                <li><a href="<?php echo SITEURL; ?>customer/index.php">Account</a></li>
            <?php }
            ?>
        </div>
        <a href="cart.php" class="trolley">
            <button class="trolley-button">
                <img class="trolley-icon" src="Image/basket.png" alt="" />
                <div class="add-to-cart-count js-cart-quantity"><?php echo $count; ?></div>
            </button>

        </a>
    </div>