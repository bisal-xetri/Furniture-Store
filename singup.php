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
            <form action="studentsignupvalidation.php" method="post">
                <h1>Signup</h1>
                <div class="input-box">
                    <input type="text" name="uname" placeholder="Username" />
                    <i class="bx bxs-user"></i>
                </div>
                <div class="input-box">
                    <input type="email" name="email" placeholder="email" />
                    <i class='bx bxs-envelope'></i>
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
                    <p>Do you have an account? <a href="studentlogin.php">Login</a></p>
                </div>
        </div>
        </form>
    </div> 
</body>
</html>