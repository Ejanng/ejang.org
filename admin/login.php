<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../config.php'; // Include the config file
require $admin_db_url; // Ensure this file connects to your database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Query the database for the user
        $stmt = $conn->prepare("SELECT id, username, pass, role, date_created FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the user exists and verify the password
        if ($user && password_verify($password, $user['pass'])) {
            // Store user details in session
            $_SESSION['id'] = $user['id'];                  // User's ID
            $_SESSION['username'] = $user['username'];      // Username
            $_SESSION['role'] = $user['role'];              // User role
            $_SESSION['date_created'] = $user['date_created']; // Account creation date

            // Redirect to index.php after successful login
            header("Location: $admin_index_url");
            exit;
        } else {
            $error = "Invalid username or password."; // Incorrect login details
        }
    } catch (Exception $e) {
        $error = "An error occurred: " . $e->getMessage(); // Handle exceptions
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= htmlspecialchars($gtag_id) ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?= htmlspecialchars($gtag_id) ?>');
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .login-container h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .login-container input[type="text"],
        .login-container input[type="password"],
        .login-container button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .login-container button {
            background-color: #007BFF;
            color: white;
            border: none;
        }
        .login-container button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            font-size: 14px;
            text-align: center;
        }
        .corner-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s ease;
            z-index: 1000; /* Ensure it stays on top */
        }

        .corner-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Admin Login</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <p>Do not Log in if you're not amdin!</p>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
    <a href="<?php echo $admin_index_url ?>" class="corner-button">Go to Main Page</a>
</body>
</html>
