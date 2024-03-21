<?php include('include/header.php');

if (!isset($_SESSION['username'])) {
  header('location:../user-login.php');
  exit(); // Add exit() after redirect to prevent further code execution
}

?>

<div class="account-info">
  Order information
</div>

<div class="user-information">
  <?php include('include/sidebar.php'); ?>

  <div class="user-detail-info">
    <h1>My Orders</h1>

    <?php
    $customer_id = $_SESSION['id'];


    $order_query = "SELECT * FROM tbl_order WHERE customer_id = $customer_id";

    $run = mysqli_query($con, $order_query);

    if (!$run) {
      die("Query failed: " . mysqli_error($con));
    }

    if (mysqli_num_rows($run) > 0) {
      if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
      }
    ?>
      <table class="order-table">
        <th>Product Image</th>
        <th>Product Name</th>
        <th>Product Quantity</th>
        <th>Total Price</th>
        <th>Ordered Date</th>
        <th>Status</th>

        <?php
        while ($order_row = mysqli_fetch_array($run)) {
          $order_pro_id  = $order_row['product_id'];
          $order_qty     = $order_row['qty'];
          $order_amount  = $order_row['total'];
          $order_date    = $order_row['order_date'];
          $order_status  = $order_row['status'];

          $pro_query  = "SELECT * FROM tbl_furniture WHERE id = $order_pro_id";
          $pro_run    = mysqli_query($con, $pro_query);

          if (!$pro_run) {
            die("Product Query failed: " . mysqli_error($con));
          }

          if (mysqli_num_rows($pro_run) > 0) {
            while ($pr_row = mysqli_fetch_array($pro_run)) {
              $title = $pr_row['title'];
              $img1 = $pr_row['image_name'];
        ?>
              <tr>
                <td>
                  <img class="order-img" src="../Image/furniture/<?php echo $img1; ?>">
                </td>
                <td>
                  <h4><?php echo $title; ?></h4>
                </td>
                <td>
                  x <?php echo $order_qty; ?>
                </td>
                <td><?php echo $order_amount; ?> </td>
                <td><?php echo $order_date; ?></td>
                <td><?php
                    if ($order_status == 'On Delivery' || $order_status == 'Delivered' || $order_status == 'Canceled' || $order_status == 'Ordered') {
                      echo $order_status;
                    }
                    ?> </td>
              </tr>
        <?php
            }
          }
        }
        ?>
      </table>
    <?php
    } else {
      echo "<h2>You haven't ordered anything yet </h2>";
    }
    ?>
  </div>
</div>

<?php include('include/footer.php') ?>