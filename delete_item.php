<?php
session_start();
require('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['item_id'])) {
    $item_id = (int)$_POST['item_id'];
    $user_id = $_SESSION['user_id'];

    // Only delete if item belongs to the user
    $query = "DELETE FROM items WHERE id = $item_id AND user_id = $user_id";
    mysqli_query($con, $query);
}
header("Location: additem.php");
exit();
?>