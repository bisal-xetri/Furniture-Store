<?php include('partials/menu.php') ?>
<div class="main-content">
    <div class="wrapper">
        <h1>Add Admin</h1>
        <br>
        <?php
        if (isset($_SESSION['add'])) { //seeking whether session is already is set or not
            echo $_SESSION['add'];
            unset($_SESSION['add']);
        }

        ?>

        <form action="" method="post">
            <table class="tbl-30">
                <tr>
                    <td>Full Name:</td>
                    <td><input type="text" name="full_name" placeholder="enter your name" id=""></td>
                </tr>
                <tr>
                    <td>Username:</td>
                    <td><input type="text" name="username" placeholder="Your username" id=""></td>
                </tr>
                <tr>
                    <td>Password:</td>
                    <td><input type="password" name="password" placeholder=" Your password" id=""></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Admin" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<?php include('partials/footer.php') ?>
<?php
// process the value from form and save it to database
// Check whether button is clicked or not
if (isset($_POST['submit'])) {
    // button is clicked
    //     get the value from the form
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $password = sha1($_POST['password']); // sha1 the password
    // sql query save to data in database
    $sql = "INSERT INTO tbl_admin SET 
    full_name = '$full_name',
    username = '$username',
    password = '$password'
     ";

    $res = mysqli_query($con, $sql) or die(mysqli_error($con));
    if ($res) {
        // echo "data inserted into tbl_admin";
        //create session
        $_SESSION['add'] = 'Admin added successfully';
        //redirect back to manage admin page
        header('location:' . SITEURL . 'admin/manage-admin.php');
    } else {
        // echo "data not inserted into tbl_admin";
        //create session
        $_SESSION['add'] = 'Failed to add admin';
        //redirect back to add admin page
        header('location:' . SITEURL . 'admin/manage-admin.php');
    }
}
?>