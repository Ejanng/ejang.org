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
            background-color: #121212; /* Dark background */
            color: #FFFFFF; /* Light text */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
        }
        .container {
            background: #1E1E1E; /* Dark container background */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 1000px;
            text-align: center;
        }
        h1 {
            color: #BB86FC; /* Purple header color */
            margin-bottom: 20px;
        }
        p {
            text-align: center;
            margin: 15px 0;
        }
        a.back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #BB86FC; /* Purple button */
            color: #FFFFFF;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        a.back-button:hover {
            background-color: #9C4DFF; /* Brighter purple on hover */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #2E2E2E; /* Dark table background */
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #BB86FC; /* Purple border */
        }
        th {
            background-color: #BB86FC; /* Purple header background */
            color: #1E1E1E; /* Dark text for headers */
        }
        tr:nth-child(even) {
            background-color: #3A3A3A; /* Slightly darker background for alternate rows */
        }
        tr:hover {
            background-color: #4E4E4E; /* Highlight row on hover */
        }
        td {
            color: #FFFFFF; /* White text for table cells */
        }
        /* Responsive Design */
        @media (max-width: 768px) {
            table {
                font-size: 14px;
            }
            th, td {
                padding: 8px;
            }
            a.back-button {
                padding: 8px 16px;
                font-size: 14px;
            }
        }
        @media (max-width: 480px) {
            table {
                font-size: 12px;
            }
            th, td {
                padding: 6px;
            }
            a.back-button {
                padding: 6px 12px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Logs</h1>
        <p><a href="<?= htmlspecialchars($tools_index_url) ?>" class="back-button">Back to Dashboard</a></p>
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
