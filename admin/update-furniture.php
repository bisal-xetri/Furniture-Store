<?php 
include('partials/menu.php'); 

ob_start(); // Start output buffering

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql2 = "SELECT * FROM tbl_furniture WHERE id=$id";
    $res2 = mysqli_query($con, $sql2);
    $row = mysqli_fetch_assoc($res2);
    // Get individually value of selected furniture object
    $title = $row['title'];
    $description = $row['description'];
    $price = $row['price'];
    $current_image = $row['image_name'];
    $stock = $row['stock'];
    $current_category = $row['category_id'];
    $featured = $row['featured'];
    $active = $row['active'];
} else {
    header('Location:' . SITEURL . 'admin/manage-furniture.php');
    exit();
}
?>
<div class="main-content">
    <div class="wrapper">
        <h1>Update Furniture</h1>
        <br>
        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title:</td>
                    <td><input class='update-input' type="text" name="title" value="<?php echo htmlspecialchars($title); ?>"></td>
                </tr>
                <tr>
                    <td>Description:</td>
                    <td><textarea class='update-input' name="description" cols="20" rows="5"><?php echo htmlspecialchars($description); ?></textarea></td>
                </tr>
                <tr>
                    <td>Price:</td>
                    <td><input class='update-input' type="number" min="0" value="<?php echo $price ?>" name="price"></td>
                </tr>
                <tr>
                    <td>Stock:</td>
                    <td><input class='update-input' type="number" min="0" value="<?php echo $stock ?>" name="stock"></td>
                </tr>
                <tr>
                    <td>Current Image:</td>
                    <td>
                        <?php if ($current_image == '') {
                            echo "<div class='error'>Image not found</div>";
                        } else { ?>
                            <img src="<?php echo SITEURL; ?>Image/furniture/<?php echo $current_image; ?>" width="150px" alt="">
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td>Select New Image:</td>
                    <td><input type="file" name="image"></td>
                </tr>
                <tr>
                    <td>Category:</td>
                    <td>
                        <select name="category">
                            <?php
                            $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                            $res = mysqli_query($con, $sql);
                            $count = mysqli_num_rows($res);
                            if ($count > 0) {
                                while ($row = mysqli_fetch_assoc($res)) {
                                    $category_title = $row['title'];
                                    $category_id = $row['id'];
                                    ?>
                                    <option <?php if ($current_category == $category_id) echo 'selected'; ?> value='<?php echo $category_id; ?>'><?php echo $category_title; ?></option>
                                    <?php
                                }
                            } else {
                                echo "<option value='0'>Category not available</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Featured:</td>
                    <td>
                        <input <?php if ($featured == 'Yes') echo 'checked'; ?> type="radio" name="featured" value="Yes">Yes
                        <input <?php if ($featured == 'No') echo 'checked'; ?> type="radio" name="featured" value="No">No
                    </td>
                </tr>
                <tr>
                    <td>Active:</td>
                    <td>
                        <input <?php if ($active == 'Yes') echo 'checked'; ?> type="radio" name="active" value="Yes">Yes
                        <input <?php if ($active == 'No') echo 'checked'; ?> type="radio" name="active" value="No">No
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
                        <input type="submit" name="submit" value="Update Furniture" class="btn-primary">
                    </td>
                </tr>
            </table>
        </form>
        <?php
        if (isset($_POST['submit'])) {
            $id = $_POST['id'];
            $title = $_POST['title'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $current_image = $_POST['current_image'];
            $category = $_POST['category'];
            $featured = $_POST['featured'];
            $active = $_POST['active'];
            $stock = $_POST['stock'];

            // Check whether the upload button is enabled or not
            if (isset($_FILES['image']['name'])) {
                $image_name = $_FILES['image']['name'];
                if ($image_name != '') {
                    // Rename the image
                    $explodedArray = explode('.', $image_name);
                    $ext = end($explodedArray);
                    $image_name = "Furniture-Name" . rand(000, 999) . '.' . $ext;
                    $source_path = $_FILES['image']['tmp_name'];
                    $dest_path = "../Image/furniture/" . $image_name;
                    $upload = move_uploaded_file($source_path, $dest_path);
                    if ($upload == false) {
                        // Failed to upload
                        $_SESSION['upload'] = "<div class='error'>Upload failed</div>";
                        header("Location:" . SITEURL . "admin/manage-furniture.php");
                        exit();
                    }
                    // Remove current image if it exists
                    if ($current_image != '') {
                        // Current image is available
                        $remove_path = "../Image/furniture/" . $current_image;
                        $remove = unlink($remove_path);
                        // Check if image is removed or not
                        if ($remove == false) {
                            $_SESSION['remove-failed'] = "<div class='error'>Failed removing current image</div>";
                            header("Location:" . SITEURL . "admin/manage-furniture.php");
                            exit();
                        }
                    }
                } else {
                    $image_name = $current_image;
                }
            } else {
                $image_name = $current_image;
            }
            
            $sql3 = "UPDATE tbl_furniture SET 
                title='$title',
                description='$description',
                price=$price,
                image_name='$image_name',
                stock=$stock,
                category_id=$category,
                featured='$featured',
                active='$active'
                WHERE id = $id
            ";
            
            $res3 = mysqli_query($con, $sql3);
            if ($res3) {
                $_SESSION['update'] = "<div class='success'>Furniture updated successfully.</div>";
                header("Location:" . SITEURL . "admin/manage-furniture.php");
            } else {
                $_SESSION['update'] = "<div class='error'>Failed to update furniture.</div>";
                header("Location:" . SITEURL . "admin/manage-furniture.php");
            }
            exit();
        }
        ?>
    </div>
</div>
<?php 
ob_end_flush(); // End output buffering and flush output
include('partials/footer.php'); 
?>
