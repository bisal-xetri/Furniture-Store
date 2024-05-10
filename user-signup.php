<?php include('config/constant.php') ?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="CSS/login.css" />


</head>

<body>

<link rel="stylesheet" href="CSS/login.css" />
    <div class="main">
        <div class="wrapper">
            <form action="" method="POST">
                <h1>User Signup</h1>
                <?php
                if (isset($_SESSION['add'])) {
                    echo $_SESSION['add'];
                    unset($_SESSION['add']);
                }

                ?>
                <div class="input-box">
                    <input type="text" name="full_name" placeholder="Full Name" />
                    <i class="bx bxs-user"></i>
                </div>
                <div class="input-box">
                    <input type="text" name="uname" placeholder="Username" />
                    <i class="bx bxs-user"></i>
                </div>
                <div class="input-box">
                    <input type="email" name="email" placeholder="enter your email" />
                    <i class='bx bxs-envelope'></i>
                </div>
                <div class="input-box">
                    <input type="tel" name="phone" placeholder="enter your phone number" />
                    <i class='bx bxs-contact'></i>
                </div>
                <div class="input-box">
                    <input type="text" name="address" placeholder="your address" />
                    <i class='bx bxs-location-plus'></i>
                </div>
                <label style="color:brown" for="">
                </label>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" />
                    <i class="bx bxs-lock-alt"></i>
                </div>
                <div class="input-box">
                    <input type="password" name="cpassword" placeholder="ConfirmPassword" />
                    <i class="bx bxs-lock-alt"></i>
                </div>

                <button type="submit" name="submit" class="btn">Signup</button>
                <div class="signup-link">
                    <p>Do you have an account? <a href="<?php echo SITEURL; ?>user-login.php">Login</a></p>
                </div>
        </div>
        </form>
    </div>

</body>

</html>
<?php
if (isset($_POST['submit'])) {
    // Validate that none of the fields are empty
    if (empty($_POST['full_name']) || empty($_POST['uname']) || empty($_POST['email']) || empty($_POST['phone']) || empty($_POST['address']) || empty($_POST['password']) || empty($_POST['cpassword'])) {
        $_SESSION['add'] = "<div class='error'>Please fill all the fields.</div>";
        header('Location:' . SITEURL . 'user-signup.php');
        exit;
    }

    // Validate that password and confirm password match
    if ($_POST['password'] !== $_POST['cpassword']) {
        $_SESSION['add'] = "<div class='error'>Password and Confirm Password do not match.</div>";
        header('Location:' . SITEURL . 'user-signup.php');
        exit;
    }

    // Validate that the password is at least 8 characters and contains a special symbol
    $password = $_POST['password'];
    if (strlen($password) < 8 || !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        $_SESSION['add'] = "<div class='error'>Password must be at least 8 characters long and contain a special symbol.</div>";
        header('Location:' . SITEURL . 'user-signup.php');
        exit;
    }

    $full_name = $_POST['full_name'];
    $username = $_POST['uname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = sha1($password); // Using the validated password

    $sql = "INSERT INTO tbl_user SET
    full_name = '$full_name',
    username = '$username',
    email = '$email',
    phone = $phone,
    address = '$address',
    password = '$password'";

    $res = mysqli_query($con, $sql);

    if ($res) {
        $_SESSION['add'] = "<div class='success'>Create Account successfully.</div>";
        header('Location:' . SITEURL . 'user-login.php');
    } else {
        $_SESSION['add'] = "<div class='error'>Failed to create account.</div>";
        header('Location:' . SITEURL . 'user-signup.php');
    }
} else {
    // Handle case where form is not submitted
}
?>