<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Ideas Platform - Collecting and Solving Problems Facing African Countries with Technology">
    <meta name="keywords" content="Ideas, Technology, Africa, Problem Solving, Innovation, Platform">
    <meta name="author" content="Clifford Mukosh">
    <title>Welcome to Ideas Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #2c3e50;
            color: white;
        }
        .carousel-item {
            height: 100vh;
            background-size: cover;
            background-position: center;
            opacity: 0.7;
        }
        .carousel-inner {
            height: 100%;
        }
        .content-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
            z-index: 10;
            background: rgba(0, 0, 0, 0.6);
            padding: 20px;
            border-radius: 10px;
            max-width: 80%;
            width: 90%;
        }
        .content-container h1 {
            font-size: 2rem;
            margin-bottom: 15px;
        }
        .content-container p {
            font-size: 1rem;
            margin-bottom: 15px;
        }
        .btn-lg {
            padding: 10px 20px;
            font-size: 1rem;
            margin: 5px;
        }
        @media (min-width: 768px) {
            .content-container h1 {
                font-size: 3rem;
            }
            .content-container p {
                font-size: 1.2rem;
                margin-bottom: 30px;
            }
            .btn-lg {
                padding: 15px 30px;
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active" style="background-image: url('./images/image1.jpg');"></div>
            <div class="carousel-item" style="background-image: url('./images/image3.jpg');"></div>
            <div class="carousel-item" style="background-image: url('./images/image2.jpeg');"></div>
        </div>
    </div>
    <div class="content-container">
        <h1>Welcome to Ideas Platform</h1>
        <p>Collecting and Solving Problems Facing African Countries with Technology</p>
        <div class="buttons">
            <a href="./authentication/login" class="btn btn-primary btn-lg">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </a>
            <a href="./authentication/register" class="btn btn-success btn-lg">
                <i class="bi bi-person-plus"></i> Register
            </a>  
        </div>
        <br>
        <p>Hello User, kindly register to log in and submit your ideas</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
