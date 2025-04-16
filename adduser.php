<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require('db.php');
require('auth_session.php');

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

    //add user into db
    $query = "INSERT INTO `users` (username, password, email, phone, birthday, create_datetime)
            VALUES ('$username', '$hashed_password','$email','$phone', '$bday','$create_datetime')";
    mysqli_query($con, $query);
}
?>


<!DOCTYPE html>
<html>
<head>
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
        </form>

        <div class="group-buttons">
            <button><a href="dashboard.php">Dashboard</a></button>
        </div>
    </div>
</body>
</html>
