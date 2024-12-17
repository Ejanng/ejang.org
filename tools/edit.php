<?php
session_start(); // Start the session to access session data
include '../config.php'; // Include the config file
require $tools_db_url; // Ensure this file connects to your database
require $tools_role_url; // Include the role file
// Initialize variables
$data = null;
$message = "";

// Fetch data based on ID or username
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == 'fetch_by_id') {
        // Fetch data by ID
        $id = $_POST['id'];
        if (!empty($id)) {
            $stmt = $conn->prepare("SELECT * FROM Messages WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                $message = "No record found for ID: $id.";
            }
        } else {
            $message = "ID cannot be empty.";
        }
    } elseif ($action == 'fetch_by_username') {
        // Fetch data by username
        $username = $_POST['username'];
        if (!empty($username)) {
            $stmt = $conn->prepare("SELECT * FROM Messages WHERE username = :username");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$data) {
                $message = "No records found for username: $username.";
            }
        } else {
            $message = "Username cannot be empty.";
        }
    }
}

// Update the text by ID
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_text') {
    $id = $_POST['id'];
    $newText = $_POST['new_text'];
    $editedBy = $_SESSION['username']; // Assuming username is stored in session for the admin

    // Fetch the current data for logging
    $stmt = $conn->prepare("SELECT * FROM Messages WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $dataToUpdate = $stmt->fetch(PDO::FETCH_ASSOC);
    $oldText = $dataToUpdate['text'];

    // Update the text in the Messages table
    $stmt = $conn->prepare("UPDATE Messages SET text = :new_text WHERE id = :id");
    $stmt->bindParam(':new_text', $newText, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Log the edit in EditLogs table
    $logStmt = $conn->prepare("INSERT INTO EditLogs 
        (record_id, username, old_text, new_text, date_created, date_edited, edited_by) 
        VALUES (:record_id, :username, :old_text, :new_text, :date_created, NOW(), :edited_by)");
    $logStmt->bindParam(':record_id', $id, PDO::PARAM_INT);
    $logStmt->bindParam(':username', $dataToUpdate['username'], PDO::PARAM_STR);
    $logStmt->bindParam(':old_text', $oldText, PDO::PARAM_STR);
    $logStmt->bindParam(':new_text', $newText, PDO::PARAM_STR);
    $logStmt->bindParam(':date_created', $dataToUpdate['date'], PDO::PARAM_STR);
    $logStmt->bindParam(':edited_by', $editedBy, PDO::PARAM_STR);
    $logStmt->execute();

    $message = "Record updated successfully.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #FFFFFF;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            background-color: #1E1E1E;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }
        h1, h3 {
            color: #BB86FC;
        }
        form {
            margin-bottom: 30px;
        }
        label {
            font-size: 14px;
            margin-bottom: 5px;
            display: block;
        }
        input[type="number"],
        input[type="text"],
        textarea,
        button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #BB86FC;
            border-radius: 5px;
            background-color: #2E2E2E;
            color: #FFFFFF;
        }
        button {
            background-color: #BB86FC;
            color: white;
            border: none;
        }
        button:hover {
            background-color: #9C4DFF;
        }
        .error-message {
            color: #FF5252;
            font-size: 14px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #BB86FC;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #2E2E2E;
        }
        .message {
            background-color: #1B5E20;
            padding: 10px;
            border: 1px solid #388E3C;
            color: #A5D6A7;
            margin-bottom: 20px;
        }
        @media (max-width: 768px) {
            .container {
                width: 100%;
                padding: 15px;
            }
            input, textarea, button {
                font-size: 14px;
            }
            th, td {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Fetch and Edit Records</h1>

        <!-- Display error or success message -->
        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <!-- Fetch by ID Form -->
        <form method="POST">
            <h3>Fetch Record by ID</h3>
            <input type="hidden" name="action" value="fetch_by_id">
            <label for="id">Enter ID:</label>
            <input type="number" id="id" name="id" required>
            <button type="submit">Fetch</button>
        </form>

        <!-- Fetch by Username Form -->
        <form method="POST">
            <h3>Fetch Records by Username</h3>
            <input type="hidden" name="action" value="fetch_by_username">
            <label for="username">Enter Username:</label>
            <input type="text" id="username" name="username" required>
            <button type="submit">Fetch</button>
        </form>

        <!-- Show Fetched Data -->
        <?php if ($data): ?>
            <?php if (isset($data['id'])): ?>
                <h3>Record Details</h3>
                <p><strong>ID:</strong> <?= htmlspecialchars($data['id']) ?></p>
                <p><strong>Username:</strong> <?= htmlspecialchars($data['username']) ?></p>
                <p><strong>Text:</strong> <?= htmlspecialchars($data['text']) ?></p>
                <p><strong>Date:</strong> <?= htmlspecialchars($data['date']) ?></p>

                <!-- Edit Text Form -->
                <form method="POST">
                    <h3>Edit Text</h3>
                    <input type="hidden" name="action" value="update_text">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($data['id']) ?>">
                    <label for="new_text">New Text:</label>
                    <textarea id="new_text" name="new_text" required><?= htmlspecialchars($data['text']) ?></textarea>
                    <button type="submit">Update</button>
                </form>
            <?php else: ?>
                <h3>Records for Username: <?= htmlspecialchars($_POST['username']) ?></h3>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Text</th>
                        <th>Date</th>
                    </tr>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars($row['text']) ?></td>
                            <td><?= htmlspecialchars($row['date']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        <?php endif; ?>
        <a href='<?php echo $tools_view_data_url ?>'><button>View All Data</button></a>
    </div>
</body>
</html>