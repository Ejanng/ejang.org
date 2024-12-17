<?php

include '../config.php'; // Include database config
require $tools_db_url;   // Ensure database connection is established

// Include the LinkedList classes
require 'LinkedList.php';

// Define the size of the hashtable (number of buckets)
define('HASHTABLE_SIZE', 10);  // Using 10 for simplicity in this example

// Hash function to generate an index for the username
function hashUsername($username) {
    return crc32($username) % HASHTABLE_SIZE;
}

// Initialize the hashtable with empty buckets
$hashtable = array_fill(0, HASHTABLE_SIZE, null);

// Fetch data from the database
try {
    $stmt = $conn->prepare("SELECT username, text FROM Messages");
    $stmt->execute();

    // Populate the hashtable
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $username = $row['username'];
        $text = $row['text'];

        // Compute the hash index for the username
        $index = hashUsername($username);

        // Check if the bucket is empty
        if ($hashtable[$index] === null) {
            // Initialize a new linked list for this index if empty
            $hashtable[$index] = new LinkedList();
        }

        // Append the message to the linked list at the computed index
        $hashtable[$index]->append($text);
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

// Visual representation (HTML)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hashtable Visual Representation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212; /* Dark background */
            color: #ffffff; /* White text */
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #BB86FC; /* Purple header color */
            margin-bottom: 20px;
        }
        .hashtable {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
        }
        .bucket {
            padding: 10px;
            background: #1E1E1E; /* Bucket background */
            border: 1px solid #444; /* Border color */
            border-radius: 8px;
            min-height: 100px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .bucket span {
            display: block;
            padding: 5px 10px;
            background-color: #BB86FC; /* Purple bucket label */
            color: #121212; /* Dark text */
            border-radius: 5px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .bucket .messages {
            width: 100%;
            margin-top: 10px;
            padding-left: 10px;
        }
        .message {
            background: #2E2E2E; /* Darker message background */
            margin: 5px 0;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #555; /* Border for messages */
            color: #ffffff;
        }
        .message:hover {
            background-color: #444; /* Hover effect */
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #BB86FC;
            color: #121212;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            text-align: center;
        }
        a:hover {
            background-color: #9C4DFF;
        }
    </style>
</head>
<body>
    <h1>Hashtable Visual Representation</h1>
    <div class="hashtable">
        <?php
        // Display the hashtable visually
        for ($i = 0; $i < HASHTABLE_SIZE; $i++) {
            echo '<div class="bucket">';
            echo "<span>Bucket $i</span>";
            
            if ($hashtable[$i] !== null) {
                $messages = $hashtable[$i]->toArray();  // Convert the linked list to an array
                echo '<div class="messages">';
                foreach ($messages as $message) {
                    echo '<div class="message">' . htmlspecialchars($message) . '</div>';
                }
                echo '</div>';
            } else {
                echo '<div>No messages</div>';
            }
            echo '</div>';
        }
        ?>
    </div>
    <a href="search.php">Search Messages</a>
</body>
</html>
