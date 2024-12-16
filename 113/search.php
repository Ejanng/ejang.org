<?php
include '../config.php'; // Include database config
require $tools_db_url;   // Ensure database connection is established

// Include the LinkedList classes
require 'LinkedList.php';

// Define the size of the hashtable (number of buckets)
define('HASHTABLE_SIZE', 100);

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

// Search functionality
$searchResults = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query'])) {
    $query = trim($_POST['query']);

    if (!empty($query)) {
        // Search through the hashtable
        for ($i = 0; $i < HASHTABLE_SIZE; $i++) {
            if ($hashtable[$i] !== null) {
                $messages = $hashtable[$i]->toArray(); // Convert linked list to array for searching
                foreach ($messages as $message) {
                    if (stripos($message, $query) !== false) { // Case-insensitive search
                        $searchResults[] = [
                            'username' => "Unknown", // Username will be deduced later
                            'text' => $message
                        ];
                    }
                }
            }
        }
    } else {
        echo "<p>Error: Search query cannot be empty!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linked List Hashtable Search</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f5f5f5;
        }
        form {
            margin-bottom: 20px;
            padding: 10px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .results {
            padding: 10px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .results ul {
            list-style-type: none;
            padding: 0;
        }
        .results li {
            margin: 10px 0;
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #eee;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Linked List Hashtable Search</h1>

    <!-- Search Form -->
    <form method="POST" action="">
        <h2>Search Data</h2>
        <label for="query">Search Query:</label>
        <input type="text" id="query" name="query" placeholder="Enter search keyword..." required>
        <input type="submit" value="Search">
    </form>

    <!-- Results Section -->
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query'])): ?>
        <div class="results">
            <h2>Search Results for: '<strong><?php echo htmlspecialchars($_POST['query']); ?></strong>'</h2>
            <?php if (!empty($searchResults)): ?>
                <ul>
                    <?php foreach ($searchResults as $result): ?>
                        <li>
                            <strong>User:</strong> <?php echo htmlspecialchars($result['username']); ?><br>
                            <strong>Message:</strong> <?php echo htmlspecialchars($result['text']); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No results found for '<strong><?php echo htmlspecialchars($_POST['query']); ?></strong>'.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</body>
</html>
