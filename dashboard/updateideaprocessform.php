<?php
session_start();
require '../dbconnection/dbconnection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idea_id = $_POST['idea_id'];
    $problem_heading = $_POST['problem_heading'];
    $description = $_POST['description'];
    $possible_solution = $_POST['possible_solution'];
    $suggested_tools = $_POST['suggested_tools'];
    $impact_on_economy = $_POST['impact_on_economy'];
    $revenue_generation = $_POST['revenue_generation'];
    $stakeholders = $_POST['stakeholders'];
    $user_id = $_SESSION['user_id'];

    try {
        // Update the idea
        $stmt = $conn->prepare("UPDATE ideas SET 
            problem_heading = :problem_heading,
            description = :description,
            possible_solution = :possible_solution,
            suggested_tools = :suggested_tools,
            impact_on_economy = :impact_on_economy,
            revenue_generation = :revenue_generation,
            stakeholders = :stakeholders
            WHERE idea_id = :idea_id AND user_id = :user_id");

        $stmt->bindParam(':problem_heading', $problem_heading);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':possible_solution', $possible_solution);
        $stmt->bindParam(':suggested_tools', $suggested_tools);
        $stmt->bindParam(':impact_on_economy', $impact_on_economy);
        $stmt->bindParam(':revenue_generation', $revenue_generation);
        $stmt->bindParam(':stakeholders', $stakeholders);
        $stmt->bindParam(':idea_id', $idea_id);
        $stmt->bindParam(':user_id', $user_id);

        $stmt->execute();
        $_SESSION['success_message'] = "Idea updated successfully!";
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
    }

    header('Location: dashboard.php');
    exit();
}
?>
