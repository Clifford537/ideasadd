<?php
session_start();
require '../dbconnection/dbconnection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../authentication/login');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details and all ideas from the database
try {
    // Fetch username
    $stmt = $conn->prepare("SELECT username FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $username = htmlspecialchars($user['username']);

    // Fetch all ideas along with counts of likes, comments, and views
    $stmt = $conn->prepare("
        SELECT 
            ideas.*, 
            users.username AS author_username,
            COALESCE(like_counts.like_count, 0) AS like_count,
            COALESCE(comment_counts.comment_count, 0) AS comment_count,
            COALESCE(view_counts.view_count, 0) AS view_count
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
        LEFT JOIN (
            SELECT 
                idea_id, 
                COUNT(DISTINCT user_id) AS view_count 
            FROM 
                views 
            GROUP BY 
                idea_id
        ) AS view_counts ON ideas.idea_id = view_counts.idea_id
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
        .icon {
            font-size: 0.75rem; /* Smaller icon size */
            margin-right: 3px;
        }
        .badge {
            font-size: 0.75rem; /* Smaller badge text */
            background-color: transparent; /* Remove background color */
            color: #6c757d; /* Grey color */
        }
    /* Styles for small screens (e.g., mobile devices) */
    @media (max-width: 576px) {
        .container {
            padding: 0 5px; /* Reduced padding for small screens */
        }
        .idea-card {
            margin-bottom: 10px; /* Reduced margin for cards on small screens */
            border-radius: 4px; /* Smaller border radius */
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1); /* Smaller box shadow */
        }
        .card-title {
            font-size: 1.525rem; /* Larger font size for card titles on small screens */
        }
        .card-text {
            font-size: 1.2rem; /* Larger font size for card text on small screens */
        }
        .card-footer {
            font-size: 0.95rem; /* Font size for card footer on small screens */
        }
        .icon {
            font-size: 0.95rem; /* Icon size on small screens */
        }
        .badge {
            font-size: 0.95rem; /* Badge text size on small screens */
        }
        .read-more-btn {
            font-size: 0.975rem; /* Font size for 'Read More' button on small screens */
        }
    }

    /* Styles for very small screens (e.g., devices with width <= 360px) */
    @media (max-width: 360px) {
        .container {
            padding: 0 2px; /* Minimized padding for extra small screens */
        }
        .idea-card {
            margin-bottom: 5px; /* Minimized margin for cards on extra small screens */
        }
        .card-title {
            font-size: 1rem; /* Font size for card titles on extra small screens */
        }
        .card-text {
            font-size: 0.75rem; /* Font size for card text on extra small screens */
        }
        .card-footer {
            font-size: 0.65rem; /* Font size for card footer on extra small screens */
        }
        .icon {
            font-size: 0.65rem; /* Smaller icon size on extra small screens */
        }
        .badge {
            font-size: 0.65rem; /* Smaller badge text size on extra small screens */
        }
        .read-more-btn {
            font-size: 0.75rem; /* Font size for 'Read More' button on extra small screens */
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
        <h6 class="mb-4"><i class="fas fa-user icon"></i>Hi <?php echo $username; ?>! This platform allows you to write any idea or problem you ever faced or are facing in your country. <i class="fa-solid fa-wand-magic-sparkles" style="color:indigo;"></i></h6>
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
                                <h5 style="background: linear-gradient(to right, #FF6F61, #FF9A8B);-webkit-background-clip: text;-webkit-text-fill-color: transparent;" class="card-title"><?php echo htmlspecialchars($idea['problem_heading']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars(substr($idea['description'], 0, 50)); ?>...</p>
                                <p class="card-text"><small class="text-muted"><i class="fas fa-user icon" style="color:orange;"></i><?php echo htmlspecialchars($idea['author_username']); ?></small></p>
                            </div>
                            <div class="card-footer">
                                <span class="badge" style="color:red;"><i class="fas fa-heart icon" style="color:indigo;"></i><?php echo htmlspecialchars($idea['like_count']); ?></span>
                                <span class="badge" style="color:green;"><i class="fas fa-comment icon" style="color:green;"></i><?php echo htmlspecialchars($idea['comment_count']); ?></span>
                                <span class="badge" style="color:blue;"><i class="fas fa-eye icon" style="color:blue;"></i><?php echo htmlspecialchars($idea['view_count']); ?></span>
                                <a href="readmore?id=<?php echo $idea['idea_id']; ?>" class="read-more-btn float-end" style="color:#00BFF;">Read More</a>
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
