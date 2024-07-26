<?php

// Check if we're on a local server or a live server
if ($_SERVER['SERVER_NAME'] == 'localhost') {
    // Local server DB configurations
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ideas";
} else {
    // Live server DB configurations
    $servername = "sql107.infinityfree.com";
    $username = "if0_36948684";
    $password = "rK71euqdiQms";
    $dbname = "if0_36948684_ideas";
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>
