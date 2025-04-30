<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require('db.php');
require('auth_session.php');
//show errors and debugging issues

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) ||  $_SESSION['is_admin'] !== true) {
    header("Location: login.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"]== "POST"){

    $username = stripslashes($_POST['username']);
    $username= mysqli_real_escape_string($con, $username);
    $email = stripslashes($_POST['email']);
    $email= mysqli_real_escape_string($con,$email);
     $phone = stripslashes($_POST['phone']);
    $phone = mysqli_real_escape_string($con, $phone);
    $bday = stripslashes($_POST['bday']);
    $bday= mysqli_real_escape_string($con, $bday);
    $password =stripslashes($_POST['password']); 
    $password= mysqli_real_escape_string($con, $password);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $create_datetime =date("Y-m-d H:i:s");
    $is_admin=0;
    try {
        $query = "INSERT INTO `users` (username, password, email, phone, birthday, create_datetime, is_admin)
                VALUES ('$username', '$hashed_password','$email','$phone', '$bday','$create_datetime', '$is_admin')";
        mysqli_query($con, $query);
        $success = "User added successfully. Start shopping!";
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() === 1062) {
            $error = "Username already exists. Please choose another.";
        } else {
            $error = "Database error: " . $e->getMessage();
        }
    }
}

?>


<!DOCTYPE html>
<html>
<head>
<header>
        <i class="fa-solid fa-bug" style="color: #ffffff; font-size: 72px; display: block; align-content: center;margin-right: 15px;padding: 8px 16px; border-radius: 0px;"></i>
        <h1 id='p1' style="color: rgb(255, 255, 255); text-align: left; font-weight: bolder; font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; font-size:32px;">
            BEE <br> BUY <br>
        </h1>
        <nav>
            <ul class="nav-links">
                <li><a href="index.html">HOME</a></li>
                <li><a href="gallery.php">SHOP</a></li>
                <li><a href="bio.html">BIO</a></li>
                <li><a href="contactus.html">CONTACT</a></li>
                <li><a href="cart.php" class="active">CART</a></li>
                <li><a href="login.php">LOGIN</a></li>
                <li><a href="dashboard.php">ADMIN</a></li>
                <li><a href="additem.php">POST</a></li>
                <li><a href="submit.php">PENDING</a></li>
                <a href="https://facebook.com" target="_blank"> 
                    <i class="fa-brands fa-facebook-f" style="color: #ffffff; align-content: center;margin-right: 15px;padding: 8px 16px; border-radius: 0px;"></i>
                </a><br>
                <a href="https://x.com" target="_blank"> 
                    <i class="fa-brands fa-x-twitter" style="color: #ffffff; align-content: center;margin-right: 15px;padding: 8px 16px; border-radius: 0px;"></i>
                </a><br>
                <a href="https://instagram.com" target="_blank"> 
                    <i class="fa-brands fa-instagram" style="color: #ffffff; align-content: center;margin-right: 15px;padding: 8px 16px; border-radius: 0px;"></i>
                </a>
                </ul>
        </nav>
    </header>
    <meta charset="utf-8">
    <title>Add New User - Admin</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /*would not let me use styles.css fo some reason*/
    
        a{
            color: black;
            font-weight: bolder;
        }
        body{
            background-color: white;
            background-image:none;
        }
        h1{
            color:black;
            font-size: 24px;
        }
        a:hover{
            background-color:rgba(40, 90, 121, 0.21);
            font-weight: bolder;
            text-decoration: underline;
            color:rgb(11, 125, 32);
        }
        button{
            border: none;
            padding: 10px 12px;
            margin: 0 10px;     
            font-weight:bolder;      
        }
        .group-buttons {
            text-align: center; 
            margin-top: 20px;   
        }
    </style>
</head>
<body>
    <div class="form">
        <h1>Add New User</h1>

        <form method="POST" action="adduser.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email"name="email" placeholder="Email"required>
            <input type="text" name="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="000-000-0001" required>
            <input type="date"name="bday" placeholder="Birthday" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Add New User</button>
            <button type="submit"><a href="dashboard.php">Dashboard</a></button>
        </form>
        <?php if (isset($error)) { echo "<p style='color:black;'>$error</p>"; } ?>
        <?php if (isset($success)) { echo "<p style='color:black;'>$success</p>"; } ?>

    </div>
</body>
</html>
