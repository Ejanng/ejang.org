<?php
include '../config.php'; // Include the config file
require $tools_db_url; // Ensure this file connects to your database
header("Location: $tools_index_url");

try {
    // Get form data (username and message)
    if (isset($_POST['username']) && isset($_POST['text'])) {
        $username = $_POST['username']; // Assuming the form field for username is 'username'
        $text = $_POST['text'];         // Assuming the form field for message is 'text'

        // Check if the username and text are not empty
        if (!empty($username) && !empty($text)) {
            // Prepare the SQL INSERT query for Messages table
            $stmt = $conn->prepare("INSERT INTO Messages (username, text) VALUES (:username, :text)");

            // Bind parameters to the query
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':text', $text);

            // Execute the query to insert the data into Messages table
            $stmt->execute();

            // Now, insert the username into the user_data table
            // First, check if the username already exists in the user_data table
            $checkStmt = $conn->prepare("SELECT COUNT(*) FROM user_data WHERE username = :username");
            $checkStmt->bindParam(':username', $username);
            $checkStmt->execute();
            $count = $checkStmt->fetchColumn();

            // If the username doesn't exist in the user_data table, insert it
            if ($count == 0) {
                $insertStmt = $conn->prepare("INSERT INTO user_data (username) VALUES (:username)");
                $insertStmt->bindParam(':username', $username);
                $insertStmt->execute();
            }

        } else {
            echo "Error: Username or message cannot be empty!";
        }
    } else {
        echo "Error: Missing form data!";
    }
} catch (PDOException $e) {
    // Handle any errors
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$conn = null;
exit;
?>
