<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Idea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            max-width: 800px;
            background: #ffffff; /* White background for the form container */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px; /* Ensure spacing from the fixed navbar */
        }
        .form-label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #28a745; /* Green background for the button */
            border-color: #28a745;
        }
        .btn-primary:hover {
            background-color: #218838; /* Darker green on hover */
            border-color: #1e7e34;
        }
        .alert {
            margin-top: 20px;
        }
        @media (max-width: 768px) {
            .container {
                padding: 15px;
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
            <a class="navbar-brand" href="#">Idea Platform</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="addidea.php">Add Idea</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="passwordchange.php">Change Password</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manageideas.php">Manage My Ideas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5">
        <h2 class="mb-4">Add a New Idea</h2>
        <?php if ($success_message): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        <form action="addideaprocessform.php" method="POST">
            <div class="mb-3">
                <label for="country" class="form-label">Country</label>
                <input type="text" class="form-control" id="country" name="country" required>
            </div>
            <div class="mb-3">
                <label for="problem_heading" class="form-label">Problem Heading</label>
                <input type="text" class="form-control" id="problem_heading" name="problem_heading" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="possible_solution" class="form-label">Possible Solution</label>
                <textarea class="form-control" id="possible_solution" name="possible_solution" rows="4"></textarea>
            </div>
            <div class="mb-3">
                <label for="suggested_tools" class="form-label">Suggested Tools</label>
                <textarea class="form-control" id="suggested_tools" name="suggested_tools" rows="4"></textarea>
            </div>
            <div class="mb-3">
                <label for="impact_on_economy" class="form-label">Impact on Economy</label>
                <textarea class="form-control" id="impact_on_economy" name="impact_on_economy" rows="4"></textarea>
            </div>
            <div class="mb-3">
                <label for="revenue_generation" class="form-label">Revenue Generation</label>
                <textarea class="form-control" id="revenue_generation" name="revenue_generation" rows="4"></textarea>
            </div>
            <div class="mb-3">
                <label for="stakeholders" class="form-label">Stakeholders</label>
                <textarea class="form-control" id="stakeholders" name="stakeholders" rows="4"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Idea</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
