<?php include('partials/menu.php'); ?>
<div class="main-content">
    <div class="wrapper">
        <h1>Update Category</h1>
        <br>
        <?php
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            //create sql query to update category
            $sql = "SELECT * FROM tbl_category WHERE id = $id";
            $res = mysqli_query($con, $sql);
            $count = mysqli_num_rows($res);
            if ($count == 1) {
                $row = mysqli_fetch_assoc($res);
                $id = $row['id'];
                $title = $row['title'];
                $current_image = $row['image_name'];
                $featured = $row['featured'];
                $active = $row['active'];
                //check image selected or not
                if (isset($_FILES['image']['name'])) {
                    $image_name = $_FILES['image']['name'];
                    if ($image_name != "") {

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
                            header("Location:" . SITEURL . "admin/manage-category.php");
                            die();
                        }
                        //section B
                        if ($current_image != '') {
                            $remove_path = "../Image/category/" . $current_image;
                            $remove = unlink($remove_path);
                            if ($remove  == false) {
                                //failed to remove image
                                $_SESSION['failed-remove'] = "<div class='error'> Failed to remove Image</div>";
                                header("Location:" . SITEURL . "admin/manage-category.php");
                                die();
                            }
                        }
                    }
                } else {
                    $image_name = $current_image;
                }
            } else {
                //redirect to category
                $_SESSION['no-category-found'] = "<div class='error'>No category found</div>";
                header("Location:" . SITEURL . "admin/manage-category.php");
            }
        } else {
            //redirect to manage category page
            header('Location:' . SITEURL . 'admin/manage-category.php');
        }
        ?>
        <form action="" enctype="multipart/form-data" method="POST">
            <table class="tbl-30">
                <tr>
                    <td>Title:</td>
                    <td><input type="text" name="title" value="<?php echo $title; ?>"></td>
                </tr>
                <tr>
                    <td>Current Image:</td>
                    <td>
                        <?php
                        if ($current_image != "") {
                            //display the current image
                        ?>
                            <img src="<?php echo SITEURL; ?>Image/category/<?php echo $current_image; ?>" width="150px" alt="">
                        <?php
                        } else {
                            //display the message
                            echo "<div class='error'>Image not found</div>";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>New Image:</td>
                    <td><input type="file" name="image"></td>
                </tr>
                <tr>
                    <td>Featured:</td>
                    <td><input <?php if ($featured == "Yes") {
                                    echo "checked";
                                } ?> type="radio" name="featured" value="Yes">Yes
                        <input <?php if ($featured == "No") {
                                    echo "checked";
                                } ?> type="radio" name="featured" value="No">No
                    </td>
                </tr>
                <tr>
                    <td>Active:</td>
                    <td><input <?php if ($active == "Yes") {
                                    echo "checked";
                                } ?> type="radio" name="active" value="Yes">Yes
                        <input <?php if ($active == "No") {
                                    echo "checked";
                                } ?> type="radio" name="active" value="No">No
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="submit" name="submit" value="Update Category" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>
        <?php
        if (isset($_POST['submit'])) {
            // echo "click";
            $id = $_POST['id'];
            $title = $_POST['title'];
            $current_image = $_POST['current_image'];
            $featured = $_POST['featured'];
            $active = $_POST['active'];
            //upload selected image
            $sql2 = "UPDATE tbl_category SET title='$title',image_name='$image_name', featured = '$featured', active = '$active' WHERE id = '$id'";
            $res2 = mysqli_query($con, $sql2);
            if ($res2) {
                $_SESSION['update'] = "<div class='success'>Category updated</div>";
                header("Location:" . SITEURL . "admin/manage-category.php");
            } else {
                $_SESSION['update'] = "<div class='error'>Failed to update category.</div>";
                header("Location:" . SITEURL . "admin/manage-category.php");
            }
        }
        ?>
    </div>
</div>
<?php include('partials/footer.php'); ?>