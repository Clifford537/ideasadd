<?php
session_start();
require '../dbconnection/dbconnection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    // Fetch all ideas for the logged-in user
    $stmt = $conn->prepare("SELECT * FROM ideas WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $ideas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Ideas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .idea-card {
            margin-bottom: 20px;
        }
        .card-header {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Manage Your Ideas</h2>
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $_SESSION['success_message']; ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $_SESSION['error_message']; ?>
            </div>
        <?php endif; ?>
        <?php if (empty($ideas)): ?>
            <p>You have no ideas yet.</p>
        <?php else: ?>
            <?php foreach ($ideas as $idea): ?>
                <div class="card idea-card">
                    <div class="card-header">
                        <?php echo htmlspecialchars($idea['problem_heading']); ?>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><?php echo htmlspecialchars(substr($idea['description'], 0, 100)) . '...'; ?></p>
                        <a href="updateidea.php?id=<?php echo $idea['idea_id']; ?>" class="btn btn-warning">Edit</a>
                        <a href="deleteidea.php?id=<?php echo $idea['idea_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this idea?')">Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
