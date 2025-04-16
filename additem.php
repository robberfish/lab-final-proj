<?php

require('db.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = stripslashes($_POST['name']);
    $name = mysqli_real_escape_string($con, $name);

    $image_url = stripslashes($_POST['image_url']);
    $image_url = mysqli_real_escape_string($con, $image_url);

    $price = stripslashes($_POST['price']);
    $price = mysqli_real_escape_string($con, $price);

    //Insert item into database
    $query = "INSERT INTO `items` (name, image_url, price) 
              VALUES ('$name', '$image_url', '$price')";
    $result = mysqli_query($con, $query);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Add New Item - Admin</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        a { color: black; font-weight: bolder; }
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
        }
        .group-buttons {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="form">
        <h1>Add New Item</h1>
        <form method="POST" action="additem.php">
            <input type="text" name="name" placeholder="Red Shoes" required>
            <input type="text" name="image_url" placeholder="Picture URL" required>
            <input type="text" name="price" placeholder="100.00" required>
            <button type="submit">Add New Item</button>
        </form>
        <div class="group-buttons">
            <button><a href="itemdash.php">Item Dashboard</a></button>
        </div>
    </div>
</body>
</html>
