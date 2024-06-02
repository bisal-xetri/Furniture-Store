<?php include('config/constant.php'); ?>
<?php include('partial-front/menu.php'); ?>
<?php
// Start or resume the session

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    // Check if 'id' session variable is set
    if (isset($_SESSION['id'])) {
        $custid = $_SESSION['id'];

        if (isset($_GET['cart_id'])) {
            $p_id = $_GET['cart_id'];

            $sel_cart = "SELECT * FROM cart WHERE user_id = $custid AND product_id = $p_id";
            $run_cart = mysqli_query($con, $sel_cart);

            if ($run_cart) {
                if (mysqli_num_rows($run_cart) == 0) {
                    $cart_query = "INSERT INTO `cart`(`user_id`, `product_id`,quantity) VALUES ($custid,$p_id,1)";
                    if (mysqli_query($con, $cart_query)) {
                        header('location:furniture-items.php');
                        exit; // Exit after redirection
                    }
                } else {
                    while ($row = mysqli_fetch_array($run_cart)) {
                        $exist_pro_id = $row['product_id'];
                        if ($p_id == $exist_pro_id) {
                            echo "<script> alert('⚠️ This product is already in your cart');</script>";
                        }
                    }
                }
            } else {
                // Handle query execution failure
                echo "Error executing query: " . mysqli_error($con);
            }
        }
    } else {
        // Handle 'id' session variable not being set
        echo "Warning: 'id' session variable is not set";
    }
} else {
    echo "<script>function a(event){event.preventDefault();alert('⚠️ Login is required to add this product into cart');}</script>";
}
?>

<div class="chair-type">
    <?php
    $sql2 = "SELECT * FROM tbl_furniture WHERE  active='Yes' AND Featured='Yes' ";
    $res2 = mysqli_query($con, $sql2);
    $count2 = mysqli_num_rows($res2);
    if ($count2 > 0) {
        while ($row = mysqli_fetch_assoc($res2)) {
            $id = $row['id'];
            $title = $row['title'];
            $price = $row['price'];
            $image_name = $row['image_name'];
    ?>
            <div class="chair-info" data-aos="zoom-in" data-aos-delay="100">
                <div class="chair-picture">
                    <?php
                    //check img available
                    if ($image_name == '') {
                        echo "<div class='error'>Image not available</div>";
                    } else {
                    ?>
                        <a href="<?php echo SITEURL; ?>furniture-detail.php?image_id=<?php echo $id; ?>">
                            <img src="<?php echo SITEURL; ?>Image/furniture/<?php echo $image_name; ?>" alt="" />
                        </a>
                    <?php
                    }

                    ?>

                </div>
                <div class="chair-description">
                    <p class="chair-name"><?php echo $title; ?></p>
                    <p class="chair-price">Rs.<?php echo $price; ?></p>

                    <a href="furniture-items.php?cart_id=<?php echo $id; ?>" class="add-to-cart js-add-to-cart" onclick="a(event)"><i class="ri-shopping-cart-2-fill"></i>add to cart</a>
                </div>
            </div>
    <?php
        }
    } else {
        echo "<div class='error'> Furniture Not available</div>";
    }
    ?>


</div>

<?php include('partial-front/footer.php'); ?>