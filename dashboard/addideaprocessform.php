<?php
session_start();
require '../dbconnection/dbconnection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id']; // Get the logged-in user's ID
    $username = $_SESSION['username']; // Get the logged-in user's username
    $country = $_POST['country'];
    $problem_heading = $_POST['problem_heading'];
    $description = $_POST['description'];
    $possible_solution = $_POST['possible_solution'];
    $suggested_tools = $_POST['suggested_tools'];
    $impact_on_economy = $_POST['impact_on_economy'];
    $revenue_generation = $_POST['revenue_generation'];
    $stakeholders = $_POST['stakeholders'];

    try {
        $stmt = $conn->prepare("INSERT INTO ideas (user_id, username, country, problem_heading, description, possible_solution, suggested_tools, impact_on_economy, revenue_generation, stakeholders) VALUES (:user_id, :username, :country, :problem_heading, :description, :possible_solution, :suggested_tools, :impact_on_economy, :revenue_generation, :stakeholders)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':problem_heading', $problem_heading);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':possible_solution', $possible_solution);
        $stmt->bindParam(':suggested_tools', $suggested_tools);
        $stmt->bindParam(':impact_on_economy', $impact_on_economy);
        $stmt->bindParam(':revenue_generation', $revenue_generation);
        $stmt->bindParam(':stakeholders', $stakeholders);

        $stmt->execute();
        $_SESSION['success_message'] = "Idea submitted successfully!";
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
    }

    header('Location: addidea.php');
    exit();
}
?>
