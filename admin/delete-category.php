<?php
include('../config/constant.php');
//echo "delete page";
//check whether id and image values are set or not
if (isset($_GET['id']) and isset($_GET['image_name'])) {
    //get the value and delete it
    $id = $_GET['id'];
    $image_name = $_GET['image_name'];
    //remove image if already exists 
    if ($image_name != "") {
        $path = "../Image/category/" . $image_name;
        $remove = unlink($path);
        if ($remove == false) {
            //sett the session message the redirect to manage category page
            $_SESSION['remove'] = "<div class='error'>Failed to remove category image</div>";
            header("Location:" . SITEURL . "admin/manage-category.php");
            die();
        }
    }
    $sql = "DELETE FROM tbl_category WHERE id=$id";
    $res = mysqli_query($con, $sql);
    //check data delete from database or not 
    if ($res) {
        $_SESSION['delete'] = "<div class='success'>Categories deleted successfully</div>";
        header("Location:" . SITEURL . "admin/manage-category.php");
    } else {
        $_SESSION['delete'] = "<div class='error'>Failed to deleted category.</div>";
        header("Location:" . SITEURL . "admin/manage-category.php");
    }
} else {
    //redirect to manage category page
    header('Location:' . SITEURL . 'admin/manage-category.php');
}
