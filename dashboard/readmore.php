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

    try {
        // Fetch the idea details
        $stmt = $conn->prepare("SELECT i.*, u.username FROM ideas i JOIN users u ON i.user_id = u.user_id WHERE i.idea_id = :idea_id");
        $stmt->bindParam(':idea_id', $idea_id);
        $stmt->execute();
        $idea = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$idea) {
            $_SESSION['error_message'] = "Idea not found.";
            header('Location: manageideas.php');
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
        header('Location: manageideas.php');
        exit();
    }
} else {
    $_SESSION['error_message'] = "No idea ID provided.";
    header('Location: manageideas.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Read More - Idea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .idea-detail {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container idea-detail">
        <h2><?php echo htmlspecialchars($idea['problem_heading']); ?></h2>
        <p><strong>Submitted by:</strong> <?php echo htmlspecialchars($idea['username']); ?></p>
        <p><strong>Country:</strong> <?php echo htmlspecialchars($idea['country']); ?></p>
        <p><strong>Description:</strong></p>
        <p><?php echo nl2br(htmlspecialchars($idea['description'])); ?></p>
        <p><strong>Possible Solution:</strong></p>
        <p><?php echo nl2br(htmlspecialchars($idea['possible_solution'])); ?></p>
        <p><strong>Suggested Tools:</strong></p>
        <p><?php echo nl2br(htmlspecialchars($idea['suggested_tools'])); ?></p>
        <p><strong>Impact on Economy:</strong></p>
        <p><?php echo nl2br(htmlspecialchars($idea['impact_on_economy'])); ?></p>
        <p><strong>Revenue Generation:</strong></p>
        <p><?php echo nl2br(htmlspecialchars($idea['revenue_generation'])); ?></p>
        <p><strong>Stakeholders:</strong></p>
        <p><?php echo nl2br(htmlspecialchars($idea['stakeholders'])); ?></p>
        <a href="manageideas.php" class="btn btn-secondary">Back to My Ideas</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
