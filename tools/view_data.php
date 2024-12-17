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
        };
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
            background-color: #121212;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        h1 {
            margin-bottom: 20px;
            color: #BB86FC;
        }

        .table-container {
            width: 80%;
            max-width: 900px;
            background-color: #1E1E1E;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.7);
            overflow: hidden;
            padding: 20px;
        }

        .table-wrapper {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #444;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #444;
        }

        th {
            background-color: #2E2E2E;
            color: #BB86FC;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        tr:nth-child(even) {
            background-color: #353b48;
        }

        tr:hover {
            background-color: #9C4DFF;
        }

        .button-container {
            display: flex;
            justify-content: center; /* Centers the buttons horizontally */
            gap: 20px; /* Adds space between the buttons */
            margin-top: 20px;
        }

        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #BB86FC;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-button:hover {
            background-color: #9C4DFF;
        }

        @media (max-width: 768px) {
            /* Adjust table container for smaller screens */
            .table-container {
                width: 95%;
                padding: 10px;
            }

            /* Smaller table headers and cells for mobile */
            th, td {
                padding: 8px;
                font-size: 12px;
            }

            /* Adjust buttons for smaller screens */
            .back-button {
                padding: 8px 16px;
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            /* For very small screens, allow table scrolling */
            .table-wrapper {
                overflow-x: auto;
            }

            table {
                font-size: 14px; /* Smaller font for readability */
            }

            th, td {
                padding: 6px;
            }
        }
    </style>
</head>
<body class="dark-mode">
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
    <div class="button-container">
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a class="back-button" href='<?php echo $tools_delete_url ?>'>Delete a Data?</a>
            <a class="back-button" href='<?php echo $tools_edit_url ?>'>Edit a Data?</a>
        <?php endif; ?>
        <a class="back-button" href='<?php echo $tools_index_url ?>'>Back to Form</a>
    </div>
</body>
</html>
