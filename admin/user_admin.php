<?php
session_start();
include '../config.php'; // Include the config file
require $admin_id_url;
require $admin_db_url; // Ensure this file connects to your database
require $admin_role_url; // Include the role file

// Initialize variables
$message = '';
$users = [];

try {
    // Fetch users from the users table
    $stmt = $conn->query("SELECT id, username, pass, role, date_created FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $message = "Could not fetch users: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Users</title> <!-- Changed the title to reflect all users -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212; /* Dark background */
            color: #FFFFFF; /* Light text color for readability */
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .form-container {
            background: #1E1E1E; /* Dark container background */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 800px;
            text-align: center;
            overflow-x: auto; /* Allow horizontal scrolling if necessary */
        }

        h1 {
            color: #BB86FC; /* Purple header */
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            margin-left: auto;
            margin-right: auto;
            background-color: #2E2E2E; /* Dark table background */
        }

        table, th, td {
            border: 1px solid #BB86FC; /* Purple borders */
        }

        th, td {
            padding: 12px 20px;
            text-align: left;
            font-size: 14px;
            color: #FFFFFF;
        }

        th {
            background-color: #333; /* Dark header row */
        }

        tr:nth-child(even) {
            background-color: #3A3A3A; /* Slightly lighter rows for alternating colors */
        }

        tr:hover {
            background-color: #444; /* Hover effect on rows */
        }

        .form-container button {
            background-color: #BB86FC; /* Purple button */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #9C4DFF; /* Lighter purple on hover */
        }

        /* Mobile responsive design */
        @media (max-width: 768px) {
            .form-container {
                width: 90%;
                padding: 15px;
            }

            table {
                font-size: 12px;
                padding: 8px;
                overflow-x: auto;
            }

            th, td {
                font-size: 12px; /* Reduce font size on mobile */
                padding: 8px; /* Reduce padding on mobile */
            }

            h1 {
                font-size: 20px;
            }

            .form-container button {
                padding: 8px 15px;
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 18px;
            }

            table {
                font-size: 10px; /* Further reduce font size for smaller devices */
            }

            th, td {
                padding: 6px;
                font-size: 10px; /* Adjust padding and font size */
            }

            .form-container button {
                padding: 6px 12px;
                font-size: 12px;
            }
        }

    </style>
</head>
<body>
    <div class="form-container">
        <h1>All Users</h1>
        <?php if ($message): ?>
            <p><?= htmlspecialchars($message) ?></p>
            <?php $message = ''; // Reset the message ?>
        <?php endif; ?>
        <h2>List of All Users</h2>
        <?php if ($users): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Role</th>
                        <th>Date Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $showUser): ?>
                        <tr>
                            <td><?= htmlspecialchars($showUser['id']) ?></td>
                            <td><?= htmlspecialchars($showUser['username']) ?></td>
                            <td><?= htmlspecialchars($showUser['pass']) ?></td>
                            <td><?= htmlspecialchars($showUser['role']) ?></td>
                            <td><?= htmlspecialchars($showUser['date_created']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>
        <button onclick="window.location.href='<?php echo $admin_index_url ?>'">Back to Form</button>
    </div>
</body>
</html>
