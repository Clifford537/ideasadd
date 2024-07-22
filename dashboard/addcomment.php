<?php
session_start();
require '../dbconnection/dbconnection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    $_SESSION['error_message'] = "You need to log in to add a comment.";
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $idea_id = isset($_POST['idea_id']) ? intval($_POST['idea_id']) : 0;
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

    // Validate input
    if (empty($comment)) {
        $_SESSION['error_message'] = "Comment cannot be empty.";
        header('Location: readmore.php?id=' . $idea_id);
        exit();
    }

    try {
        // Insert comment into the database
        $stmt = $conn->prepare("INSERT INTO comments (user_id, idea_id, comment, created_at) VALUES (:user_id, :idea_id, :comment, NOW())");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':idea_id', $idea_id);
        $stmt->bindParam(':comment', $comment);
        $stmt->execute();

        $_SESSION['success_message'] = "Comment added successfully.";
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
    }

    header('Location: readmore.php?id=' . $idea_id);
    exit();
} else {
    $_SESSION['error_message'] = "Invalid request method.";
    header('Location: manageideas.php');
    exit();
}
