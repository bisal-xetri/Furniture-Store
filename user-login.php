<?php include('config/constant.php') ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="CSS/login.css" />


</head>

<body>
    <div class="main">
        <div class="wrapper">
            <form action="" method="post">
                <h1>login</h1>
                <br>
                <?php
                if (isset($_SESSION['login'])) {
                    echo $_SESSION['login'];
                    unset($_SESSION['login']);
                }
                if (isset($_SESSION['no-login-message'])) {
                    echo $_SESSION['no-login-message'];
                    unset($_SESSION['no-login-message']);
                }
                ?>
                <div class="input-box">
                    <input type="text" name="username" placeholder="Username" />
                    <i class="bx bxs-user"></i>
                </div>

                <label style="color:brown" for="">
                </label>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" />
                    <i class="bx bxs-lock-alt"></i>
                </div>

                <input type="submit" name="submit" value="LogIn" class="btn">
                <div class="signup-link">
                    <p>If you have't an account? <a href="<?php echo SITEURL; ?>user-signup.php">SignIn</a></p>
                </div>
        </div>

    </div>
    </form>
    </div>
</body>

</html>
<?php
// Check whether submit button is clicked or not
if (isset($_POST['submit'])) {
    // Process for login form
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = sha1($_POST['password']);

    // Check SQL to see whether a user with the username and password exists or not
    $sql = "SELECT * FROM tbl_user WHERE username = '$username' AND password = '$password'";
    $res = mysqli_query($con, $sql);
    if ($res) {
        $count = mysqli_num_rows($res);
        if ($count == 1) {
            // Fetch user data
            $row = mysqli_fetch_assoc($res);
            $user_id = $row['id'];
            $customer_email=$row['email'] ;
            $customer_add=$row['address'];
            $customer_number=$row['phone'];// Assuming 'id' is the column name for the user ID in your table

            // Set session variables
            $_SESSION['login'] = "<div class='success'>Login Successfully</div>";
            $_SESSION['username'] = $username;
            $_SESSION['id'] = $user_id; 
              $_SESSION['email']=$customer_email; 
             $_SESSION['add']=$customer_add;
           
             $_SESSION['number']= $customer_number;

            // Redirect to the appropriate page
            header("Location:" . SITEURL . "customer/index.php");
            exit(); // Exit after redirection
        } else {
            // Session message
            $_SESSION['login'] = "<div class='error'>Username or password didn't match.</div>";
            header("Location:" . SITEURL . "user-login.php");
            exit(); // Exit after redirection
        }
    } else {
        // Handle query execution failure
        echo "Error executing query: " . mysqli_error($con);
    }
}

?>