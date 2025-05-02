<?php
session_start();
require('db.php');


// Only allow access if logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
//buddy id
$buddyId = null;
$buddyItems = [];
$buddyStmt = mysqli_prepare($con, "SELECT buddy_id FROM users WHERE id = ?");
mysqli_stmt_bind_param($buddyStmt, "i", $user_id);
mysqli_stmt_execute($buddyStmt);
$buddyResult = mysqli_stmt_get_result($buddyStmt);
if ($buddyRow = mysqli_fetch_assoc($buddyResult)) {
    $buddyId = $buddyRow['buddy_id'];
}
//get buddy items 
if ($buddyId) {
    $buddyItemsStmt = mysqli_prepare($con, "SELECT id, name FROM items WHERE user_id = ? AND status = 'available'");
    mysqli_stmt_bind_param($buddyItemsStmt, "i", $buddyId);
    mysqli_stmt_execute($buddyItemsStmt);
    $buddyItemsResult = mysqli_stmt_get_result($buddyItemsStmt);
    while ($row = mysqli_fetch_assoc($buddyItemsResult)) {
        $buddyItems[] = $row;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BEE BUY</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
     <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/css/fresh-bootstrap-table.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet">
  <link href="http://fonts.googleapis.com/css?family=Roboto:400,700,300" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="styles.css">
    <style>
        h2 {
            font-family: 'Lucida Sans', sans-serif;
            color:#ffffff;
            text-align: center;
            font-weight: bolder;
            font-size: 40px;
        }
        button { width: 20%; }
        select {
            width: 100%;
            padding: 12px 14px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            background-color: #fff;
            font-family: 'Lucida Sans', sans-serif;
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
                <li><a href="contactus.html" >CONTACT</a></li>
                <li><a href="cart.php"class="active">CART</a></li>
                <li><a href="login.php">LOGIN</a></li>
                <li><a href="dashboard.php">ADMIN</a></li>
                <li><a href="additem.php">POST</a></li>
                <li><a href="submit.php">PENDING</a></li>
                <a href="https://facebook.com" target="_blank"> 
                    <i class="fa-brands fa-facebook-f" style="color: #ffffff; align-content: center;margin-right: 15px;padding: 8px 16px; border-radius: 0px;"></i>
                </a><br>
                <a href="https://x.com" target="_blank"> 
                    <i class="fa-brands fa-x-twitter" style="color: #ffffff; align-content: center;margin-right: 15px;padding: 8px 16px; border-radius: 0px; text-decoration: none;"></i>
                </a><br>
                <a href="https://instagram.com" target="_blank"> 
                    <i class="fa-brands fa-instagram" style="color: #ffffff; align-content: center;margin-right: 15px;padding: 8px 16px; border-radius: 0px;"></i>
                </a>
                </ul>
        </nav></header>
        <div class="card" style="background-color: rgba(0, 0, 0, 0.4); text-align: center;">
    <div class="card-header">
        <h2>Trade Pending</h2>
        <p style="font-size: small;">All trades include a 15% transaction cost</p>
        <ul id="cart-items"></ul>
        <p id="cart-total"></p>
        <p id="transaction-total" style="font-size: large;"></p>
    </div>

    <script>
    function prepareTradeForm(form) {
        const cart = JSON.parse(localStorage.getItem("cart")) || [];
        if (cart.length === 0) {
            alert("Your cart's empty! Please add an item to request a trade for.");
            return false;
        }
        const hiddenInput = form.querySelector(".requested_item_id");
        if (!hiddenInput) {
            alert("Requested item Id input is missing.");
            return false;
        }
        hiddenInput.value = cart[0].id;

        //find what the total is
        let total = 0;
        cart.forEach(item =>{
            total += parseFloat(item.price.toString().replace("$", ""));
        });
        const transactionTotal = total + total * 0.15;
        document.getElementById("transaction_total_input").value = transactionTotal.toFixed(2);
        return true;
    }
    function loadCart(){
        let cart = JSON.parse(localStorage.getItem("cart")) || [];
        let cartList = document.getElementById("cart-items");
        cartList.innerHTML = "";
        let total = 0;

        if (cart.length ===0){
            let emptyMessage = document.createElement("li");
            emptyMessage.textContent = "Your cart is empty";
            cartList.appendChild(emptyMessage);
        } else {
            cart.forEach((item, index) => {
                let listItem = document.createElement("li");
                listItem.className = "cart-item";
                let itemContainer = document.createElement("div");
                let itemText = document.createElement("span");
                itemText.textContent = `${item.name} - ${item.price}`;
                total += parseFloat(item.price.toString().replace("$", ""));
                let removeButton = document.createElement("button");
                removeButton.textContent = "Remove";
                removeButton.onclick = function () {
                    cart.splice(index, 1);
                    localStorage.setItem("cart", JSON.stringify(cart));
                    loadCart();
                };
                itemContainer.appendChild(itemText);
                itemContainer.appendChild(removeButton);
                listItem.appendChild(itemContainer);
                cartList.appendChild(listItem);
            });
        }

        document.getElementById("cart-total").textContent = `Cart total:${total}`;
        let transactionTotal = total + total * 0.15;
        document.getElementById("transaction-total").textContent = `Transaction total:${transactionTotal.toFixed(2)}`;
    }

    document.addEventListener("DOMContentLoaded", loadCart);
    </script>

    <?php
    //process in POST so it loads accurately
    $transactionTotal = isset($_POST['transaction_total']) ? (float)$_POST['transaction_total'] : 0;
    ?>
    <?php if ($buddyId && count($buddyItems) > 0): ?>
        <div class="card-header"><h2>For Your Buddy's Item</h2></div>
        <form method="POST" action="" onsubmit="return prepareTradeForm(this);">
            <p>Select your buddy's item to offer:</p>
            <select name="offered_item_id" id="offered_item" required>
                <option value="">-- Select an Item --</option>
                <?php foreach ($buddyItems as $item): ?>
                    <option value="<?= $item['id'] ?>" 
                        <?= $transactionTotal > 0 && $item['price']< $transactionTotal ? "disabled" : "" ?>>
                        <?= htmlspecialchars($item['name']) ?> - <?= htmlspecialchars($item['price']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="hidden" name="transaction_total" id="transaction_total_input">
            <input type="hidden" name="requested_item_id" id="requested_item_id" class="requested_item_id" required>
            <button type="submit">Trade and Share Codes</button>
        </form>
    <?php else: ?>
        <h2>Your Buddy's Items</h2>
        <p>Your buddy hasn't posted any available items yet.</p>
    <?php endif; ?>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['offered_item_id'], $_POST['requested_item_id'])){
        $offeredItemId = (int)$_POST['offered_item_id'];
        $requestedItemId = (int)$_POST['requested_item_id'];
        $requesterId = $_SESSION['user_id'];
        $transactionTotal = (float)$_POST['transaction_total'];
        //get the details for my offered item
        $offeredStmt = mysqli_prepare($con, "SELECT user_id, price FROM items WHERE id = ?");
        mysqli_stmt_bind_param($offeredStmt, "i", $offeredItemId);
        mysqli_stmt_execute($offeredStmt);
        $offeredResult = mysqli_stmt_get_result($offeredStmt);
        $offeredItem = mysqli_fetch_assoc($offeredResult);
        if (!$offeredItem) {
            echo "Offered item not found.";
            exit;
        }

        $buddyId = $offeredItem['user_id'];
        $offeredPrice = (float)$offeredItem['price'];
        //does it match/exceed the price?
        if ($offeredPrice < $transactionTotal) {
            echo "<p>Trade rejected: offered item ($offeredPrice) is below the required transaction value ($transactionTotal). Try again</p>";
            exit;
        }
        //get the owner for the item
        $requestedStmt = mysqli_prepare($con, "SELECT user_id FROM items WHERE id = ?");
        mysqli_stmt_bind_param($requestedStmt, "i", $requestedItemId);
        mysqli_stmt_execute($requestedStmt);
        $requestedResult = mysqli_stmt_get_result($requestedStmt);
        $requestedItem = mysqli_fetch_assoc($requestedResult);
        if (!$requestedItem){
            echo "Requested item not found with ID: " . $requestedItemId;
            exit;
        }
        $ownerId = $requestedItem['user_id'];

        //get hash key and split it for the two people involved
        $fullKey = bin2hex(random_bytes(8));
        $buddyHalf = substr($fullKey, 0, 8);
        $ownerHalf = substr($fullKey, 8,8);

        //Insert into trades db
        $insertStmt = mysqli_prepare($con, "INSERT INTO trades 
            (requester_id, offered_item_id, requested_item_id, buddy_id, owner_id, buddy_half, owner_half, full_key)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($insertStmt, "iiiissss", $requesterId, $offeredItemId, $requestedItemId, $buddyId, $ownerId, $buddyHalf, $ownerHalf, $fullKey);
        mysqli_stmt_execute($insertStmt);

        //Mark both items on hold
        $updateStatusStmt = mysqli_prepare($con, "UPDATE items SET status = 'on hold' WHERE id IN (?, ?)");
        mysqli_stmt_bind_param($updateStatusStmt, "ii", $offeredItemId, $requestedItemId);
        mysqli_stmt_execute($updateStatusStmt);

        echo "<p>Trade offered! Hash code is $buddyHalf.</p>";
        echo "<p>Your code has been shared with your buddy to submit to the database.</p>";
    }
    ?>
</div>
