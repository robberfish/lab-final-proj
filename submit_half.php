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

    // Fetch trade details
    $stmt = $con->prepare("SELECT requester_id, buddy_id, owner_id, offered_item_id, requested_item_id FROM trades WHERE id = ?");
    $stmt->bind_param("i", $tradeId);
    $stmt->execute();
    $tradeResult = $stmt->get_result();
    $trade = $tradeResult->fetch_assoc();
    $stmt->close();

    if (!$trade) {
        die("Trade not found.");
    }

    $requesterId = $trade['requester_id'];
    $buddyId = $trade['buddy_id'];
    $ownerId = $trade['owner_id'];
    $offeredItemId = $trade['offered_item_id'];
    $requestedItemId = $trade['requested_item_id'];

    // Validate requested item if present
    if (!empty($requestedItemId)) {
        $check = $con->prepare("SELECT id FROM items WHERE id = ?");
        $check->bind_param("i", $requestedItemId);
        $check->execute();
        $check->store_result();
        if ($check->num_rows === 0) {
            die("Invalid item ID submitted: $requestedItemId");
        }
        $check->close();
    }

    // Determine which side is submitting
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

    // Check if both sides have submitted
    $stmt = $con->prepare("SELECT owner_submitted, buddy_submitted FROM trades WHERE id = ?");
    $stmt->bind_param("i", $tradeId);
    $stmt->execute();
    $stmt->bind_result($ownerSubmitted, $buddySubmitted);
    $stmt->fetch();
    $stmt->close();

    if ($ownerSubmitted && $buddySubmitted) {
        // Mark trade complete
        $stmt = $con->prepare("UPDATE trades SET status = 'complete', completed_at = NOW() WHERE id = ?");
        $stmt->bind_param("i", $tradeId);
        $stmt->execute();
        $stmt->close();

        // Mark items as unavailable
        $stmt = $con->prepare("UPDATE items SET status = 'unavailable' WHERE id IN (?, ?)");
        $stmt->bind_param("ii", $offeredItemId, $requestedItemId);
        $stmt->execute();
        $stmt->close();

        // Get buddy of owner (X’s buddy)
        $stmt = $con->prepare("SELECT buddy_id FROM users WHERE id = ?");
        $stmt->bind_param("i", $ownerId);
        $stmt->execute();
        $stmt->bind_result($buddyOfX);
        $stmt->fetch();
        $stmt->close();

        if (!$buddyOfX) {
            die("Buddy of owner not found. Cannot complete trade.");
        }

        // Transfer item ownership
$stmt = $con->prepare("UPDATE items SET user_id = CASE
WHEN id = ? THEN ?  -- Item 1 (from X) → goes to A (requester)
WHEN id = ? THEN ?  -- Item 2 (from B) → goes to X’s buddy (Y)
END
WHERE id IN (?, ?)");
$stmt->bind_param(
"iiiiii",
$requestedItemId, $requesterId,  // item 1 → A (requester)
$offeredItemId, $buddyOfX,       // item 2 → Y (X's buddy)
$requestedItemId, $offeredItemId
);
$stmt->execute();
$stmt->close();
    }

    header("Location: submit.php");
    exit();
}
?>
