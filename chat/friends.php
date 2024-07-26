<?php
session_start();
require '../dbconnection/dbconnection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../authentication/login');
    exit();
}

$currentUserId = $_SESSION['user_id'];

// Fetch the logged-in user details
$sql = "SELECT user_id, country FROM users WHERE user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $currentUserId, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $currentUserCountry = $user['country'];
} else {
    die("User not found.");
}

// Function to display users
function displayUsers($currentUserId, $currentUserCountry, $conn) {
    $sql = "SELECT user_id, username, country FROM users WHERE user_id != :user_id ORDER BY country = :country DESC, username ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $currentUserId, PDO::PARAM_INT);
    $stmt->bindParam(':country', $currentUserCountry, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Users</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Username</th><th>Country</th><th>Action</th></tr>";
    
    foreach ($result as $row) {
        echo "<tr>";
        echo "<td>{$row['username']}</td>";
        echo "<td>{$row['country']}</td>";
        echo "<td><button onclick='sendFriendRequest({$row['user_id']})'>Send Friend Request</button></td>";
        echo "</tr>";
    }
    
    echo "</table>";
}

// Function to display pending friend requests
function displayPendingRequests($currentUserId, $conn) {
    $sql = "SELECT fr.request_id, u.username FROM friend_requests fr JOIN users u ON fr.sender_id = u.user_id WHERE fr.receiver_id = :receiver_id AND fr.status = 'pending'";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':receiver_id', $currentUserId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Pending Friend Requests</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Username</th><th>Action</th></tr>";
    
    foreach ($result as $row) {
        echo "<tr>";
        echo "<td>{$row['username']}</td>";
        echo "<td>
                <a href='friendprocess.php?action=accept&request_id={$row['request_id']}'>Accept</a> |
                <a href='friendprocess.php?action=decline&request_id={$row['request_id']}'>Decline</a>
              </td>";
        echo "</tr>";
    }
    
    echo "</table>";
}

// Function to display sent friend requests
function displaySentRequests($currentUserId, $conn) {
    $sql = "SELECT fr.request_id, u.username FROM friend_requests fr JOIN users u ON fr.receiver_id = u.user_id WHERE fr.sender_id = :sender_id AND fr.status = 'pending'";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':sender_id', $currentUserId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Sent Friend Requests</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Username</th><th>Status</th></tr>";
    
    foreach ($result as $row) {
        echo "<tr>";
        echo "<td>{$row['username']}</td>";
        echo "<td>Pending</td>";
        echo "</tr>";
    }
    
    echo "</table>";
}

// Function to display friends
function displayFriends($currentUserId, $conn) {
    $sql = "SELECT u.user_id, u.username FROM friends f JOIN users u ON f.friend_user_id = u.user_id WHERE f.user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $currentUserId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Friends</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Username</th><th>Action</th></tr>";
    
    foreach ($result as $row) {
        echo "<tr>";
        echo "<td>{$row['username']}</td>";
        echo "<td>
                <a href='chatit.php?user_id={$row['user_id']}'>Chat</a> |
                <a href='friendprocess.php?action=unfriend&friend_id={$row['user_id']}' onclick='return confirm(\"Are you sure you want to unfriend this user?\");'>Unfriend</a>
              </td>";
        echo "</tr>";
    }
    
    echo "</table>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friends</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php
    displayFriends($currentUserId, $conn);
    displayPendingRequests($currentUserId, $conn);
    displaySentRequests($currentUserId, $conn);
    displayUsers($currentUserId, $currentUserCountry, $conn);
    ?>

    <!-- Modal -->
    <div id="notificationModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p id="modalMessage"></p>
        </div>
    </div>

    <script>
        function showModal(message) {
            document.getElementById('modalMessage').textContent = message;
            document.getElementById('notificationModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('notificationModal').style.display = 'none';
        }

        function sendFriendRequest(receiverId) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "friendprocess.php?action=send&receiver_id=" + receiverId, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    showModal(xhr.responseText);
                    // Refresh the page to show updated requests and friends list
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            };
            xhr.send();
        }
    </script>
</body>
</html>
