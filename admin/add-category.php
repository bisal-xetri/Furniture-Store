<?php
include('partials/menu.php');
?>
<div class="main-content">
    <div class="wrapper">
        <h1>Add Category</h1>
        <br>
        <?php
        if (isset($_SESSION['add'])) {
            echo $_SESSION['add'];
            unset($_SESSION['add']);
        }
        if (isset($_SESSION['upload'])) {
            echo $_SESSION['upload'];
            unset($_SESSION['upload']);
        }
        ?>
        <br>
        <form action="" method="post" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title:</td>
                    <td><input type="text" name="title" placeholder="category title"></td>
                </tr>
                <tr>
                    <td>Select Image:</td>
                    <td><input type="file" name="image" placeholder=""></td>
                </tr>
                <tr>
                    <td>Featured:</td>
                    <td>
                        <input type="radio" name="featured" value="Yes">Yes
                        <input type="radio" name="featured" value="No">No
                    </td>
                </tr>
                <tr>
                    <td>Active:</td>
                    <td>
                        <input type="radio" value="Yes" name="active">Yes
                        <input type="radio" value="No" name="active">No
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Category" class="btn-primary">
                    </td>
                </tr>
            </table>
        </form>
        <?php
        // check if submit button is clicked
        if (isset($_POST['submit'])) {
            $title = $_POST['title'];
            // for radio input type whether button is selected or not
            if (isset($_POST['featured'])) {
                $featured = $_POST['featured'];
            } else {
                // set default value
                $featured = 'No';
            }
            if (isset($_POST['active'])) {
                $active = $_POST['active'];
            } else {
                // set default value
                $active = 'No';
            }
            //check if image selected or not    
            // print_r($_FILES['image']);
            // die();
            if (isset($_FILES['image']['name'])) {
                //upload image
                $image_name = $_FILES['image']['name'];
                //upload image only if image selected
                if ($image_name != "") {

                    
                    //auto rename image
                    //get the extensions of the image (jpg,png,wep);
                    $ext = end(explode('.', $image_name));
                    //rename image
                    $image_name = "Furniture_category_" . rand(000, 999) . '.' . $ext;

                    $source_path = $_FILES['image']['tmp_name'];
                    $destination_path = "../Image/category/" . $image_name;
                    //upload image
                    $upload = move_uploaded_file($source_path, $destination_path);
                    if ($upload == false) {
                        $_SESSION['upload'] = "<div class='error'> failed to upload image</div>";
                        header("Location:" . SITEURL . "admin/add-category.php");
                        die();
                    }
                }
            } else {
                //don't upload image and set the image name value as blank
                $image_name = "";
            }
            // create sql query to insert data into the database
            $sql = "INSERT INTO tbl_category SET
             title='$title',
             image_name='$image_name',
             featured='$featured',
             active='$active'";
            $res = mysqli_query($con, $sql);
            if ($res) {
                $_SESSION['add'] = "<div class='success'>Category Added</div>";
                header('Location:' . SITEURL . 'admin/manage-category.php');
            } else {
                $_SESSION['add'] = "<div class='error'>Failed to add category.</div>";
                header('Location:' . SITEURL . 'admin/add-category.php');
            }
        }
        ?>
    </div>
</div>
<?php
include('partials/footer.php');
?>