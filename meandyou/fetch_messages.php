<?php
session_start(); // Start the session
require '../dbconnection/dbconnection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "User not logged in.";
    exit();
}

$currentUserId = $_SESSION['user_id'];
$receiver_id = intval($_GET['receiver_id']);

try {
    $stmt = $conn->prepare("
        SELECT m.*, u.username AS sender_username
        FROM messages m
        JOIN users u ON m.sender_id = u.user_id
        WHERE (m.sender_id = :currentUserId AND m.receiver_id = :receiver_id)
           OR (m.sender_id = :receiver_id AND m.receiver_id = :currentUserId)
        ORDER BY m.sent_at
    ");
    $stmt->bindParam(':currentUserId', $currentUserId, PDO::PARAM_INT);
    $stmt->bindParam(':receiver_id', $receiver_id, PDO::PARAM_INT);
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($messages as $message) {
        echo "<div><strong>{$message['sender_username']}:</strong> {$message['message']}</div>";
        if (!empty($message['file_path'])) {
            echo "<div><a href='{$message['file_path']}' target='_blank'>View File</a></div>";
        }
    }
} catch (PDOException $e) {
    echo "Error fetching messages: " . $e->getMessage();
}
?>
