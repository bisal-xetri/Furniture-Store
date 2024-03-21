<?php include('partials/menu.php') ?>
<div class="main-content">
    <div class="wrapper">
        <h1>Add Furniture</h1>
        <br>
        <?php
        if (isset($_SESSION['upload'])) {
            echo $_SESSION['upload'];
            unset($_SESSION['upload']);
        }

        ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title:</td>
                    <td><input type="text" name="title" placeholder="Name of the Furniture"></td>
                </tr>
                <tr>
                    <td>Description:</td>
                    <td><textarea name="description" id="" cols="30" rows="5" placeholder="Description of Furniture"></textarea></td>
                </tr>
                <tr>
                    <td>Price:</td>
                    <td><input type="number" min="0" name="price" placeholder=""></td>
                </tr>
                <tr>
                    <td>Select Image:</td>
                    <td><input type="file" name="image"></td>
                </tr>
                <tr>
                    <td>Category:</td>
                    <td><select name="category" id="">
                            <?php
                            //create a php to display the category
                            //create sql to get all the categories
                            $sql = "SELECT * FROM tbl_category WHERE active='YES'";
                            $res = mysqli_query($con, $sql);
                            $count = mysqli_num_rows($res);
                            if ($count > 0) {
                                //we have a category
                                while ($row = mysqli_fetch_array($res)) {
                                    $id = $row['id'];
                                    $title = $row['title'];
                            ?>
                                    <option value="<?php echo $id; ?>"><?php echo $title; ?></option>
                                <?php
                                }
                            } else {
                                //we don't have a category  
                                ?>
                                <option value="0">No category Found!</option>
                            <?php
                            }

                            ?>

                        </select></td>
                </tr>
                <tr>
                    <td>Featured:</td>
                    <td><input type="radio" name="featured" value="Yes">Yes
                        <input type="radio" name="featured" value="No">No

                    </td>
                </tr>
                <tr>
                    <td>Active:</td>
                    <td><input type="radio" name="active" value="Yes">Yes
                        <input type="radio" name="active" value="No">No

                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Furniture" class="btn-secondary" id="">
                    </td>
                </tr>
            </table>
        </form>
        <?php
        //check whether button clicked or not
        if (isset($_POST['submit'])) {
            // echo 'helo';
            //1. get the data from the form
            $title = $_POST['title'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category = $_POST['category'];
            //check whether radio button is checked or not
            if (isset($_POST['featured'])) {
                $featured = $_POST['featured'];
            } else {
                $featured = "No";
            }
            if (isset($_POST['active'])) {
                $active = $_POST['active'];
            } else {
                $active = "No";
            }

            //2. upload the selected image 
            //check whether the selected image is clicked or not
            if (isset($_FILES['image']['name'])) {
                //get the details of the selected image
                $image_name = $_FILES['image']['name'];
                //check whether image selected or not upload only if selected
                if ($image_name != "") {
                    //image selected
                    //a. rename the selected image

                    //get the extension of the selected image
                    $explodedArray = explode('.', $image_name);
                    $ext = end($explodedArray);
                    //create new name for image
                    $image_name = "Furniture-Name" . rand(000, 999) . "." . $ext; //eg Furniture-Name123.jpg

                    //b. upload the selected image
                    $src = $_FILES['image']['tmp_name'];
                    $dst = "../Image/furniture/" . $image_name;
                    $upload = move_uploaded_file($src, $dst);
                    //check whether the image is uploaded successfully or not
                    if ($upload == false) {
                        $_SESSION['upload'] = "<div class='error'>Upload Failed</div>";
                        header("Location:" . SITEURL . "admin/add-furniture.php");
                        die();
                    }
                }
            } else {
                //setting default values as blank
                $image_name = "";
            }
            //3. insert into the database
            //create sql query for 
            $sql2 = "INSERT INTO tbl_furniture SET 
             title='$title',
             description='$description', 
             price=$price, 
             image_name='$image_name', 
             category_id=$category,
             featured ='$featured', 
            active='$active'";
            //4.
            $res2 = mysqli_query($con, $sql2);
            if ($res2) {
                //data inserted into database
                $_SESSION['add'] = "<div class='success'> furniture added Successfully </div>";
                header("Location:" . SITEURL . "admin/manage-furniture.php");
            } else {
                // failure
                $_SESSION['add'] = "<div class='error'> Failed to add Furniture. </div>";
                header("Location:" . SITEURL . "admin/manage-furniture.php");
            }
        }

        ?>
    </div>
</div>

<?php include('partials/footer.php') ?>