<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start(); // Start the session to access session data
include '../config.php'; // Include the config file
require $tools_db_url; // Ensure this file connects to your database

try {
    // Prepare and execute the query to retrieve all data from the 'messages' table
    $stmt = $conn->prepare("SELECT id, username, text, date FROM Messages");
    $stmt->execute();

    // Fetch all results as an associative array
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle database connection or query errors
    die("Error: " . $e->getMessage());
}

// Close the database connection (optional, as PDO closes it automatically when the script ends)
$conn = null;
?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= htmlspecialchars($gtag_id) ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?= htmlspecialchars($gtag_id) ?>');
    </script>
    <script>
        // Save scroll position before reload
        setInterval(function () {
            if (document.visibilityState === "visible") {
                localStorage.setItem("scrollPosition", window.scrollY); // Save current scroll position
                location.reload();
            }
        }, 300000);

        // Restore scroll position after reload
        window.onload = function () {
            const scrollPosition = localStorage.getItem("scrollPosition");
            if (scrollPosition) {
                window.scrollTo(0, parseInt(scrollPosition, 10)); // Scroll to the saved position
                localStorage.removeItem("scrollPosition"); // Optional: Clean up storage
            }

            // Restore dark mode preference
            const isDarkMode = localStorage.getItem("darkMode") === "true";
            if (isDarkMode) {
                document.body.classList.add("dark-mode");
            }
        };

        // Toggle dark mode
        function toggleDarkMode() {
            const body = document.body;
            const isDarkMode = body.classList.toggle("dark-mode");
            localStorage.setItem("darkMode", isDarkMode); // Save preference
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Notes</title>
    <style>
        /* Resetting styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        h1 {
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .table-container {
            width: 80%;
            max-width: 900px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 20px;
        }

        .table-wrapper {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #ddd;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #3498db;
            color: #fff;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        tr:nth-child(even) {
            background-color: #ecf0f1;
        }

        tr:hover {
            background-color: #dfe6e9;
        }

        .button-container {
            display: flex;
            justify-content: center; /* Centers the buttons horizontally */
            gap: 20px; /* Adds space between the buttons */
        }

        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-button:hover {
            background-color: #2980b9;
        }
        @media (max-width: 768px) {
            .table-container {
                width: 95%;
            }

            th, td {
                padding: 8px;
            }

            .back-button {
                padding: 8px 16px;
            }
        }

        /* Dark mode styles */
        body.dark-mode {
            background-color: #1e272e;
            color: #ffffff;
        }

        .dark-mode table {
            background-color: #2c2c2c;
            color: #ffffff;
            border-color: #444;
        }

        .dark-mode th {
            background-color: #3d3d3d;
            color: #ffffff;
        }

        .dark-mode tr:nth-child(even) {
            background-color: #353b48;
        }

        .dark-mode tr:hover {
            background-color: #4b5f7a;
        }

        .dark-mode-button {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 10px 15px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }

        .dark-mode-button:hover {
            background-color: #2980b9;
        }

        /* Dark mode toggle button */
        .dark-mode-button {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 10px 15px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }

        .dark-mode-button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <h1>All Notes</h1>
    <div class="table-container">
        <?php if (count($messages) > 0): ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Message</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $message): ?>
                            <tr>
                                <td><?= htmlspecialchars($message['id']); ?></td>
                                <td><?= htmlspecialchars($message['username']); ?></td>
                                <td><?= htmlspecialchars($message['text']); ?></td>
                                <td><?= htmlspecialchars($message['date']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No messages found!</p>
        <?php endif; ?>
    </div>
    <!-- Conditionally show the delete button -->
    <!-- Show delete button only if the user is an admin -->
    <div class="button-container">
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a class="back-button" href='<?php echo $tools_delete_url ?>'>Delete a Data?</a>
            <a class="back-button" href='<?php echo $tools_edit_url ?>'>Edit a Data?</a>
        <?php endif; ?>
        <a class="back-button" href='<?php echo $tools_index_url ?>'>Back to Form</a>
    </div>
    <button class="dark-mode-button" onclick="toggleDarkMode()">Toggle Dark Mode</button>
</body>
</html>