<?php
session_start();
require '../dbconnection/dbconnection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['idea_id'])) {
        $idea_id = $_POST['idea_id'];

        try {
            // Check if the user has already liked this idea
            $stmt = $conn->prepare("SELECT * FROM likes WHERE idea_id = :idea_id AND user_id = :user_id");
            $stmt->bindParam(':idea_id', $idea_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // User has already liked this idea; remove the like
                $stmt = $conn->prepare("DELETE FROM likes WHERE idea_id = :idea_id AND user_id = :user_id");
                $stmt->bindParam(':idea_id', $idea_id);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->execute();
                $_SESSION['success_message'] = "Like removed.";
            } else {
                // Add a new like
                $stmt = $conn->prepare("INSERT INTO likes (idea_id, user_id, created_at) VALUES (:idea_id, :user_id, NOW())");
                $stmt->bindParam(':idea_id', $idea_id);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->execute();
                $_SESSION['success_message'] = "Idea liked.";
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Error: " . $e->getMessage();
        }
    } else {
        $_SESSION['error_message'] = "Invalid input.";
    }
} else {
    $_SESSION['error_message'] = "Invalid request method.";
}

header("Location: readmore.php?id=$idea_id");
exit();
?>
