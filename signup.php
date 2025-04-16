<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('db.php');
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_POST['username'])) {
    $username = mysqli_real_escape_string($con, stripslashes($_POST['username']));
    $email    = mysqli_real_escape_string($con, stripslashes($_POST['email']));
    $password = mysqli_real_escape_string($con, stripslashes($_POST['password']));
    $phone    = mysqli_real_escape_string($con, stripslashes($_POST['phone']));
    $bday     = mysqli_real_escape_string($con, stripslashes($_POST['bday']));
    $create_datetime = date("Y-m-d H:i:s");
    $is_admin = 0; // default for new users

    $query = "INSERT INTO users (username, password, email, phone, birthday, create_datetime, is_admin)
              VALUES ('$username', '" . md5($password) . "', '$email', '$phone', '$bday', '$create_datetime', '$is_admin')";

    $result = mysqli_query($con, $query);

    if ($result) {
        echo "<div class='form'>
              <h3>YOU HAVE REGISTERED</h3><br/>
              <p class='link'>Click to <a href='login.php'>Login</a></p>
              </div>";
        exit(); // prevents the form from showing again
    } else {
        echo "<div class='form'>
              <h3>Error: " . mysqli_error($con) . "</h3><br/>
              <p class='link'>Click to <a href='signup.php'>register</a> again.</p>
              </div>";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Pretend Photo Website</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <i class="fa-solid fa-bug" style="color: #ffffff; font-size: 80px; display: block; align-content: center;margin-right: 15px;padding: 8px 16px; border-radius: 0px;"></i>
        <h1 id='p1' style="color: rgb(255, 255, 255); text-align: left; font-weight: bolder; font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; font-size:32px;">
            BAR <br> TER <br> BEE <br>
        </h1>
        <nav>
            <ul class="nav-links">
                <li><a href="index.html">HOME</a></li>
                <li><a href="gallery.php">SHOP</a></li>
                <li><a href="bio.html">BIO</a></li>
                <li><a href="contactus.html">CONTACT</a></li>
                <li><a href="cart.html">CART</a></li>
                <li><a href="login.php">LOGIN</a></li>
                <li><a href="dashboard.php">ADMIN</a></li>
                <li><a href="additem.php">POST</a></li>
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

    <h1>Sign Up</h1>
    <form method="POST" action=""> <!-- Using POST method to submit the form to itself -->
      
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="Phone number" required>
        <p>Birthday:</p><input type="date" name="bday" placeholder="Birthday" required><br>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" value="Register">
    </form>
    <br>
    <p>Already have an account? Login <a href="login.php"><u>here</u></a>!</p>

</body>
</html>
