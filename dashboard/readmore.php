<?php
session_start();
require '../dbconnection/dbconnection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

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

        // Fetch comments for the idea
        $stmt = $conn->prepare("SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.user_id WHERE c.idea_id = :idea_id ORDER BY c.created_at DESC");
        $stmt->bindParam(':idea_id', $idea_id);
        $stmt->execute();
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch like count
        $stmt = $conn->prepare("SELECT COUNT(*) FROM likes WHERE idea_id = :idea_id");
        $stmt->bindParam(':idea_id', $idea_id);
        $stmt->execute();
        $like_count = $stmt->fetchColumn();

        // Check if the user has liked the idea
        $stmt = $conn->prepare("SELECT COUNT(*) FROM likes WHERE idea_id = :idea_id AND user_id = :user_id");
        $stmt->bindParam(':idea_id', $idea_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $has_liked = $stmt->fetchColumn() > 0;

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: #f1f5f9; /* Light background color */
            margin-top: 0px; /* Space for fixed navbar */
        }
        .navbar {
            background: linear-gradient(90deg, #343a40 0%, #495057 100%); /* Gradient background */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            color: #ffffff;
            font-weight: bold;
            display: flex;
            align-items: center;
        }
        .navbar-brand i {
            margin-right: 8px; /* Space between icon and text */
            color: #ffffff;
        }
        .navbar-nav .nav-link {
            color: #ffffff;
            font-size: 1rem;
        }
        .navbar-nav .nav-link:hover {
            color: #adb5bd; /* Light grey on hover */
        }
        .navbar-toggler {
            border-color: #ffffff;
        }
        .navbar-toggler-icon {
            background-image: url('data:image/svg+xml;charset=utf8,%3Csvg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 30 30"%3E%3Cpath stroke="%23ffffff" stroke-width="2" d="M5 7h20M5 15h20M5 23h20"%3E%3C/path%3E%3C/svg%3E');
        }
        .idea-detail {
            margin-top: 30px;
        }
        .comment-card {
            margin-bottom: 20px;
        }
        .btn-like {
            font-size: 1.2rem;
            display: inline-flex;
            align-items: center;
        }
        .btn-like i {
            margin-right: 5px;
        }
        .modal-body textarea {
            resize: none;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-lightbulb"></i> Idea Platform
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard"><i class="fas fa-home"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="addidea"><i class="fas fa-plus"></i> Add Idea</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="passwordchange"><i class="fas fa-key"></i> Change Password</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manageideas"><i class="fas fa-tasks"></i> Manage Ideas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container idea-detail">
        <div class="row">
            <div class="col-lg-8">
                <h2 class="mb-4"><?php echo htmlspecialchars($idea['problem_heading']); ?></h2>
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

                <!-- Like Button -->
                <form action="likeidea.php" method="POST" class="mb-4">
                    <input type="hidden" name="idea_id" value="<?php echo $idea_id; ?>">
                    <button type="submit" class="btn btn-outline-primary btn-like <?php echo $has_liked ? 'disabled' : ''; ?>">
                        <i class="fas fa-thumbs-up"></i> Like (<?php echo $like_count; ?>)
                    </button>
                </form>

                <!-- Comment Form -->
                <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#commentModal">
                    <i class="fas fa-comment"></i> Add Comment
                </button>
                   <!-- Comments Section -->
                    <div class="mt-4">
                        <h4>Comments</h4>
                        <?php if (!empty($comments)): ?>
                            <?php foreach ($comments as $comment): ?>
                                <div class="card comment-card">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($comment['username']); ?></h5>
                                        <p class="card-text"><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                                        <p class="card-text"><small class="text-muted">Posted on <?php echo htmlspecialchars($comment['created_at']); ?></small></p>

                                        <!-- Edit Comment Button -->
                                        <?php if ($comment['user_id'] == $user_id): ?>
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editCommentModal" 
                                                    data-comment-id="<?php echo $comment['comment_id']; ?>" 
                                                    data-comment-text="<?php echo htmlspecialchars($comment['comment']); ?>">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>

                                            <!-- Delete Comment Button -->
                                            <form action="deletecomment.php" method="POST" class="d-inline">
                                                <input type="hidden" name="comment_id" value="<?php echo $comment['comment_id']; ?>">
                                                <input type="hidden" name="idea_id" value="<?php echo $idea_id; ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No comments yet.</p>
                        <?php endif; ?>
                    </div>
                <a href="manageideas.php" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left"></i> Back to My Ideas</a>
            </div>
        </div>
    </div>

            <!-- Edit Comment Modal -->
        <div class="modal fade" id="editCommentModal" tabindex="-1" aria-labelledby="editCommentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCommentModalLabel">Edit Comment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="editcomment.php" method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="comment_id" id="edit-comment-id">
                            <input type="hidden" name="idea_id" value="<?php echo $idea_id; ?>">
                            <div class="mb-3">
                                <label for="edit-comment" class="form-label">Comment</label>
                                <textarea class="form-control" id="edit-comment" name="comment" rows="4" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    <!-- Comment Modal -->
    <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentModalLabel">Add a Comment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="addcomment.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="idea_id" value="<?php echo $idea_id; ?>">
                        <div class="mb-3">
                            <label for="comment" class="form-label">Comment</label>
                            <textarea class="form-control" id="comment" name="comment" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Post Comment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
     <script>
        document.addEventListener('DOMContentLoaded', function() {
        var editCommentModal = document.getElementById('editCommentModal');
        editCommentModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var commentId = button.getAttribute('data-comment-id');
            var commentText = button.getAttribute('data-comment-text');

            var modal = editCommentModal;
            modal.querySelector('#edit-comment-id').value = commentId;
            modal.querySelector('#edit-comment').value = commentText;
        });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>
