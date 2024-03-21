<?php
include('../config/constant.php');
//1getthe id of the admin
$id = $_GET['id'];

//2. create  sql statement
$sql = "DELETE FROM tbl_admin WHERE id=$id";
//execute sql statement
$res = mysqli_query($con, $sql);
if ($res) {
    //echo "Successfully deleted";
    //create session variable to display message
    $_SESSION['delete'] = "<div class='success'>Admin deleted successfully.</div>";
    //redirect back to manage admin
    header('Location:' . SITEURL . 'admin/manage-admin.php');
} else {
    //echo "Failed to delete admin";
    $_SESSION['delete'] = "<div class='error'>Failed to delete admin. Try again</div>";
    header('Location:' . SITEURL . 'admin/manage-admin.php');
}
//3. redirect to admin manage page with message (sucessfully/error)
