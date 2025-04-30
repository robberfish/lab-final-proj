<?php
session_start();
require('db.php');
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id']; //Get logged-in user's ID

//query to fetch the user's posted items
$query = "SELECT * FROM items WHERE user_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $user_id); 
$stmt->execute();

//set result
$items_result = $stmt->get_result();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($con, stripslashes($_POST['name']));
    $price = mysqli_real_escape_string($con, stripslashes($_POST['price']));
    $user_id=$user_id = $_SESSION['user_id'];
    //allow user to upload image
    $target_dir = "uploads/"; 
    $image_file = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_ext = strtolower(pathinfo($image_file, PATHINFO_EXTENSION));
    $allowed_exts = ['jpg', 'jpeg', 'png'];

    if (in_array($image_ext, $allowed_exts)) {
        $new_filename = uniqid("img_") . '.' . $image_ext;
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($image_tmp, $target_file)) {
            $image_url = mysqli_real_escape_string($con, $target_file);
            $query = "INSERT INTO `items` (name, image_url, price, user_id) 
                      VALUES ('$name', '$image_url', '$price', '$user_id')";
            $result = mysqli_query($con, $query);
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "Only JPG, JPEG, and PNG are allowed.";
    }
}


?>

<!DOCTYPE html>
<html>
<head>
<title>Pretend Photo Website - Login</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
    <title>Add New Item </title>
    <link rel="stylesheet" href="styles.css">
    <style>
        a { color: black; font-weight: bolder; }
        body { background-image: url("mountains.jpg");
    background-color: gray;
    background-blend-mode: multiply;
    background-repeat: no-repeat;
    background-size: cover;
    background-attachment: fixed;
    margin: 0;}
        h1 { color: white; font-size: 24px; }
        a:hover {
            background-color: rgba(40, 90, 121, 0.21);
            font-weight: bolder;
            text-decoration: underline;
            color: rgb(11, 125, 32);
        }
        .group-buttons {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<form method="POST" action="additem.php" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Red Shoes" required>
    <input type="file" name="image" accept="image/png, image/jpeg" required>
    <input type="text" name="price" placeholder="100.00" required>
    <button type="submit">Add New Item</button>
</form>

</body>
</html>
<?php if ($items_result && mysqli_num_rows($items_result) > 0): ?>
    <div class="postings-section">
        <h1>Your Current Postings</h1>
        <ul id="cart-items">
            <?php while ($item = mysqli_fetch_assoc($items_result)): ?>
                <li class="cart-item">
                    <strong><?php echo htmlspecialchars($item['name']); ?></strong> - $<?php echo htmlspecialchars($item['price']); ?>
                    <form method="POST" action="delete_item.php" style="display:inline;">
                        <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                        <button type="submit">Delete</button>
                    </form>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
<?php else: ?>
    <div class="postings-section">
        <h1>Your Current Postings</h1>
        <p>No items posted yet.</p>
    </div>
<?php endif; ?>


