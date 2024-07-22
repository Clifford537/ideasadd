<?php
require '../dbconnection/dbconnection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $stmt = $conn->prepare("SELECT user_id, username, password FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            header('Location: ../dashboard/dashboard');
            exit();
        } else {
            $_SESSION['login_error'] = "Invalid username or password.";
            header('Location: login.php');
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['login_error'] = "Error: " . $e->getMessage();
        header('Location: login.php');
        exit();
    }
}
?>
