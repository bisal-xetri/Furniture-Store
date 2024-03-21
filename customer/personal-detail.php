<?php
include('include/header.php');

if (!isset($_SESSION['username'])) {
  header('location:../user-login.php');
}

if (isset($_SESSION['username'])) {

  $customer_id    = $_SESSION['id'];

  $query = "SELECT * FROM tbl_user WHERE id=$customer_id";
  $run   = mysqli_query($con, $query);

  if (!$run) {
    die('Error in SQL query: ' . mysqli_error($con));
  }

  $row = mysqli_fetch_assoc($run);

  $cust_name = $row['full_name'];
  $cust_email = $row['email'];
  $cust_add = $row['address'];
  $cust_number = $row['phone'];

  if (isset($_POST['update'])) {

    $fullname = $_POST['fullname'];
    echo $email    = $_POST['email'];
    $address  = $_POST['address'];
    $number   = $_POST['phone_number'];

    $up_query = "UPDATE `tbl_user` SET `full_name`='$fullname',
     `address`='$address', `phone`='$number'
       WHERE id=$customer_id ";
    $update_run = mysqli_query($con, $up_query);

    if (!$update_run) {
      die('Error in UPDATE query: ' . mysqli_error($con));
    }

    $_SESSION['msg'] = "<div style='color:green; text-align:center'>Update Successfully</div>";

    header('location:personal-detail.php');
  }
}
?>








<div class="account-info">

  Personal information

</div>
<!-- <?php
      if (isset($_SESSION['login'])) {
        echo $_SESSION['login'];
        unset($_SESSION['login']);
      }
      ?> -->
<div class="user-information">
  <?php include('include/sidebar.php'); ?>


  <div class="user-detail-info">
    <h2>CHANGE PERSONAL DETAILS</h2></hr>
    <p>You can access and modify your personal details (name, billing address, telephone number, etc.)
      in order to facilitate your future
      purchases and to notify us of any change in your contact details.</p>

    <?php

    if (isset($_SESSION['msg'])) {
      echo $_SESSION['msg'];
    }
    ?>

    <form action="" class="personal-detail-form" method="post">
      <input type="text" name="fullname" placeholder="Full Name" value="<?php echo $cust_name; ?>" class="form-control"><br><br>
      <input type="email" name="email" placeholder="Email" class="form-control" value="<?php echo $cust_email; ?>" disabled><br><br>
      <input type="text" name="address" placeholder="Address" value="<?php echo $cust_add; ?>" class="form-control"><br><br>
      <input type="number" name="phone_number" placeholder="Phone Number" value="<?php echo $cust_number; ?>" class="form-control"><br><br>
      <input type="submit" name="update" class="btn-update" value="Update">
    </form>
  </div>
</div>

<?php include('include/footer.php'); ?>