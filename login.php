
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <title>
        Pretend Photo Website
    </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <i class="fa-solid fa-bug" style="color: #ffffff; font-size: 80px; display: block; align-content: center;margin-right: 15px;padding: 8px 16px; border-radius: 0px;"></i>
        <h1 id='p1' style="color: rgb(255, 255, 255); text-align: left; font-weight: bolder; font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; font-size:32px;">
        BEE <br> BUY <br>
        </h1>
        <nav>
            <ul class="nav-links">
                <li><a href="index.html">HOME</a></li>
                <li><a href="gallery.php">SHOP</a></li>
                <li><a href="bio.html">BIO</a></li>
                <li><a href="contactus.html">CONTACT</a></li>
                <li><a href="login.php" class="active">LOGIN</a></li>
                <li><a href="cart.html">CART</a></li>
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

    <?php
    require('db.php');
    session_start();
    if (isset($_POST['username'])) {//stripslashes cleans up stuff with backslashes so data is not messy
        $username = stripslashes($_REQUEST['username']);  
        $username= mysqli_real_escape_string($con, $username);
        $password = stripslashes($_REQUEST['password']);
        $password =mysqli_real_escape_string($con, $password);
        $query    = "SELECT * FROM `users` WHERE username='$username'
                     AND password='" . md5($password) . "'";
        $result = mysqli_query($con, $query) or die(mysql_error());
        $rows = mysqli_num_rows($result);
        if ($rows == 1) {
            $_SESSION['username']= $username;
            header("Location: dashboard.php");
        } 
    }else{
        ?>
            <form class="form" method="post" name="login">
                <h1 class="login-title">Login</h1>
                <input type="text" class="login-input" name="username"placeholder="Username"/>
                <input type="password"class="login-input" name="password" placeholder="Password"/>
                <input type="submit" value="Login"name="submit"class="login-button"/>
            </form>
        <?php
    }
?>  
    
    <br>
    <p>Don't have an account? Create one <a href="signup.php"><u>here</u></a>!</p>

</body>

</html>
