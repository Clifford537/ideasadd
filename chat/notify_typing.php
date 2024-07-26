<?php
session_start();
require '../dbconnection/dbconnection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../authentication/login');
    exit();
}

$user_id = $_SESSION['user_id'];
$receiverId = isset($_GET['receiver_id']) ? intval($_GET['receiver_id']) : 0;

if ($receiverId) {
    $sql = "REPLACE INTO typing_status (user_id, receiver_id, typing) VALUES (?, ?, 1)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id, $receiverId]);

    // Clear typing status after 5 seconds
    $clearSql = "DELETE FROM typing_status WHERE user_id = ? AND receiver_id = ? AND typing = 1";
    $clearStmt = $conn->prepare($clearSql);
    $clearStmt->execute([$user_id, $receiverId]);
}

$conn = null;
?>
