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
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .form-container button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .form-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Deleted Users</h1>
        <?php if ($message): ?>
            <p><?= htmlspecialchars($message) ?></p>
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
        <button onclick="window.location.href='<?php echo $admin_index_url ?>'">Back to Form</button>
    </div>
</body>
</html>
