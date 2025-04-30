<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require('db.php'); 

// Only allow access if logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

//query to find all trades that have not been completed
$query = "
    SELECT t.*, 
           CASE 
               WHEN buddy_id = ? THEN 'buddy'
               WHEN owner_id = ? THEN 'owner'
           END as role
    FROM trades t
    WHERE (buddy_id = ? AND buddy_submitted = FALSE)
       OR (owner_id = ? AND owner_submitted = FALSE)
";

$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "iiii", $userId, $userId, $userId, $userId); //binding user_id
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pendingTrades = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Your Trade Half</title>
</head>
<body>
<head>
    <meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <title>
        BEE BUY
    </title>
<style>
    h2{
        font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        color:#ffffff;
        text-align: center;
        font-weight: bolder;
        font-size: 40px;
    }
    button{
        width: 20%;
    }
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
                <li><a href="cart.php">CART</a></li>
                <li><a href="login.php">LOGIN</a></li>
                <li><a href="dashboard.php">ADMIN</a></li>
                <li><a href="additem.php">POST</a></li>
                <li><a href="submit.php"class="active">PENDING</a></li>
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

<h2>Pending Trade Confirmations</h2>
<?php if (count($pendingTrades) === 0): ?>
    <p>No pending confirmations.</p>
<?php else: ?>
    <?php foreach ($pendingTrades as $trade): ?>
        <div style="border: 10px solid rgba(255, 255, 255, 0.3); margin: 10px; padding: 10px;">
            <p><strong>Trade ID:</strong> <?= htmlspecialchars($trade['id']) ?></p>
            <p><strong>Role:</strong> <?= htmlspecialchars(ucfirst($trade['role'])) ?></p>
            <p><strong>Your Half to Submit:</strong> 
                <?php if ($trade['role'] === 'buddy'): ?>
                    <?= htmlspecialchars($trade['buddy_half']) ?>
                <?php elseif ($trade['role'] === 'owner'): ?>
                    <?= htmlspecialchars($trade['owner_half']) ?>
                <?php endif; ?>
            </p>
            <form style= "padding: 0px; background-color:rgba(205, 194, 194, 0);display: flex;flex-wrap: wrap;"
              action="cancel.php" method="POST">
                <input type="hidden" name="trade_id" value="<?= $trade['id'] ?>">
                <button type="submit" onclick="return confirm('Are you sure you want to cancel this trade and reset the items?');">Cancel Trade</button>
            </form>

            <form action="submit_half.php" method="POST" onsubmit="return prepareTradeForm(this);">
    <input type="hidden" name="trade_id" value="<?= $trade['id'] ?>">
    <input type="hidden" name="requested_item_id" value="<?php echo $trade['requested_item_id']; ?>">
    <input type="text" name="submitted_half" placeholder="Enter your half" required>
    <button type="submit">Submit</button>
</form>


        </div>
    <?php endforeach; ?>
<?php endif; ?>
</body>
</html>
