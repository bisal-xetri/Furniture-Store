<?php
include('include/header.php');

if (!isset($_SESSION['username'])) {
  header('location:../user-login.php');
}

if (isset($_SESSION['username'])) {
  $customer_id    = $_SESSION['id'];
}
?>

<div class="account-info">
  Change password
</div>

<div class="user-information">
  <?php include('include/sidebar.php'); ?>

  <div class="user-detail-info">
    <h1>Manage Password</h1>

    <div class="col-md-9">

      <h3>CHANGE PASSWORD</h3>
      <p>If you wish to change the password to access your account, please provide
        the following information:</p>


      <?php
      if (isset($_POST['update'])) {
        $old_pass     =sha1( $_POST['old_pass']);
        $new_pass     =sha1( $_POST['new_pass']);
        $confirm_pass =sha1( $_POST['conf_pass']);

        $query = "SELECT password FROM tbl_user Where id=$customer_id";
        $run   = mysqli_query($con, $query);

        if ($run) { // Check if the query was successful
          if (mysqli_num_rows($run) > 0) {
            $row = mysqli_fetch_array($run);
            $cust_pass  = $row['password'];
            if (!empty($old_pass) && !empty($new_pass) && !empty($confirm_pass)) {
              if ($old_pass === $cust_pass) {
                if ($new_pass === $confirm_pass) {

                  $up_query = "UPDATE tbl_user SET password = '$confirm_pass'";

                  if (mysqli_query($con, $up_query)) {
                    $msg = "<div style='color:green; text-align:center'> Congratulation!  your password has been changed.</div>";
                  }
                } else {
                  $error = "<div style='color:red; text-align:center'> Ooh! New password and confirm password must be matched.</div>";
                }
              } else {
                $error = "<div style='color:red; text-align:center'> Ooh! Old password is wrong!.</div>";
              }
            } else {
              $error = "<div style='color:red; text-align:center' > Sorry! All(*) Fields Are Required.</div>";
            }
          } else {
            $error = "<div style='color:red; text-align:center'> No user found with the given user_id.</div>";
          }
        } else {
          $error = "<div style='color:red; text-align:center'> Query failed: " . mysqli_error($con) . "</div>";
        }
      }

      if (isset($msg)) {
        echo $msg;
      } else if (isset($error)) {
        echo $error;
      }
      ?>


      <form action="" method="post">
        <label>Old Password: *</label>
        <input type="text" name="old_pass" placeholder="Old Password" class="form-control">
        <label>New Password: *</label>
        <input type="text" name="new_pass" placeholder="New Password" class="form-control">
        <label>Confirm Password: *</label>
        <input type="text" name="conf_pass" placeholder="Confirm Password" class="form-control">

        <input type="submit" name="update" class="btn-update" value="Update">

      </form>


    </div>
  </div>
</div>

<?php include('include/footer.php'); ?>