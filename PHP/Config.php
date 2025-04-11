<?php
// config.php

// Replace these values with your own database connection settings
$servername = "localhost";  // or your server
$username = "root";         // database username
$password = "";             // database password
$dbname = "sockazon";       // your database name

try {
    // Create a PDO connection to the 'sockazon' database
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable error handling
} catch (PDOException $e) {
    // If the connection fails, display the error
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>
