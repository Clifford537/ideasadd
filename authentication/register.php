<?php
session_start();
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);

// Fetch African countries dynamically
function fetch_african_countries() {
    $api_url = 'https://restcountries.com/v3.1/all'; // API endpoint
    $response = file_get_contents($api_url);
    $countries = json_decode($response, true);

    $african_countries = [];
    foreach ($countries as $country) {
        if (isset($country['region']) && $country['region'] === 'Africa') {
            $african_countries[] = $country['name']['common'];
        }
    }
    return $african_countries;
}
$country_list = fetch_african_countries();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #2c3e50, #4ca1af);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .register-container {
            background: rgba(0, 0, 0, 0.8);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 90%;
            overflow: auto; /* Allow scrolling if needed */
        }
        .register-container h2 {
            margin-bottom: 1rem;
            font-weight: bold;
            text-align: center;
        }
        .form-label {
            font-weight: bold;
        }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        .form-control:focus, .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
        }
        .btn-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 1rem;
        }
        .btn-primary, .btn-secondary {
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            flex: 1;
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
        .alert {
            margin-bottom: 1rem;
        }
        .text-light {
            color: #f8f9fa !important;
        }
        .password-info {
            font-size: 0.9rem;
            color: #ccc;
        }
        @media (max-width: 576px) {
            .register-container {
                padding: 1rem;
                max-width: 90%;
                font-size: 0.9rem;
            }
            .register-container h2 {
                font-size: 1.2rem;
            }
            .form-control, .form-select {
                font-size: 0.9rem;
            }
            .password-info {
                font-size: 0.8rem;
            }
            .btn-container {
                flex-direction: row;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
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
        <form action="registerprocessform" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <div class="password-info">
                    Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, and one number.
                </div>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="mb-3">
                <label for="country" class="form-label">Country</label>
                <select id="country" name="country" class="form-select" required>
                    <option value="" disabled selected>Select your country</option>
                    <?php foreach ($country_list as $country): ?>
                        <option value="<?php echo htmlspecialchars($country); ?>"><?php echo htmlspecialchars($country); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="btn-container">
                <button type="submit" class="btn btn-primary">Register</button>
                <a href="../index" class="btn btn-secondary">Home</a>
            </div>
        </form>
        <div class="mt-3 text-center">
            <a href="login" class="text-light">Already have an account? Login here</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
