<?php
session_start(); // Start the session to access session data
include '../config.php'; // Include the config file
require $tools_db_url; // Ensure this file connects to your database
require $tools_role_url; // Include the role file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Start the transaction at the beginning of the request
    try {
        // Begin the transaction at the start of the request
        $conn->beginTransaction();

        // Get the ID from the user input
        $idToDelete = (int)$_POST['id'];

        // Fetch the record to be deleted
        $stmt = $conn->prepare("SELECT * FROM Messages WHERE id = :id");
        $stmt->bindParam(':id', $idToDelete, PDO::PARAM_INT);
        $stmt->execute();
        $recordToDelete = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($recordToDelete) {
            // Insert the deleted record into the DeletedMessages table
            $logStmt = $conn->prepare("INSERT INTO DeletedMessages (original_id, username, text) 
                                       VALUES (:original_id, :username, :text)");
            $logStmt->bindParam(':original_id', $recordToDelete['id'], PDO::PARAM_INT);
            $logStmt->bindParam(':username', $recordToDelete['username'], PDO::PARAM_STR);
            $logStmt->bindParam(':text', $recordToDelete['text'], PDO::PARAM_STR);
            $logStmt->execute();

            // Delete the specific row from the Messages table
            $deleteStmt = $conn->prepare("DELETE FROM Messages WHERE id = :id");
            $deleteStmt->bindParam(':id', $idToDelete, PDO::PARAM_INT);
            $deleteStmt->execute();

            // Reorder the IDs to be sequential
            $conn->exec("SET @row_number = 0");
            $conn->exec("UPDATE Messages SET id = (@row_number := @row_number + 1) ORDER BY id");

            // Reset the AUTO_INCREMENT value
            $conn->exec("ALTER TABLE Messages AUTO_INCREMENT = 1");

            // Commit transaction after all operations are successful
            $conn->commit();
        } else {
            // Rollback transaction if no record found
            $conn->rollBack();
        }
    } catch (Exception $e) {
        // Rollback if an exception occurs during the transaction
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
    } finally {
        // Close the connection (optional, PDO handles it automatically)
        $conn = null;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Record</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212; /* Dark background */
            color: #FFFFFF; /* White text */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: #1E1E1E; /* Dark container background */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        .form-container h1 {
            text-align: center;
            color: #BB86FC; /* Light purple text */
        }
        .form-container form {
            margin-top: 20px;
        }
        .form-container label {
            font-size: 14px;
            margin-bottom: 5px;
            display: block;
        }
        .form-container input[type="number"],
        .form-container button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #BB86FC;
            border-radius: 5px;
            background-color: #2E2E2E; /* Dark input fields */
            color: #FFFFFF;
        }
        .form-container button {
            background-color: #BB86FC; /* Purple button */
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #9C4DFF; /* Lighter purple on hover */
        }
        .message {
            text-align: center;
            margin-top: 10px;
            color: green;
        }
        .error {
            color: red;
        }
        @media (max-width: 768px) {
            .form-container {
                width: 90%;
                padding: 15px;
            }
            .form-container h1 {
                font-size: 18px;
            }
            .form-container input[type="number"],
            .form-container button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Delete Record</h1>
        <form method="POST" action="">
            <label for="id">Enter ID to Delete:</label>
            <input type="number" name="id" id="id" placeholder="Enter ID" required>
            <button type="submit">Delete</button>
        </form>
        <div class="message">
            <?php if (isset($message)) echo $message; ?>
        </div>
        <a href='<?php echo $tools_view_data_url ?>'>
            <button>View All Data</button>
        </a>
        <a href='<?php echo $tools_edit_url ?>'>
            <button>Edit Data</button>
        </a>
    </div>
</body>
</html>
