<?php
session_start(); // Initialize sessions
include '../config.php'; // Include the config file
require $admin_db_url; // Database connection

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
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'] ?? null; // Avoid undefined role
            $_SESSION['date_created'] = $user['date_created'];

            // Redirect to the admin page
            header("Location: $admin_leave_a_note_url");
            exit;
        } else {
            $error = "Invalid username or password."; // Incorrect credentials
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
            background-color: #121212; /* Dark mode background */
            color: #FFFFFF; /* Light text for readability */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }
        .login-container {
            background: #1E1E1E; /* Dark container background */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-container h1 {
            color: #BB86FC; /* Purple header */
            margin-bottom: 20px;
        }
        .login-container p {
            color: #BB86FC; /* Purple text for additional info */
            font-size: 14px;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            background-color: #2E2E2E; /* Dark input background */
            color: #FFFFFF; /* Light text */
            border: 1px solid #BB86FC; /* Purple border */
            border-radius: 5px;
            font-size: 14px;
        }
        .login-container input[type="text"]::placeholder,
        .login-container input[type="password"]::placeholder {
            color: #AAAAAA; /* Placeholder color */
        }
        .login-container button {
            width: 100%;
            padding: 12px;
            background-color: #BB86FC; /* Purple button */
            color: #FFFFFF;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
        }
        .login-container button:hover {
            background-color: #9C4DFF; /* Brighter purple on hover */
        }
        .error {
            color: #FF5252; /* Red for error messages */
            font-size: 14px;
            margin-bottom: 10px;
        }
        .corner-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #28a745; /* Green button */
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s ease;
            z-index: 1000; /* Ensure it stays on top */
        }
        .corner-button:hover {
            background-color: #218838; /* Darker green on hover */
        }

        /* Mobile responsive design */
        @media (max-width: 768px) {
            .login-container {
                width: 90%;
                padding: 15px;
            }
            .login-container h1 {
                font-size: 20px;
            }
            .login-container input[type="text"],
            .login-container input[type="password"] {
                font-size: 12px;
            }
            .login-container button {
                font-size: 12px;
                padding: 10px;
            }
            .corner-button {
                font-size: 14px;
                padding: 8px 16px;
            }
        }

        @media (max-width: 480px) {
            .login-container h1 {
                font-size: 18px;
            }
            .corner-button {
                font-size: 12px;
                padding: 6px 12px;
            }
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
            <p>Only Admins can Access this platform!</p>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
    <a href="<?php echo $admin_index_url ?>" class="corner-button">Go to Main Page</a>
</body>
</html>
