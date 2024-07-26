<?php
session_start();
require '../dbconnection/dbconnection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../authentication/login');
    exit();
}

$user_id = $_SESSION['user_id'];
$receiverId = isset($_GET['receiver_id']) ? intval($_GET['receiver_id']) : 0;

// Function to fetch messages
function fetchMessages($user_id, $receiverId, $conn) {
    $sql = "SELECT m.*, u1.username AS sender_name, u2.username AS receiver_name
            FROM messages m
            JOIN users u1 ON m.sender_id = u1.user_id
            JOIN users u2 ON m.receiver_id = u2.user_id
            WHERE (m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?)
            ORDER BY m.sent_at ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id, $receiverId, $receiverId, $user_id]);
    
    $messages = [];
    while ($row = $stmt->fetch()) {
        $statusDotColor = $row['read_status'] ? 'green' : 'gray';
        $messageClass = $row['sender_id'] == $user_id ? 'my-message' : 'friend-message';
        $messages[] = "<div class=\"$messageClass\">
                         <p>{$row['message']} <span class=\"status-dot\" style=\"background-color: $statusDotColor;\"></span></p>
                       </div>";
    }
    return $messages;
}

header('Content-Type: text/html');
echo implode('', fetchMessages($user_id, $receiverId, $conn));
?>
