<?php
session_start();
$login_error = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : '';
unset($_SESSION['login_error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #4ca1af, #2c3e50);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: rgba(0, 0, 0, 0.6);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .login-container h2 {
            margin-bottom: 1.5rem;
        }
        .form-label {
            font-weight: bold;
        }
        .alert {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="text-center">Login</h2>
        <?php if ($login_error): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $login_error; ?>
            </div>
        <?php endif; ?>
        <form action="loginprocess.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary text-center w-50">Login</button>
        </form>
        <div class="mt-3 text-center">
            <a href="register.php" class="text-light">Don't have an account? Register here</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
