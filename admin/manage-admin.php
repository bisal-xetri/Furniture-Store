<?php include('partials/menu.php') ?>
<div class="main-content">
    <div class="wrapper">
        <h1>Manage Admin</h1>
        <br>
        <?php
        if (isset($_SESSION['add'])) {
            echo $_SESSION['add']; //displayed session
            unset($_SESSION['add']); //remove the session
        }
        if (isset($_SESSION['delete'])) {
            echo $_SESSION['delete'];
            unset($_SESSION['delete']); //remove the session

        }
        if (isset($_SESSION['update'])) {
            echo $_SESSION['update'];
            unset($_SESSION['update']); //remove the session

        }
        if (isset($_SESSION['user-not-found'])) {
            echo $_SESSION['user-not-found'];
            unset($_SESSION['user-not-found']); //remove the session

        }
        if (isset($_SESSION['password-not-match'])) {
            echo $_SESSION['password-not-match'];
            unset($_SESSION['password-not-match']); //remove the session

        }
        if (isset($_SESSION['change-psw'])) {
            echo $_SESSION['change-psw'];
            unset($_SESSION['change-psw']); //remove the session

        }
        ?>
        <br>
        <br>
        <!-- button to add admin -->
        <a class="btn-primary" href="add-admin.php">Add Admin</a>
        <br>
        <br>
        <table class="tbl-full">
            <tr>
                <th>S.N.</th>
                <th>Full Name</th>
                <th>Username</th>
                <th>Actions</th>
            </tr>
            <?php
            //query to get all admin users
            $sql = "SELECT * FROM tbl_admin";
            $res = mysqli_query($con, $sql);
            if ($res) {
                //count rows of admin users
                $row = mysqli_num_rows($res); //function to get all rows of admin users
                $sn = 1; //create new 

                if ($row > 0) {
                    //we have admin users
                    while ($row = mysqli_fetch_assoc($res)) {
                        //using while loop to get all admin users
                        $id = $row['id'];
                        $full_name = $row['full_name'];
                        $username = $row['username'];
                        //display value in table
            ?>
                        <tr>
                            <td><?php echo $sn++; ?>.</td>
                            <td><?php echo $full_name; ?></td>
                            <td><?php echo $username; ?></td>
                            <td colspan="">
                                <a class="btn-primary" href="<?php echo SITEURL; ?>admin/update-password.php?id=<?php echo $id ?>"> Change Password</a>
                                <a class="btn-secondary" href="<?php echo SITEURL; ?>admin/update-admin.php?id=<?php echo $id ?>"> Update Admin</a>
                                <a class="btn-danger" href="<?php echo SITEURL; ?>admin/delete-admin.php?id=<?php echo $id ?>"> Delete Admin</a>

                            </td>
                        </tr>
            <?php
                    }
                } else {
                    //we have no admin users
                }
            }



            ?>

        </table>
    </div>
</div>
<?php include('partials/footer.php') ?>