=<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require('db.php');
require('auth_session.php');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) ||  $_SESSION['is_admin'] !== true) {
    header("Location: login.php");
    exit();
}

// Fetch all users to populate the buddy dropdown
$usersResult = mysqli_query($con, "SELECT id, username FROM users");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = stripslashes($_POST['username']);
    $username = mysqli_real_escape_string($con, $username);
    $email = stripslashes($_POST['email']);
    $email = mysqli_real_escape_string($con, $email);
    $phone = stripslashes($_POST['phone']);
    $phone = mysqli_real_escape_string($con, $phone);
    $bday = stripslashes($_POST['bday']);
    $bday = mysqli_real_escape_string($con, $bday);
    $password = stripslashes($_POST['password']); 
    $password = mysqli_real_escape_string($con, $password);
    $buddyId = (int) $_POST['buddy_id'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $create_datetime = date("Y-m-d H:i:s");
    $is_admin = 0;

    try {
        $query = "INSERT INTO `users` (username, password, email, phone, birthday, create_datetime, is_admin, buddy_id)
                  VALUES ('$username', '$hashed_password','$email','$phone', '$bday','$create_datetime', '$is_admin', '$buddyId')";
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
    <meta charset="utf-8">
    <title>Add New User - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
     <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/css/fresh-bootstrap-table.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="styles.css">
  <link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet">
  <link href="http://fonts.googleapis.com/css?family=Roboto:400,700,300" rel="stylesheet" type="text/css">
    <style>
        a { color: black; font-weight: bolder; }
        select {
            width: 100%;
            padding: 12px 14px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            background-color: #fff;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            font-size: 14px;
            font-weight: 300;
            color: #333;
            appearance: none;
        }
        body { background-color: white; background-image: none; }
        h1 { color: black; font-size: 24px; }
        a:hover {
            background-color: rgba(40, 90, 121, 0.21);
            font-weight: bolder;
            text-decoration: underline;
            color: rgb(11, 125, 32);
        }
        button {
            border: none;
            padding: 10px 12px;
            margin: 0 10px;
            font-weight: bolder;
            width: 20%;
        }
        .group-buttons {
            text-align: center;
            margin-top: 20px;
            width: 20%;
        }
        
    </style>
</head>
<body>
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
                <li><a href="cart.php" >CART</a></li>
                <li><a href="login.php">LOGIN</a></li>
                <li><a href="dashboard.php">ADMIN</a></li>
                <li><a href="additem.php"class="active">POST</a></li>
                <li><a href="submit.php">PENDING</a></li>
                
                </ul>
        </nav>
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
</header>
    <div class="form">
        <h1>Add New User</h1>
        <form method="POST" action="adduser.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="000-000-0001" required>
            <input type="date" name="bday" placeholder="Birthday" required>
            <input type="password" name="password" placeholder="Password" required>

            <label for="buddy_id">Select Buddy:</label>
            <select name="buddy_id" required>
                <option value="">-- Select a buddy --</option>
                <?php while ($user = mysqli_fetch_assoc($usersResult)): ?>
                    <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['username']) ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Add New User</button>
            <button><a href="dashboard.php">Dashboard</a></button>
        </form>

        <?php if (isset($error)) { echo "<p style='color:black;'>$error</p>"; } ?>
        <?php if (isset($success)) { echo "<p style='color:black;'>$success</p>"; } ?>
    </div>
</body>
</html>
