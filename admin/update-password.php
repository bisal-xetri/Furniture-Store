 <?php include('partials/menu.php') ?>
 <div class="main-content">
     <div class="wrapper">
         <h1>Change Password</h1>
         </br>
         <?php if (isset($_GET['id'])) {
                $id = $_GET['id'];
            } ?>
         <form action="" method="POST">
             <table class="tbl-30">
                 <tr>
                     <td>Current Password:</td>
                     <td><input class='update-input' type="password" name="current_password" value="" placeholder="current password"></td>
                 </tr>
                 <tr>
                     <td>New Password:</td>
                     <td><input class='update-input' type="password" name="new_password" value="" placeholder="new password"></td>
                 </tr>
                 <tr>
                     <td>Confirm Password:</td>
                     <td><input class='update-input' type="password" name="confirm_password" value="" placeholder="confirm password"></td>
                 </tr>
                 <tr>
                     <td colspan="2">
                         <input type="hidden" name="id" value="<?php echo $id; ?>">
                         <input class="btn-secondary" type="submit" name="submit" value="Change Password">
                     </td>
                 </tr>

             </table>
         </form>
     </div>
 </div>
 <?php
    //check submit button clicked
    if (isset($_POST['submit'])) {
        //get data from form
        $id = $_POST['id'];
        $current_password = sha1($_POST['current_password']);
        $new_password = sha1($_POST['new_password']);
        $confirm_password = sha1($_POST['confirm_password']);
        //check whether with current password and id exist
        $sql = "SELECT * FROM tbl_admin WHERE id=$id AND password='$current_password'";
        //execute the query
        $res = mysqli_query($con, $sql);
        if ($res) {
            //check data is available or not
            $count = mysqli_num_rows($res);
            if ($count == 1) {
                //user exists and password can be changed
                //echo "user exists and password can be changed";
                //header("Location:" .SITEURL . "admin/manage-admin.php");
                if ($new_password == $confirm_password) {
                    //update password
                    //echo 'password matches';
                    $sql2 = "UPDATE tbl_admin SET password='$new_password' WHERE id=$id";
                    $res2 = mysqli_query($con, $sql2);
                    if ($res2) {
                        $_SESSION['change-psw'] = "<div class='success'>Password change successfully</div>";
                        header("Location:" . SITEURL . "admin/manage-admin.php");
                    } else {
                        $_SESSION['change-psw'] = "<div class='error'>Failed to change password</div>";
                        header("Location:" . SITEURL . "admin/manage-admin.php");
                    }
                } else {
                    //redirect to manage admin page
                    $_SESSION['password-not-match'] = "<div class='error'>Password Didn't Match</div>";
                    header("Location:" . SITEURL . "admin/manage-admin.php");
                }
            } else {
                //user does not exist
                $_SESSION['user-not-found'] = "<div class='error'>User does not exist</div>";
                header("Location:" . SITEURL . "admin/manage-admin.php");
            }
        }
        //check whether new password and confirm passwords exist
        //change password if above all true
    }

    ?>
 <?php include('partials/footer.php') ?>