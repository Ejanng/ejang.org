<?php
session_start();
include '../config.php'; // Include the config file
require $admin_db_url; // Ensure this file connects to your database
require $admin_role_url; // Include the role file

// Initialize variables
$message = '';
$deletedUsers = [];

try {
    // Fetch deleted users from the DeletedUsers table
    $stmt = $conn->query("SELECT original_id, username, role, date_deleted, date_created FROM DeletedUsers");
    $deletedUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $message = "Could not fetch deleted users: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deleted Users</title>
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
        .form-container {
            background: #1E1E1E; /* Dark container background */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 800px;
            text-align: center;
        }
        .form-container h1, .form-container h2 {
            color: #BB86FC; /* Purple header color */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #BB86FC; /* Purple border */
        }
        th, td {
            padding: 10px;
            text-align: left;
            background-color: #2E2E2E; /* Slightly lighter dark background */
            color: #FFFFFF; /* Light text for cells */
        }
        th {
            background-color: #BB86FC; /* Purple header background */
            color: #1E1E1E; /* Dark text for headers */
        }
        .form-container button {
            background-color: #BB86FC; /* Purple button */
            color: #FFFFFF;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
            font-size: 16px;
        }
        .form-container button:hover {
            background-color: #9C4DFF; /* Brighter purple on hover */
        }
        .error {
            color: #FF5252; /* Red for error messages */
            font-size: 14px;
            margin-bottom: 10px;
        }
        /* Mobile responsive design */
        @media (max-width: 768px) {
            .form-container {
                padding: 15px;
                width: 90%;
            }
            table, th, td {
                font-size: 14px;
            }
            .form-container button {
                font-size: 14px;
                padding: 8px 16px;
            }
        }
        @media (max-width: 480px) {
            table, th, td {
                font-size: 12px;
            }
            .form-container button {
                font-size: 12px;
                padding: 6px 12px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Deleted Users</h1>
        <?php if ($message): ?>
            <p class="error"><?= htmlspecialchars($message) ?></p>
            <?php $message = ''; // Reset the message ?>
        <?php endif; ?>
        <h2>List of Deleted Users</h2>
        <?php if ($deletedUsers): ?>
            <table>
                <thead>
                    <tr>
                        <th>Original ID</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Date Deleted</th>
                        <th>Date Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($deletedUsers as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['original_id']) ?></td>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['role']) ?></td>
                            <td><?= htmlspecialchars($user['date_deleted']) ?></td>
                            <td><?= htmlspecialchars($user['date_created']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No deleted users found.</p>
        <?php endif; ?>
        <button onclick="window.location.href='<?php echo $admin_index_url ?>'">Back to Main Page</button>
    </div>
</body>
</html>
