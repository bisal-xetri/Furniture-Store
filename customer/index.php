<?php include('include/header.php');

if (!isset($_SESSION['username'])) {
  header('location:../user-login.php');
}
?>



<div class="account-info">

  Account information

</div>
<div class="user-information">
  <?php include('include/sidebar.php'); ?>
  <div class="user-detail-info">
    <h1>My Account</h1>
    <div><a class="user-link" href="orders.php">ORDERS</a></div>
    <p>Check the status and information regarding your online orders</p>

    <div><a class="user-link" href="personal-detail.php">PERSONAL DETAILS</a></div>
    <p>You can access and modify your personal details (name, billing address, telephone number, etc.) in order to speed up your future purchases and notify us of changes in your contact details.</p>

    <div><a class="user-link" href="access-detail.php">MANAGE ACCOUNT</a></div>
    <p>You can change your access details (password ).</p>
  </div>
</div>


<?php include('include/footer.php') ?>