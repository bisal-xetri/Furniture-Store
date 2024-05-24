<?php include('config/constant.php'); ?>
<?php include('partial-front/menu.php'); ?>
<link rel="stylesheet" href="CSS/cart.css">

<!-- <div class="jumbotron b">
  <h2 class="text-center mt-5">Cart</h2>
</div> -->

<?php
if (isset($_SESSION['username'])) {
  $customer_id = $_SESSION['id'];

  //Delete Query
  if (isset($_GET['id'])) {

    $proid = $_GET['id'];

    $del_query = "DELETE FROM cart WHERE product_id = $proid and user_id = $customer_id";
    if (mysqli_query($con, $del_query)) {
      header("location:cart.php");
    }
  }
  //end delete Query
  //cart Query
  $cart = "SELECT * FROM cart WHERE user_id='$customer_id'";
  $run  = mysqli_query($con, $cart);


  $sub_total = 0;
  $shipping_cost = 0;
  $total = 0;
  if (mysqli_num_rows($run) > 0) {
?>

    <div class="cart-detail">
      <div class="cart-item">
        <h1>Shopping Cart</h1>
        <table class="cart-title">
          <th>
            Product Details
          </th>
          <th></th>
          <th> Quantity</th>
          <th>Price</th>
          <th>Total</th>
          <th>Actions</th>

          <?php


          while ($cart_row = mysqli_fetch_array($run)) {
            $db_cust_id = $cart_row['user_id'];
            $db_pro_id  = $cart_row['product_id'];
            $db_pro_qty  = $cart_row['quantity'];

            $pr_query = "SELECT * FROM tbl_furniture WHERE id=$db_pro_id";
            $pr_run   = mysqli_query($con, $pr_query);

            if (mysqli_num_rows($pr_run) > 0) {
              while ($pr_row = mysqli_fetch_array($pr_run)) {
                $id = $pr_row['id'];
                $title = $pr_row['title'];
                $price = $pr_row['price'];
                $arrPrice = array($pr_row['price']);
                // $size = $pr_row['size'];
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
                    <h3><?php echo $title; ?></h3>
                    <!-- <p> Dimension:</p> -->
                  </td>
                  <td>
                    x <?php echo $db_pro_qty; ?>
                  </td>
                  <td><?php echo $pr_row['price']; ?></td>

                  <td><?php echo $single_pro_total_price; ?> </td>

                  <td colspan="2">
                    <a class="cart-update" href="edit_cart.php?cart_id=<?php echo $id; ?> ">Update</a>
                    <a class="cart-delete" href="cart.php?id=<?php echo $id; ?> ">Delete</a>
                  </td>
                </tr>

          <?php
              }
            } else {
              echo "<h2 style='text-align:center; margin:auto' class='cart-msg'>Your Cart is Empty</h2>";
            }
          }


          ?>

        </table>
      </div>
      <!--end cart--->

      <!--order Detail-->
      <div class="order-detail">
        <h1 class="order-detail-title">Order Detail</h1>
        <p>Subtotal:<?php echo $sub_total; ?></p>
        <p>Discount:Rs0</p>
        <p>Shipping:<?php echo $shipping_cost; ?></p><hr/>
        <h2>Total:Rs.<?php echo $total; ?></h2>
      </div>
      <!--end order--->
    </div>


    <div class="cart-button">
      <a class="continue-btn" href="furniture-items.php">
    Continue Shopping
      </a>
      <a href="checkout.php" class="proceed-btn">
        Proceed Checkout
      </a>
    </div>
<?php } else {
    echo "<h2 style='text-align:center; margin:auto;font-size:25px'>Your Cart is Empty</h2>";
  }
} else {
  echo " <h2 style='text-align:center; font-size:25px'> Your Cart is Empty</h2>";
}
?>




<?php include('partial-front/footer.php'); ?>