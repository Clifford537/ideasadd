<?php
session_start();
require '../dbconnection/dbconnection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $idea_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    try {
        // Delete the idea
        $stmt = $conn->prepare("DELETE FROM ideas WHERE idea_id = :idea_id AND user_id = :user_id");
        $stmt->bindParam(':idea_id', $idea_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $_SESSION['success_message'] = "Idea deleted successfully!";
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
    }
}

header('Location: manageideas.php');
exit();
