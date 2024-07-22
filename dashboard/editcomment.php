<?php
session_start();
require '../dbconnection/dbconnection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_POST['comment_id']) && isset($_POST['comment']) && isset($_POST['idea_id'])) {
    $comment_id = $_POST['comment_id'];
    $comment = $_POST['comment'];
    $idea_id = $_POST['idea_id'];
    $user_id = $_SESSION['user_id'];

    try {
        // Update the comment
        $stmt = $conn->prepare("UPDATE comments SET comment = :comment WHERE comment_id = :comment_id AND user_id = :user_id");
        $stmt->bindParam(':comment', $comment);
        $stmt->bindParam(':comment_id', $comment_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        // Redirect back to the idea page
        header("Location: readmore?id=" . $idea_id);
        exit();
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
        header("Location: readmore?id=" . $idea_id);
        exit();
    }
} else {
    $_SESSION['error_message'] = "Invalid request.";
    header('Location: manageideas.php');
    exit();
}
?>
