<?php include('config/constant.php'); ?>
<?php include('partial-front/menu.php'); ?>
<link rel="stylesheet" href="CSS/cart.css">

<!-- <div class="jumbotron b">
  <h2 class="text-center mt-5">Cart</h2>
</div> -->

<?php
if (isset($_SESSION['id'])) {
    $customer_id = $_SESSION['id'];

    if (isset($_GET['cart_id'])) {
        $edit_cart = $_GET['cart_id'];

        //update Query
        if (isset($_POST['update'])) {

            $qty = $_POST['Qty'];

            $up_query = "UPDATE cart SET quantity=$qty WHERE product_id = $edit_cart AND user_id = $customer_id";

            $run = mysqli_query($con, $up_query);
        }
        //end update Query

        //cart Query
        $cart = "SELECT * FROM cart WHERE user_id='$customer_id' and product_id ='$edit_cart'";
        $run  = mysqli_query($con, $cart);


        $sub_total = 0;
        $shipping_cost = 0;
        $total = 0;

?>

        <div class="cart-detail">

            <div class="cart-item">
                <form method="post">
                    <h1>Shopping Cart</h1>

                    <table class="cart-title">
                        <th>
                            Product Details
                        </th>
                        <th></th>
                        <th> Quantity</th>
                        <th>Price</th>
                        <th>Total</th>


                        <?php

                        if (mysqli_num_rows($run) > 0) {
                            while ($cart_row = mysqli_fetch_array($run)) {
                                $db_cust_id = $cart_row['user_id'];
                                $db_pro_id  = $cart_row['product_id'];
                                $db_pro_qty  = $cart_row['quantity'];

                                $pr_query = "SELECT * FROM tbl_furniture WHERE id=$db_pro_id";
                                $pr_run   = mysqli_query($con, $pr_query);

                                if (mysqli_num_rows($pr_run) > 0) {
                                    $pr_row = mysqli_fetch_array($pr_run);

                                    $pid = $pr_row['id'];
                                    $title = $pr_row['title'];
                                    $price = $pr_row['price'];
                                    $arrPrice = array($pr_row['price']);
                                    //$size = $pr_row['size'];
                                    $img1 = $pr_row['image_name'];

                                    $single_pro_total_price = $db_pro_qty * $price;
                                    $pro_total_price = array($db_pro_qty * $price);

                                    //   $values = array_sum($arrPrice);
                                    $shipping_cost = 0;
                                    $values = array_sum($pro_total_price);
                                    $sub_total += $values;
                                    $total = $sub_total + $shipping_cost;

                        ?>
                                    <tr>
                                        <td>
                                            <img class="cart-image" src="<?php echo SITEURL; ?>Image/furniture/<?php echo $img1; ?> " alt="">
                                        </td>
                                        <td>
                                            <h4><?php echo $title; ?></h4>

                                        </td>
                                        <td>
                                            <input class='cart-quantity-update' type="number" min="1" max="10" name="Qty" value="<?php echo $db_pro_qty; ?>">
                                        </td>
                                        <td>Rs.<?php echo $pr_row['price']; ?></td>

                                        <td>Rs.<?php echo $single_pro_total_price; ?> </td>

                                    </tr>
                        <?php


                                }
                            }
                        }



                        ?>

                    </table>
            </div>
            <!--end cart--->

            <!--order Detail-->
            <div class="order-detail">
                <h1 class="order-detail-title">Order Detail</h1>
                <p>Subtotal:Rs.<?php echo $sub_total; ?></p>
                <p>Discount:Rs0</p>
                <p>Shipping:Rs.<?php echo $shipping_cost; ?></p>
                <h3>Total:Rs.<?php echo $total; ?></h3>
            </div>
            <!--end order--->
        </div>


        <div class="cart-button">

            <input type="submit" name="update" class="cart-update" value="update">
            <a class="cart-update" href="cart.php"> Go to Cart</a>
        </div>
        </form>

<?php  }
}
?>




<?php include('partial-front/footer.php'); ?>