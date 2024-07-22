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
            background-attachment: fixed;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }
        .login-container {
            background: rgba(0, 0, 0, 0.6);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 90%;
        }
        .login-container h2 {
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .form-label {
            font-weight: bold;
        }
        .alert {
            margin-bottom: 1rem;
        }
        .btn-container {
            display: flex;
            justify-content: space-between;
            gap: 10px; /* Space between buttons */
            margin-top: 1rem;
        }
        .btn-primary, .btn-secondary {
            flex: 1;
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1rem;
        }
        .btn-primary {
            background-color: #4ca1af;
        }
        .btn-primary:hover {
            background-color: #357f89;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .register-link {
            color: #4ca1af;
        }
        .register-link:hover {
            color: #357f89;
        }
        @media (max-width: 576px) {
            .login-container {
                padding: 1rem;
                max-width: 300px;
            }
            .login-container h2 {
                font-size: 1.5rem;
            }
            .form-label {
                font-size: 0.9rem;
            }
            .btn-container {
                flex-direction: row; /* Align buttons in a row */
                gap: 10px; /* Adjust spacing between buttons */
            }
            .btn-primary, .btn-secondary {
                width: 48%; /* Adjust button width for better fit */
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
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
            <div class="btn-container">
                <button type="submit" class="btn btn-primary">Login</button>
                <a href="../index" class="btn btn-secondary">Home</a>
            </div>
        </form>
        <div class="mt-3 text-center">
            <a href="register" class="register-link">Don't have an account? Register here</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
