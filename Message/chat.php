<?php
session_start();
require '../dbconnection/dbconnection.php';

// Check if user is logged in
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

// Insert new message
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = $_POST['message'];
    
    $sql = "INSERT INTO messages (sender_id, receiver_id, message, read_status) VALUES (?, ?, ?, 0)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id, $receiverId, $message]);
    
    exit; // Exit after inserting the message
}

// Update message status to read
if ($receiverId) {
    $sql = "UPDATE messages SET read_status = 1 WHERE sender_id = ? AND receiver_id = ? AND read_status = 0";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$receiverId, $user_id]);
}

$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .container { display: flex; height: 100vh; margin: 0; }
        .sidebar { width: 300px; background: #f4f4f4; padding: 10px; box-sizing: border-box; border-right: 1px solid #ddd; }
        .chat { flex: 1; display: flex; flex-direction: column; }
        .chat-messages { flex: 1; overflow-y: auto; border: 1px solid #ddd; padding: 10px; box-sizing: border-box; }
        .my-message { text-align: right; }
        .friend-message { text-align: left; }
        .status-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
        .message-form { display: flex; padding: 10px; background: #fff; border-top: 1px solid #ddd; box-sizing: border-box; }
        .message-form textarea { flex: 1; padding: 5px; margin-right: 10px; box-sizing: border-box; }
        .message-form button { width: 100px; padding: 5px; }
        .sidebar ul { list-style: none; padding: 0; }
        .sidebar ul li { margin-bottom: 10px; }
        .sidebar ul li a { text-decoration: none; color: #007bff; }
        .sidebar ul li a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="container">
    <div class="sidebar">
        <?php
        // Fetch and display friends list
        require '../dbconnection/dbconnection.php';
        $conn = new PDO("mysql:host=localhost;dbname=ideas", "root", "");
        $sql = "SELECT u.user_id, u.username FROM friends f JOIN users u ON f.friend_user_id = u.user_id WHERE f.user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id]);

        echo "<h2>Friends</h2>";
        echo "<ul>";
        while ($row = $stmt->fetch()) {
            echo "<li><a href='chat.php?receiver_id={$row['user_id']}'>{$row['username']}</a></li>";
        }
        echo "</ul>";
        ?>
    </div>
    <div class="chat">
        <div id="chat-messages" class="chat-messages">
            <?php echo implode('', fetchMessages($user_id, $receiverId, $conn)); ?>
        </div>
        <form id="message-form" class="message-form" method="post">
            <textarea name="message" rows="3" placeholder="Type a message..."></textarea>
            <button type="submit">Send</button>
        </form>
    </div>
</div>
<script>
    // Function to fetch messages
    function fetchMessages() {
        fetch('fetch_messages.php?receiver_id=<?php echo $receiverId; ?>')
        .then(response => response.text())
        .then(data => {
            document.getElementById('chat-messages').innerHTML = data;
            document.querySelector('.chat-messages').scrollTop = document.querySelector('.chat-messages').scrollHeight;
        });
    }

    // Function to notify typing
    function notifyTyping() {
        fetch('notify_typing.php?receiver_id=<?php echo $receiverId; ?>', { method: 'POST' });
    }

    // Handle form submission
    document.getElementById('message-form').addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        fetch('chat.php?receiver_id=<?php echo $receiverId; ?>', {
            method: 'POST',
            body: formData
        })
        .then(() => {
            document.querySelector('textarea').value = '';
            fetchMessages();
        });
    });

    // Handle typing detection
    var typingTimeout;
    document.querySelector('textarea').addEventListener('input', function() {
        clearTimeout(typingTimeout);
        notifyTyping();
        typingTimeout = setTimeout(function() {
            fetchMessages();
        }, 5000);
    });

    // Fetch messages every 5 seconds
    setInterval(fetchMessages, 5000);
</script>

</body>
</html>
