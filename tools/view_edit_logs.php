<?php
session_start();
include '../config.php'; // Include the config file
require $tools_db_url; // Ensure this file connects to your database

// Check if the user is logged in as an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // If the user is not logged in or not an admin, redirect to login page
    header("Location: $tools_login_url");
    exit;
}

// Fetch all records from the EditLogs table
$stmt = $conn->prepare("SELECT * FROM EditLogs ORDER BY date_edited DESC");
$stmt->execute();
$editLogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Logs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .back-button {
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Logs</h1>
        <p><a href='<?php echo $tools_index_url ?>' class="back-button">Back to Dashboard</a></p>
        <?php if (count($editLogs) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Old Text</th>
                        <th>New Text</th>
                        <th>Date Created</th>
                        <th>Date Edited</th>
                        <th>Edited By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($editLogs as $log): ?>
                        <tr>
                            <td><?= htmlspecialchars($log['id']) ?></td>
                            <td><?= htmlspecialchars($log['username']) ?></td>
                            <td><?= htmlspecialchars($log['old_text']) ?></td>
                            <td><?= htmlspecialchars($log['new_text']) ?></td>
                            <td><?= htmlspecialchars($log['date_created']) ?></td>
                            <td><?= htmlspecialchars($log['date_edited']) ?></td>
                            <td><?= htmlspecialchars($log['edited_by']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No edit logs available.</p>
        <?php endif; ?>
    </div>
</body>
</html>
