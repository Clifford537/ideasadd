<?php
session_start();
require '../dbconnection/dbconnection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Check if an idea ID is provided
if (!isset($_GET['id'])) {
    header('Location: dashboard.php'); // Redirect if no ID is provided
    exit();
}

$idea_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

try {
    // Fetch the existing idea details
    $stmt = $conn->prepare("SELECT * FROM ideas WHERE idea_id = :idea_id AND user_id = :user_id");
    $stmt->bindParam(':idea_id', $idea_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $idea = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$idea) {
        header('Location: dashboard.php'); // Redirect if the idea is not found or not owned by the user
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Error: " . $e->getMessage();
    header('Location: dashboard.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Idea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Update Idea</h2>
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
        <form action="updateideaprocessform.php" method="POST">
            <input type="hidden" name="idea_id" value="<?php echo htmlspecialchars($idea['idea_id']); ?>">
            <div class="mb-3">
                <label for="problem_heading" class="form-label">Problem Heading</label>
                <input type="text" class="form-control" id="problem_heading" name="problem_heading" value="<?php echo htmlspecialchars($idea['problem_heading']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($idea['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="possible_solution" class="form-label">Possible Solution</label>
                <textarea class="form-control" id="possible_solution" name="possible_solution" rows="4"><?php echo htmlspecialchars($idea['possible_solution']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="suggested_tools" class="form-label">Suggested Tools</label>
                <textarea class="form-control" id="suggested_tools" name="suggested_tools" rows="4"><?php echo htmlspecialchars($idea['suggested_tools']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="impact_on_economy" class="form-label">Impact on Economy</label>
                <textarea class="form-control" id="impact_on_economy" name="impact_on_economy" rows="4"><?php echo htmlspecialchars($idea['impact_on_economy']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="revenue_generation" class="form-label">Revenue Generation</label>
                <textarea class="form-control" id="revenue_generation" name="revenue_generation" rows="4"><?php echo htmlspecialchars($idea['revenue_generation']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="stakeholders" class="form-label">Stakeholders</label>
                <textarea class="form-control" id="stakeholders" name="stakeholders" rows="4"><?php echo htmlspecialchars($idea['stakeholders']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Idea</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
