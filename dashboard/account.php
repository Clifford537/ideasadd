<?php
session_start();
require '../dbconnection/dbconnection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../authentication/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user ideas from database
try {
    $stmt = $conn->prepare("SELECT * FROM ideas WHERE user_id = :user_id ORDER BY idea_id DESC");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $ideas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Error fetching ideas: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .nav-pills .nav-link {
            border-radius: 0.375rem;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .card-body {
            padding: 20px;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .card-text {
            font-size: 1rem;
            color: #6c757d;
        }
        .read-more-btn {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .read-more-btn:hover {
            text-decoration: underline;
        }
        .btn-primary {
            margin-bottom: 20px;
        }
        @media (max-width: 768px) {
            .row {
                flex-direction: column;
            }
            .col-md-3, .col-md-9 {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <nav id="navbar" class="col-md-3">
                <div class="nav flex-column nav-pills">
                    <a class="nav-link active" href="dashboard.php">Dashboard</a>
                    <a class="nav-link" href="addidea.php">Add Idea</a>
                    <a class="nav-link" href="change_password.php">Change Password</a>
                    <a class="nav-link" href="manage_ideas.php">Manage My Ideas</a>
                </div>
            </nav>
            <main class="col-md-9">
                <h1 class="mb-4">Your Ideas</h1>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($ideas)): ?>
                    <?php foreach ($ideas as $idea): ?>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($idea['problem_heading']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars(substr($idea['description'], 0, 100)); ?>...</p>
                                <a href="readmore.php?id=<?php echo $idea['idea_id']; ?>" class="read-more-btn">Read More</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No ideas found. <a href="addidea.php">Add your first idea</a></p>
                <?php endif; ?>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
