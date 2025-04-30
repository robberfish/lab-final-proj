<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('db.php');
session_start();

$message = ""; // For showing feedback

//Get diff options for buddies for dropdown
$buddyQuery = "SELECT id, username FROM users";
$buddyResult = mysqli_query($con, $buddyQuery);

//submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($con, trim($_POST['username']));
    $email    = mysqli_real_escape_string($con, trim($_POST['email']));
    $password = mysqli_real_escape_string($con, trim($_POST['password']));
    $phone    = mysqli_real_escape_string($con, trim($_POST['phone']));
    $bday     = mysqli_real_escape_string($con, trim($_POST['bday']));
    $create_datetime = date("Y-m-d H:i:s");
    $buddy_id = !empty($_POST['buddy_id']) ? intval($_POST['buddy_id']) : null;
    $is_admin = 0;

    if (empty($username) || empty($password)) {
        $message = "Please fill in all required fields.";
    } else {
        //Check for duplicate username
        $check = $con->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "Username already taken.";
        } else {
            // Username is unique, insert new user
            $check->close();
            $stmt = $con->prepare("INSERT INTO users (username, password, email, phone, birthday, create_datetime, is_admin, buddy_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param("ssssssii", $username, $hashed, $email, $phone, $bday, $create_datetime, $is_admin, $buddy_id);
            if ($stmt->execute()) {
                $message = "Signup successful!";
            } else {
                $message = "Error: " . $con->error;
            }
            $stmt->close();
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Pretend Photo Website</title>
    <link rel="stylesheet" href="styles.css">
    <style>select {
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
</style>
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
                <li><a href="cart.php">CART</a></li>
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

    <h1>Sign Up</h1>
    <?php if (!empty($message)): ?>
    <p style="color: white;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; //show error messages ?> 

    <form method="POST" action=""> 
      
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="Phone number" required>
        <p>Birthday:</p><input type="date" name="bday" placeholder="Birthday" required><br>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <label for="buddy">Select a Buddy:</label>
        <select name="buddy_id" id="buddy">
            <option value="">-- No Buddy --</option>
            <?php while ($buddy = mysqli_fetch_assoc($buddyResult)): ?>
                <option value="<?= $buddy['id'] ?>"><?= htmlspecialchars($buddy['username']) ?></option>
            <?php endwhile; ?>
        </select>
        <input type="submit" value="Register">
    </form>
    <p>Already have an account? Login <a href="login.php"><u>here</u></a>!</p>

</body>
</html>
