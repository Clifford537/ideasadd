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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f6; /* Light background color */
        }
        .navbar {
            margin-bottom: 30px; /* Space between navbar and content */
        }
        .container {
            background: #ffffff; /* White background for the form container */
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
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
        .navbar-nav .nav-link {
            font-weight: bold;
        }
        .navbar-brand {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-lightbulb"></i> Idea Management</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="addidea"><i class="fas fa-plus-circle"></i> Add Idea</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manageideas"><i class="fas fa-user-cog"></i> Account Management</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">Add a New Idea</h2>
        <?php if ($success_message): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>
