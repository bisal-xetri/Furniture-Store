<?php

include('../config/constant.php');
include('login-check.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="../CSS/admin.css">
</head>

<body>
    <div class="menu text-center">
        <div class="wrapper">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="manage-admin.php">Admin</a></li>
                <li><a href="manage-user.php">Users</a></li>
                <li><a href="manage-category.php">Category</a></li>
                <li><a href="manage-furniture.php">Furniture</a></li>
                <li><a href="manage-order.php">Orders</a></li>
                <li><a href="logout.php">logout</a></li>

            </ul>
        </div>
    </div>