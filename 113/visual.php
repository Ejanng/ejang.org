<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../config.php'; // Include database config
require $tools_db_url;   // Ensure database connection is established

// Include the LinkedList classes
require 'LinkedList.php';

// Define the size of the hashtable (number of buckets)
define('HASHTABLE_SIZE', 10);  // Using 10 for simplicity in this example

// Hash function to generate an index for the username
function hashUsername($username) {
    // A simple hash function (you can use other hash functions like md5, sha1, etc.)
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
            margin: 20px;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h1 {
            text-align: center;
        }
        .hashtable {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
        }
        .bucket {
            padding: 10px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            min-height: 50px;
        }
        .bucket span {
            display: block;
            padding: 5px;
            background-color: #007BFF;
            color: white;
            border-radius: 3px;
            margin-bottom: 5px;
        }
        .bucket .messages {
            margin-top: 10px;
            padding-left: 10px;
        }
        .message {
            background: #f1f1f1;
            margin: 5px 0;
            padding: 5px;
            border-radius: 3px;
            border: 1px solid #ddd;
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
</body>
</html>
