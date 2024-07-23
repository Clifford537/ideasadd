<?php
session_start();
require '../dbconnection/dbconnection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../authentication/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details and all ideas from database
try {
    // Fetch username
    $stmt = $conn->prepare("SELECT username FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $username = htmlspecialchars($user['username']);

    // Fetch all ideas along with counts of likes and comments
    $stmt = $conn->prepare("
        SELECT 
            ideas.*, 
            users.username AS author_username,
            COALESCE(like_counts.like_count, 0) AS like_count,
            COALESCE(comment_counts.comment_count, 0) AS comment_count
        FROM 
            ideas
        JOIN 
            users ON ideas.user_id = users.user_id
        LEFT JOIN (
            SELECT 
                idea_id, 
                COUNT(*) AS like_count 
            FROM 
                likes 
            GROUP BY 
                idea_id
        ) AS like_counts ON ideas.idea_id = like_counts.idea_id
        LEFT JOIN (
            SELECT 
                idea_id, 
                COUNT(*) AS comment_count 
            FROM 
                comments 
            GROUP BY 
                idea_id
        ) AS comment_counts ON ideas.idea_id = comment_counts.idea_id
        ORDER BY 
            ideas.idea_id DESC
    ");
    $stmt->execute();
    $ideas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Error fetching data: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
        .container {
            max-width: 1200px;
        }
        .idea-card {
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #ffffff; /* White background for cards */
        }
        .read-more-btn {
            color: #495057; /* Dark grey color for readability */
            text-decoration: none;
            font-weight: bold;
        }
        .read-more-btn:hover {
            text-decoration: underline;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .card-text {
            font-size: 1rem;
            color: #6c757d; /* Light grey text color */
        }
        .card-footer {
            font-size: 0.875rem;
            color: #6c757d;
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
        <h6 class="mb-4"> Hi <?php echo $username; ?> this plaform allows you to write any idea that you have in mind feel free to write your thoughts..</h6>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        <div class="row">
            <?php if (!empty($ideas)): ?>
                <?php foreach ($ideas as $idea): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card idea-card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($idea['problem_heading']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars(substr($idea['description'], 0, 250)); ?>...</p>
                                <p class="card-text"><small class="text-muted">By <?php echo htmlspecialchars($idea['author_username']); ?></small></p>
                            </div>
                            <div class="card-footer">
                                <span class="badge bg-primary"><?php echo htmlspecialchars($idea['like_count']); ?> Likes</span>
                                <span class="badge bg-secondary"><?php echo htmlspecialchars($idea['comment_count']); ?> Comments</span>
                                <a href="readmore?id=<?php echo $idea['idea_id']; ?>" class="read-more-btn float-end">Read More</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No ideas found. <a href="addidea">Add your first idea</a></p>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>
