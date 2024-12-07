<?php
session_start();
include '../config.php'; // Include the config file
require $admin_db_url; // Ensure this file connects to your database
require $admin_role_url; // Include the role file

// Initialize variables
$message = '';
$users = [];

try {
    // Fetch users from the users table
    $stmt = $conn->query("SELECT user_id, username FROM user_data");
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
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 700px; /* Set max width of the container to prevent overflow */
            text-align: center;
            overflow: auto; /* Allows scrolling if content overflows */
            max-height: 90vh; /* Set max height for the container */
        }

        table {
            width: 80% !important; /* Reduce table width to 80% */
            margin-top: 20px;
            border-collapse: collapse;
            overflow-x: auto; /* Allow horizontal scrolling for wide tables */
            margin-left: auto; /* Center the table horizontally */
            margin-right: auto; /* Center the table horizontally */
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 6px 10px !important; /* Reduced padding for a more compact table */
            text-align: left;
            font-size: 14px !important; /* Reduced font size for a more compact look */
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

        /* Media Query for smaller screens */
        @media (max-width: 768px) {
            table {
                width: 100% !important; /* Make the table width 100% on smaller screens */
            }

            th, td {
                padding: 4px 8px !important; /* Reduce padding on smaller screens */
                font-size: 12px !important; /* Smaller font size */
            }

            .form-container {
                padding: 15px; /* Less padding for smaller screens */
                max-width: 100%; /* Ensure the container is not overflowing */
            }
        }

    </style>

</head>
<body>
    <div class="form-container">
        <h1>All Users</h1> <!-- Changed header to reflect all users -->
        <?php if ($message): ?>
            <p><?= htmlspecialchars($message) ?></p>
            <?php $message = ''; // Reset the message ?>
        <?php endif; ?>
        <h2>List of All Users</h2> <!-- Updated to reflect all users -->
        <?php if ($users): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $showUser): ?>
                        <tr>
                            <td><?= htmlspecialchars($showUser['id']) ?></td>
                            <td><?= htmlspecialchars($showUser['username']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>
        <button onclick="window.location.href='<?php echo $tools_index_url ?>'">Back to Form</button>
    </div>
</body>
</html>
