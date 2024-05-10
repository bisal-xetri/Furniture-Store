<?php include('../config/constant.php') ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../CSS/login.css" />


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

        </div>
        </form>
    </div>
</body>

</html>
<?php
//check whether submit button is clicked or not
if (isset($_POST['submit'])) {
    //process for login form
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = sha1($_POST['password']);
    //check aql to whether user with username and password exists or not
    $sql = "SELECT * FROM tbl_admin WHERE username = '$username' AND password = '$password'";
    $res = mysqli_query($con, $sql);
    if ($res) {
        $count = mysqli_num_rows($res);
        if ($count == 1) {
            $_SESSION['login'] = "<div class='success'>Admin successfully logged in</div>";
            $_SESSION['user'] = $username; //to check if user is logged in or not an logout will unset the
            header("Location:" . SITEURL . "admin/");
        } else {
            //session message
            $_SESSION['login'] = "<div class='error'>Please enter the correct username and password.</div>";
            header("Location:" . SITEURL . "admin/login.php");
        }
    }
}

?>