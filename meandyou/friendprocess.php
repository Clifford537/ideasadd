<?php
session_start();
require '../dbconnection/dbconnection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../authentication/login');
    exit();
}

$currentUserId = $_SESSION['user_id'];

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    if ($action == 'send' && isset($_GET['receiver_id'])) {
        $receiverId = intval($_GET['receiver_id']);
        
        // Check if a request already exists
        $sql = "SELECT * FROM friend_requests WHERE sender_id = :sender_id AND receiver_id = :receiver_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':sender_id', $currentUserId, PDO::PARAM_INT);
        $stmt->bindParam(':receiver_id', $receiverId, PDO::PARAM_INT);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            echo "Request already sent.";
        } else {
            // Insert new friend request
            $sql = "INSERT INTO friend_requests (sender_id, receiver_id, status) VALUES (:sender_id, :receiver_id, 'pending')";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':sender_id', $currentUserId, PDO::PARAM_INT);
            $stmt->bindParam(':receiver_id', $receiverId, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                echo "Friend request sent successfully.";
            } else {
                echo "Error sending friend request.";
            }
        }
    }

    if ($action == 'accept' && isset($_GET['request_id'])) {
        $requestId = intval($_GET['request_id']);
        
        // Get request details
        $sql = "SELECT * FROM friend_requests WHERE request_id = :request_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':request_id', $requestId, PDO::PARAM_INT);
        $stmt->execute();
        
        if ($stmt->rowCount() === 0) {
            echo "Request not found.";
        } else {
            $request = $stmt->fetch(PDO::FETCH_ASSOC);
            $senderId = $request['sender_id'];
            $receiverId = $request['receiver_id'];
            
            // Insert into friends table
            $sql = "INSERT INTO friends (user_id, friend_user_id) VALUES (:user_id, :friend_user_id), (:friend_user_id, :user_id)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $senderId, PDO::PARAM_INT);
            $stmt->bindParam(':friend_user_id', $receiverId, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                // Update friend request status
                $sql = "UPDATE friend_requests SET status = 'accepted' WHERE request_id = :request_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':request_id', $requestId, PDO::PARAM_INT);
                $stmt->execute();
                
                echo "Friend request accepted.";
            } else {
                echo "Error accepting friend request.";
            }
        }
    }

    if ($action == 'decline' && isset($_GET['request_id'])) {
        $requestId = intval($_GET['request_id']);
        
        // Update friend request status
        $sql = "UPDATE friend_requests SET status = 'declined' WHERE request_id = :request_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':request_id', $requestId, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            echo "Friend request declined.";
        } else {
            echo "Error declining friend request.";
        }
    }

    if ($action == 'unfriend' && isset($_GET['friend_id'])) {
        $friendId = intval($_GET['friend_id']);
        
        // Remove from friends table
        $sql = "DELETE FROM friends WHERE (user_id = :user_id AND friend_user_id = :friend_user_id) OR (user_id = :friend_user_id AND friend_user_id = :user_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $currentUserId, PDO::PARAM_INT);
        $stmt->bindParam(':friend_user_id', $friendId, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            echo "Unfriended successfully.";
        } else {
            echo "Error unfriending user.";
        }
    }
}

$conn = null; // Close PDO connection
?>
