<?php
session_start();
require '../dbconnection/dbconnection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header('Location: ../authentication/login');
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f1f5f9; /* Light background color */
            margin-top: 60px; /* Space for fixed navbar */
        }
        .navbar {
            background: #343a40; /* Dark grey background for navbar */
        }
        .navbar-brand {
            color: #ffffff;
            font-weight: bold;
        }
        .navbar-nav .nav-link {
            color: #ffffff;
        }
        .navbar-nav .nav-link:hover {
            color: #adb5bd; /* Light grey on hover */
        }
        .idea-card {
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #ffffff; /* White background for cards */
        }
        .card-header {
            font-weight: bold;
            background: #e9ecef;
            border-bottom: 1px solid #dee2e6;
        }
        @media (max-width: 768px) {
            .container {
                padding: 0 15px;
            }
            .navbar {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-lightbulb"></i> Idea Platform</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard"><i class="fas fa-home"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="addidea"><i class="fas fa-plus-circle"></i> Add Idea</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="passwordchange"><i class="fas fa-key"></i> Change Password</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manageideas"><i class="fas fa-cogs"></i> Manage My Ideas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5">
        <h2>Manage Your Ideas</h2>
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $_SESSION['success_message']; ?>
                <?php unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $_SESSION['error_message']; ?>
                <?php unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>
        <?php if (empty($ideas)): ?>
            <p>You have no ideas yet. start creating your ideas   <a class="nav-link" href="addidea"><i class="fas fa-plus-circle"></i> Add Idea</a> </p>
        <?php else: ?>
            <?php foreach ($ideas as $idea): ?>
                <div class="card idea-card">
                    <div class="card-header">
                        <?php echo htmlspecialchars($idea['problem_heading']); ?>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><?php echo htmlspecialchars(substr($idea['description'], 0, 100)) . '...'; ?></p>
                        <a href="updateidea.php?id=<?php echo $idea['idea_id']; ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
                        <a href="deleteidea.php?id=<?php echo $idea['idea_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this idea?')"><i class="fas fa-trash-alt"></i> Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>
