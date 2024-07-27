<?php
session_start();
require '../dbconnection/dbconnection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../authentication/login');
    exit();
}

$currentUserId = $_SESSION['user_id'];

// Fetch the receiver_id from GET request
$receiver_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

// Ensure receiver_id is a friend
try {
    $stmt = $conn->prepare("
        SELECT * FROM friends 
        WHERE (user_id = ? AND friend_user_id = ?) 
           OR (user_id = ? AND friend_user_id = ?)
    ");
    $stmt->execute([$currentUserId, $receiver_id, $receiver_id, $currentUserId]);
    $is_friend = $stmt->rowCount() > 0;

    if ($receiver_id > 0 && !$is_friend) {
        echo "You are not friends with this user.";
        exit();
    }
} catch (PDOException $e) {
    echo "Error checking friendship: " . $e->getMessage();
    exit();
}

// Fetch receiver username
try {
    $stmt = $conn->prepare("SELECT username FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $receiver_id, PDO::PARAM_INT);
    $stmt->execute();
    $receiver = $stmt->fetch(PDO::FETCH_ASSOC);
    $receiver_username = $receiver ? $receiver['username'] : 'Unknown';
} catch (PDOException $e) {
    echo "Error fetching receiver username: " . $e->getMessage();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receiver_id = isset($_POST['receiver_id']) ? intval($_POST['receiver_id']) : 0;
    $message = $_POST['message'];

    if (!empty($message) && $is_friend) {
        try {
            $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
            $stmt->execute([$currentUserId, $receiver_id, $message]);
        } catch (PDOException $e) {
            echo "Message sending failed: " . $e->getMessage();
        }
    } else {
        echo "Message cannot be sent. Either message is empty or the user is not a friend.";
    }
}

// Fetch friends for the sidebar
function displayFriends($currentUserId, $conn) {
    $sql = "SELECT u.user_id, u.username FROM friends f JOIN users u ON f.friend_user_id = u.user_id WHERE f.user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $currentUserId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Friends</h2>";
    echo "<ul>";
    
    foreach ($result as $row) {
        echo "<li><a href='meandyou.php?user_id={$row['user_id']}'>{$row['username']}</a></li>";
    }
    
    echo "</ul>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chat</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <?php displayFriends($currentUserId, $conn); ?>
        </div>
        <div class="chat">
            <?php if ($is_friend) : ?>
                <h2>Chat with <?php echo htmlspecialchars($receiver_username); ?></h2>
                <div class="messages">
                    <?php
                    try {
                        $stmt = $conn->prepare("
                            SELECT m.message_id, m.message, m.sent_at, u1.username AS sender_username
                            FROM messages m
                            JOIN users u1 ON m.sender_id = u1.user_id
                            WHERE (m.sender_id = ? AND m.receiver_id = ?) 
                               OR (m.sender_id = ? AND m.receiver_id = ?)
                            ORDER BY m.sent_at ASC
                        ");
                        $stmt->execute([$currentUserId, $receiver_id, $receiver_id, $currentUserId]);
                        $messages = $stmt->fetchAll();

                        foreach ($messages as $msg) {
                            $alignment = ($msg['sender_username'] === $_SESSION['username']) ? 'sent' : 'received';
                            echo "<div class='message $alignment'>";
                            echo "<strong>{$msg['sender_username']}</strong>: {$msg['message']}";
                            echo "<br><small>{$msg['sent_at']}</small>";
                            echo "</div>";
                        }
                    } catch (PDOException $e) {
                        echo "Query failed: " . $e->getMessage();
                    }
                    ?>
                </div>
                <form method="POST">
                    <input type="hidden" name="receiver_id" value="<?php echo htmlspecialchars($receiver_id); ?>">
                    <textarea name="message" rows="3" placeholder="Type your message..."></textarea>
                    <button type="submit">Send</button>
                </form>
            <?php else : ?>
                <p>Select a friend to start chatting.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
