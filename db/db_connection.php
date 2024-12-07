<?php
// Database connection settings
$servername = "sql211.infinityfree.com";
$username = "if0_37784118";
$password = "xxxxxxxxxxxx";
$dbname = "if0_37784118_leaveanote";

try {
    // Create a new PDO connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Optional: Enable the use of emulated prepared statements
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    // If there is an error connecting, display a message
    die("Connection failed: " . $e->getMessage());
}

// Optionally, return the connection object so it can be reused
return $conn;
?>
