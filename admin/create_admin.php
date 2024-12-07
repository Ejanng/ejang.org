    <?php
    session_start(); // Start the session to access session data

    include '../config.php'; // Include the config file
    require $admin_id_url;  // Include the id file
    require $admin_db_url; // Ensure this file connects to your database
    require $admin_role_url; // Include the role file

    // Process the form when it's submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the username and password from the form
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Hash the password using bcrypt
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert the new admin user into the database
        try {
            $stmt = $conn->prepare("INSERT INTO users (username, pass, role) VALUES (:username, :pass, 'admin')");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':pass', $hashedPassword, PDO::PARAM_STR);

            // Execute the query to insert the new admin user
            $stmt->execute();

            $message = "New admin created successfully!";
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create New Admin</title>
    </head>
    <body>
        <h1>Create New Admin</h1>

        <!-- Display success or error message -->
        <?php if (isset($message)) { echo "<p>$message</p>"; } ?>

        <!-- Form to create new admin user -->
        <form method="POST" action="create_admin.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>

            <button type="submit">Create Admin</button>
        </form>
        <a href='<?php echo $admin_index_url ?>'>Back to Form</a>
    </body>
    </html>
