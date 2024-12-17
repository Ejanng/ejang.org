<?php
include '../config.php'; // Include database config
require $tools_db_url;   // Ensure database connection is established

// Include the LinkedList classes
require 'LinkedList.php';

// Define the size of the hashtable (number of buckets)
define('HASHTABLE_SIZE', 100);

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
        $index = hashUsername($username);

        if ($hashtable[$index] === null) {
            $hashtable[$index] = new LinkedList();
        }
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
        for ($i = 0; $i < HASHTABLE_SIZE; $i++) {
            if ($hashtable[$i] !== null) {
                $messages = $hashtable[$i]->toArray();
                foreach ($messages as $message) {
                    if (stripos($message, $query) !== false) {
                        $searchResults[] = [
                            'username' => "Unknown",
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
            background-color: #121212; /* Dark background */
            color: #ffffff; /* White text */
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #BB86FC; /* Purple header color */
        }
        form {
            background-color: #1E1E1E; /* Form container */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            margin-bottom: 20px;
        }
        form h2 {
            color: #BB86FC;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #BB86FC;
            border-radius: 5px;
            background-color: #2E2E2E;
            color: #ffffff;
        }
        input[type="submit"] {
            background-color: #BB86FC;
            color: #1E1E1E;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #9C4DFF;
        }
        .results {
            background-color: #1E1E1E;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        .results h2 {
            color: #BB86FC;
            margin-bottom: 10px;
        }
        .results ul {
            list-style-type: none;
            padding: 0;
        }
        .results li {
            margin: 10px 0;
            padding: 10px;
            background-color: #2E2E2E;
            border: 1px solid #444;
            border-radius: 5px;
            color: #ffffff;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #BB86FC;
            color: #1E1E1E;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        a:hover {
            background-color: #9C4DFF;
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
    <a href="visual.php">Go to Visual</a>
    <a href="/ejang.org/leave-a-note.php">Go to Main Page</a>
</body>
</html>
