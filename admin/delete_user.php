<?php
session_start();
include '../config.php'; // Include the config file
require $admin_id_url; // Include the id file
require $admin_db_url; // Ensure this file connects to your database
require $admin_role_url; // Include the role file

// Initialize variables
$message = '';
$users = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn->beginTransaction();
        $idToDelete = (int)$_POST['id'];

        // Fetch the user details from the database
        $stmt = $conn->prepare("SELECT id, username, role, date_created FROM users WHERE id = :id");
        $stmt->bindParam(':id', $idToDelete, PDO::PARAM_INT);
        $stmt->execute();
        $recordToDelete = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($recordToDelete) {
            // Insert the deleted user's information into the DeletedUsers table
            $logStmt = $conn->prepare("
                INSERT INTO DeletedUsers (original_id, username, role, date_deleted) 
                VALUES (:original_id, :username, :role, NOW())
            ");
            $logStmt->bindParam(':original_id', $recordToDelete['id'], PDO::PARAM_INT);
            $logStmt->bindParam(':username', $recordToDelete['username'], PDO::PARAM_STR);
            $logStmt->bindParam(':role', $recordToDelete['role'], PDO::PARAM_STR);
            $logStmt->execute();

            // Delete the user from the users table
            $deleteStmt = $conn->prepare("DELETE FROM users WHERE id = :id");
            $deleteStmt->bindParam(':id', $idToDelete, PDO::PARAM_INT);
            $deleteStmt->execute();

            // Reorder the IDs to keep them sequential
            $conn->exec("SET @row_number = 0");
            $conn->exec("UPDATE users SET id = (@row_number := @row_number + 1) ORDER BY id");

            // Reset the AUTO_INCREMENT value
            $conn->exec("ALTER TABLE users AUTO_INCREMENT = 1");

            $conn->commit();
            $message = "User with ID $idToDelete deleted successfully.";

            // Redirect to clear POST data
            header("Location: delete_user.php");
            exit;
        } else {
            $message = "No user found with ID $idToDelete.";
            $conn->rollBack();
        }
    } catch (Exception $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        $message = "An error occurred: " . $e->getMessage();
    }
}

// Fetch users with date_created included
try {
    $stmt = $conn->query("SELECT id, username, role, date_created FROM users");
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
    <title>Delete User</title>
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
            max-width: 600px;
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
        .form-container input[type="number"],
        .form-container button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-container button {
            background-color: #007BFF;
            color: white;
            border: none;
        }
        .form-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Delete User</h1>
        <?php if ($message): ?>
            <p><?= htmlspecialchars($message) ?></p>
            <?php $message = ''; // Reset the message ?>
        <?php endif; ?>
        <form method="POST" action="delete_user.php">
            <label for="id">Enter ID to Delete:</label>
            <input type="number" name="id" id="id" placeholder="Enter ID" value="" required>
            <button type="submit">Delete</button>
        </form>
        <h2>All Users</h2>
        <?php if ($users): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Date Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['role']) ?></td>
                            <td><?= htmlspecialchars($user['date_created']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>
    </div>
    <a href='<?php echo $admin_index_url ?>'>Back to Form</a>
</body>
</html>
