<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = (int) $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tradeId = $_POST['trade_id'];
    $submittedHalf = trim($_POST['submitted_half']);

    if (empty($tradeId) || empty($submittedHalf)) {
        die("Missing required fields.");
    }

    //Fetch trade details
    $stmt = $con->prepare("SELECT owner_id, buddy_id, requested_item_id, offered_item_id, status FROM trades WHERE id = ?");
    $stmt->bind_param("i", $tradeId);
    $stmt->execute();
    $stmt->bind_result($ownerId, $buddyId, $requestedItemId, $offeredItemId, $tradeStatus);
    $stmt->fetch();
    $stmt->close();

    if (!$ownerId || !$buddyId) {
        die("Invalid trade.");
    }

    //Validate requested item
    if ($requestedItemId !== null) {
        $check = $con->prepare("SELECT id FROM items WHERE id = ?");
        $check->bind_param("i", $requestedItemId);
        $check->execute();
        $check->store_result();
        if ($check->num_rows === 0) {
            die("Invalid item ID submitted: $requestedItemId");
        }
        $check->close();
    }

    //Determine role and update
    if ($userId === $ownerId) {
        $updateQuery = "UPDATE trades SET owner_half = ?, owner_submitted = 1, requested_item_id = ? WHERE id = ?";
    } elseif ($userId === $buddyId) {
        $updateQuery = "UPDATE trades SET buddy_half = ?, buddy_submitted = 1, requested_item_id = ? WHERE id = ?";
    } else {
        die("Unauthorized action.");
    }

    $stmt = $con->prepare($updateQuery);
    $stmt->bind_param("sii", $submittedHalf, $requestedItemId, $tradeId);
    $stmt->execute();
    $stmt->close();

    //see if both halves have been submitted
    $stmt = $con->prepare("SELECT owner_submitted, buddy_submitted FROM trades WHERE id = ?");
    $stmt->bind_param("i", $tradeId);
    $stmt->execute();
    $stmt->bind_result($ownerSubmitted, $buddySubmitted);
    $stmt->fetch();
    $stmt->close();

    if ($ownerSubmitted && $buddySubmitted) {
        //trade is complete, add timestamp in
        $stmt = $con->prepare("UPDATE trades SET status = 'complete', completed_at = NOW() WHERE id = ?");
        $stmt->bind_param("i", $tradeId);
        $stmt->execute();
        $stmt->close();

        //remove both items froms shop by marking unavailable
        $stmt = $con->prepare("UPDATE items SET status = 'unavailable' WHERE id IN (?, ?)");
        $stmt->bind_param("ii", $offeredItemId, $requestedItemId);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: submit.php");
    exit();
}
?>
