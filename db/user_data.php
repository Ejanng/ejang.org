<?php
include '../config.php'; // Include the config file
require $admin_db_url; // Ensure this file connects to your database

try {
    // Step 1: Query to get usernames from Messages table that are not in user_data
    $stmt = $conn->prepare("
        SELECT DISTINCT username
        FROM Messages
        WHERE username NOT IN (SELECT username FROM user_data)
    ");
    $stmt->execute();

    // Step 2: Insert those usernames into the user_data table
    $usernames = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($usernames as $user) {
        // Insert the username into user_data table
        $insertStmt = $conn->prepare("INSERT INTO user_data (username) VALUES (:username)");
        $insertStmt->bindParam(':username', $user['username']);
        $insertStmt->execute();
    }

    echo "Unrecorded usernames have been successfully added to the user_data table.";

} catch (PDOException $e) {
    // Handle any errors
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$conn = null;
exit;
?>
