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
    <title>All Users</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #1e272e; /* Dark background */
            color: #ffffff; /* Light text */
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: #2f3640; /* Slightly lighter dark background */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 700px;
            text-align: center;
            overflow: auto;
            max-height: 90vh;
        }

        h1, h2 {
            color: #ffffff;
        }

        table {
            width: 100%; /* Full width */
            border-collapse: collapse;
            margin-top: 20px;
            overflow-x: auto;
            background-color: #353b48; /* Dark table background */
            border-radius: 8px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #444; /* Subtle border for rows */
        }

        th {
            background-color: #3d3d3d; /* Header background */
            color: #ffffff;
        }

        td {
            color: #ecf0f1; /* Light gray text */
        }

        tr:nth-child(even) {
            background-color: #2c3e50; /* Alternating row colors */
        }

        tr:hover {
            background-color: #4b5f7a; /* Highlight row on hover */
        }

        .form-container button {
            background-color: #3498db; /* Blue button */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-container button:hover {
            background-color: #2980b9; /* Darker blue on hover */
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            table {
                font-size: 14px;
            }

            th, td {
                padding: 8px 10px;
            }

            .form-container {
                padding: 15px;
                max-width: 90%;
            }
        }

        @media (max-width: 480px) {
            table {
                font-size: 12px;
            }

            th, td {
                padding: 6px 8px;
            }

            table {
                display: block;
                overflow-x: auto; /* Horizontal scroll for small devices */
                white-space: nowrap;
            }

            .form-container {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>All Users</h1>
        <?php if ($message): ?>
            <p><?= htmlspecialchars($message) ?></p>
            <?php $message = ''; ?>
        <?php endif; ?>
        <h2>List of All Users</h2>
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
                            <td><?= htmlspecialchars($showUser['user_id']) ?></td> <!-- Updated key to 'user_id' -->
                            <td><?= htmlspecialchars($showUser['username']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>
        <button onclick="window.location.href='<?php echo $tools_index_url ?>'">Back to Dashboard</button>
    </div>
</body>
</html>
