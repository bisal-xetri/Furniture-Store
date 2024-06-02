<?php include('partials/menu.php') ?>
<div class="main-content">
    <div class="wrapper">
        <h1>Manage Furniture</h1>

        <br>

        <!-- button to add admin -->
        <a class="btn-primary" href="<?php echo SITEURL; ?>admin/add-furniture.php">Add Furniture</a>
        <br>
        <br>
        <?php
        if (isset($_SESSION['add'])) {
            echo $_SESSION['add'];
            unset($_SESSION['add']);
        }
        if (isset($_SESSION['delete'])) {
            echo $_SESSION['delete'];
            unset($_SESSION['delete']);
        }
        if (isset($_SESSION['upload'])) {
            echo $_SESSION['upload'];
            unset($_SESSION['upload']);
        }
        if (isset($_SESSION['unauthorize'])) {
            echo $_SESSION['unauthorize'];
            unset($_SESSION['unauthorize']);
        }
        if (isset($_SESSION['update'])) {
            echo $_SESSION['update'];
            unset($_SESSION['update']);
        }
        ?>
        <table class="tbl-full">
            <tr>
                <th>S.N.</th>
                <th>Title</th>
                <th>Price</th>
                <th>Image</th>
                <th>Featured</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
            <?php
            $sql = "SELECT * FROM tbl_furniture";
            $res = mysqli_query($con, $sql);
            $count = mysqli_num_rows($res);
            $sn = 1;
            if ($count > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $id = $row['id'];
                    $title = $row['title'];
                    $price = $row['price'];
                    $image_name = $row['image_name'];
                    $featured = $row['featured'];
                    $active = $row['active'];
            ?>
                    <tr>
                        <td><?php echo $sn++; ?>.</td>
                        <td><?php echo $title; ?></td>
                        <td>Rs.<?php echo $price; ?></td>
                        <td>
                            <?php
                            if ($image_name == '') {
                                echo "<div class='error'>Image not added</div>";
                            } else {
                            ?>
                                <img src="<?php echo SITEURL; ?>Image/furniture/<?php echo $image_name; ?>" width="100px" alt="">
                            <?php
                            }
                            ?>
                        </td>
                        <td><?php echo $featured; ?></td>
                        <td><?php echo $active; ?></td>
                        <td>
                            <a class="btn-secondary" href="<?php echo SITEURL; ?>admin/update-furniture.php?id=<?php echo $id; ?>">Update Furniture</a>
                            <a class="btn-danger" href="<?php echo SITEURL; ?>admin/delete-furniture.php?id=<?php echo $id; ?>&image_name=<?php echo $image_name; ?>">Delete Furniture</a>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='7' class='error'>Furniture not added yet.</td></tr>";
            }
            ?>
        </table>
    </div>
</div>
<?php include('partials/footer.php') ?>