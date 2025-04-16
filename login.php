<?php
session_start();
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = stripslashes($_POST['username']);
    $username = mysqli_real_escape_string($con, $username);
    $password = stripslashes($_POST['password']);
    $password = mysqli_real_escape_string($con, $password);

    $query = "SELECT * FROM `users` WHERE username='$username'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = (bool)$user['is_admin'];

            header("Location: dashboard.php");
            exit();
        } else {
            echo "<p style='color:red;'>Invalid password.</p>";
        }
    } else {
        echo "<p style='color:red;'>User not found.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pretend Photo Website - Login</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <header>
        <i class="fa-solid fa-bug" style="color: #ffffff; font-size: 80px; display: block; align-content: center;margin-right: 15px;padding: 8px 16px;"></i>
        <h1 id='p1' style="color: white; font-size:32px;">
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
                <a href="https://facebook.com" target="_blank"><i class="fa-brands fa-facebook-f" style="color: #ffffff;"></i></a>
                <a href="https://x.com" target="_blank"><i class="fa-brands fa-x-twitter" style="color: #ffffff;"></i></a>
                <a href="https://instagram.com" target="_blank"><i class="fa-brands fa-instagram" style="color: #ffffff;"></i></a>
            </ul>
        </nav>
    </header>

    <div class="form">
        <form method="post" name="login">
            <h1 class="login-title">Login</h1>
            <input type="text" class="login-input" name="username" placeholder="Username" required />
            <input type="password" class="login-input" name="password" placeholder="Password" required />
            <input type="submit" value="Login" name="submit" class="login-button" />
        </form>

        <?php if (isset($error)): ?>
            <p style="color: red; text-align: center;"><?php echo $error; ?></p>
        <?php endif; ?>

       <!-- <br>
        <p>Don't have an account? Create one <a href="signup.php"><u>here</u></a>!</p>-->
    </div>
</body>
</html>
