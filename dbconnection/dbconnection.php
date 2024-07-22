<?php

$servername = "sql107.infinityfree.com";
$username = "if0_36948684";
$password = "rK71euqdiQms";
$dbname = "if0_36948684_ideas";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

/*
//local server db configurations
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ideas";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
*/
?>
