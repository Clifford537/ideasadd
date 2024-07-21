<?php
require '../dbconnection/dbconnection.php';
session_start();

// Password validation function
function validate_password($password) {
    // Minimum password requirements
    $min_length = 8;
    $has_upper = preg_match('/[A-Z]/', $password);
    $has_lower = preg_match('/[a-z]/', $password);
    $has_number = preg_match('/\d/', $password);
    $has_special = preg_match('/[@$!%*?&]/', $password);

    if (strlen($password) < $min_length) {
        return 'Password must be at least ' . $min_length . ' characters long.';
    }
    if (!$has_upper) {
        return 'Password must contain at least one uppercase letter.';
    }
    if (!$has_lower) {
        return 'Password must contain at least one lowercase letter.';
    }
    if (!$has_number) {
        return 'Password must contain at least one number.';
    }
    if (!$has_special) {
        return 'Password must contain at least one special character (@, $, !, %, *, ?, &).';
    }

    return true;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $country = $_POST['country'];

    // Validate password
    $password_validation = validate_password($password);
    if ($password_validation !== true) {
        $_SESSION['error_message'] = $password_validation;
        header('Location: register.php');
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, country) VALUES (:username, :email, :password, :country)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':country', $country);

        $stmt->execute();
        $_SESSION['success_message'] = "Registration successful!";
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
    }

    header('Location: register.php');
    exit();
}
?>
