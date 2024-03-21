<?php include('partials/menu.php') ?>
<div class="main-content">
    <div class="wrapper">
        <h1>Manage Users</h1>
        <br>
        <?php

        if (isset($_SESSION['delete'])) {
            echo $_SESSION['delete'];
            unset($_SESSION['delete']); //remove the session

        }
        ?>
        <br>
        <br>
        <!-- button to add admin -->

        <table class="tbl-full">
            <tr>
                <th>S.N.</th>
                <th>Full Name</th>
                <th>Username</th>
                <th>Actions</th>
            </tr>
            <?php
            //query to get all admin users
            $sql = "SELECT * FROM tbl_user";
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
                            <td>

                                <a class="btn-danger" href="<?php echo SITEURL; ?>admin/delete-user.php?id=<?php echo $id ?>"> Delete User</a>

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