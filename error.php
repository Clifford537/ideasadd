<?php
// Determine the error code
$error_code = isset($_GET['error']) ? intval($_GET['error']) : 500;

// Set a default message
$error_message = "Something went wrong. Please try again later.";

// Determine the error message based on the error code
switch ($error_code) {
    case 400:
        $error_message = "Bad Request. The server could not understand the request due to invalid syntax.";
        break;
    case 401:
        $error_message = "Unauthorized. You must authenticate yourself to get the requested response.";
        break;
    case 403:
        $error_message = "Forbidden. You don't have permission to access this resource.";
        break;
    case 404:
        $error_message = "Not Found. The requested resource could not be found on this server.";
        break;
    case 500:
        $error_message = "Internal Server Error. Something went wrong on our end.";
        break;
    case 503:
        $error_message = "Service Unavailable. The server is currently unable to handle the request.";
        break;
    default:
        $error_code = 500;
        $error_message = "Internal Server Error. Something went wrong on our end.";
        break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $error_code; ?> Error</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #ffafbd, #ffc3a0);
            color: #333;
            text-align: center;
        }
        .container {
            max-width: 600px;
            padding: 30px;
            border: 1px solid #ff6f61;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease-in;
        }
        h1 {
            font-size: 4em;
            margin: 0;
            color: #ff6f61;
            animation: textAnimation 2s ease-in-out infinite;
        }
        p {
            font-size: 1.2em;
            margin: 20px 0;
            color: #555;
        }
        a {
            text-decoration: none;
            color: #fff;
            background: #ff6f61;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1.1em;
            transition: background 0.3s ease;
        }
        a:hover {
            background: #ff3f34;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes textAnimation {
            0% { transform: translateY(-10px); opacity: 0; }
            50% { transform: translateY(5px); opacity: 1; }
            100% { transform: translateY(-10px); opacity: 0; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Oops! An Error Occurred</h1>
        <p><?php echo $error_message; ?></p>
        <a href="https://ideasubmissionafrica.wuaze.com">Go to Homepage</a>
    </div>
</body>
</html>
