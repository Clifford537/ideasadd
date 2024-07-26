<?php
// errorpage.php

// Error message
$error_message = isset($_GET['error_message']) ? $_GET['error_message'] : 'An unexpected error occurred.';

// Redirect time in seconds
$redirect_time = 3;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7fa; /* Light cyan background */
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: #ffffff;
            border: 1px solid #b0bec5; /* Light grey border */
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }

        h1 {
            color: #d32f2f; /* Red color */
            margin-bottom: 20px;
        }

        p {
            color: #333;
        }

        .redirect {
            margin-top: 20px;
            color: #555;
            font-size: 18px;
        }
    </style>
    <script>
        // Countdown timer and redirect
        let countdown = <?php echo $redirect_time; ?>;
        function updateCountdown() {
            document.getElementById('countdown').textContent = countdown;
            if (countdown <= 0) {
                window.location.href = 'https://ideasubmissionafrica.wuaze.com/';
            }
            countdown--;
        }
        setInterval(updateCountdown, 1000); // Update every second
    </script>
</head>
<body>
    <div class="container">
        <h1>Error</h1>
        <p><?php echo htmlspecialchars($error_message); ?></p>
        <div class="redirect">
            <p>You will be redirected to the home page in <span id="countdown"><?php echo $redirect_time; ?></span> seconds.</p>
        </div>
    </div>
</body>
</html>
