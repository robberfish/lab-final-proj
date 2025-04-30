<?php
session_start();
require('db.php');

//Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

//Get trade ID
if (isset($_POST['trade_id'])) {
    $tradeId = (int) $_POST['trade_id'];

    //Get the trade details
    $stmt = mysqli_prepare($con, "SELECT offered_item_id, requested_item_id FROM trades WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $tradeId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $trade = mysqli_fetch_assoc($result);

    if ($trade) {
        //move both items back to available status
        $updateStmt = mysqli_prepare($con, "UPDATE items SET status = 'available' WHERE id IN (?, ?)");
        mysqli_stmt_bind_param($updateStmt, "ii", $trade['offered_item_id'], $trade['requested_item_id']);
        if (mysqli_stmt_execute($updateStmt)) {
            //allow user to cancel trade
            $deleteTradeStmt = mysqli_prepare($con, "DELETE FROM trades WHERE id = ?");
            mysqli_stmt_bind_param($deleteTradeStmt, "i", $tradeId);
            mysqli_stmt_execute($deleteTradeStmt);

            echo "Trade has been cancelled and items are now available.";
            header("Location: submit.php"); //redirect back to the pending page
            exit();
        } else {
            echo "Error resetting items to available: " . mysqli_error($con);
        }
    } else {
        echo "Trade not found.";
    }
} else {
    echo "Invalid trade ID.";
}
?>
