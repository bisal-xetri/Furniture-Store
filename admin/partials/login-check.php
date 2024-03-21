
<?php


//heck user login or not
if (!isset($_SESSION['user'])) { //if user session is not saved 
    //user is not logged in
    $_SESSION['no-login-message'] = "<div style='color:red; text-align:center;'>*Please login to access admin panel</div>";
    //login page redirect
    header('Location:' . SITEURL . 'admin/login.php');
}
