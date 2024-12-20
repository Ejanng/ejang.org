<?php
    session_start(); // Start the session to access session data
    include 'config.php'; // Include the config file
    // echo  $_SESSION['id'];
    // echo $_SESSION['role'];
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
    <script src="reload.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave a Note?</title>
    <style>
        /* Resetting styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #121212;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            max-width: 400px;
            width: 90%;
            background-color: #1E1E1E;
            color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .form-container h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #BB86FC;
        }

        .form-container p {
            margin: 10px 0;
            font-size: 18px;
        }

        .form-container label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-container input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px 0;
            border: 1px solid #444;
            border-radius: 5px;
            font-size: 16px;
            background-color: #353b48;
            color: #fff;
        }

        .form-container button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            color: #fff;
            background-color: #BB86FC;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #9C4DFF;
        }

        .view-data {
            margin-top: 20px;
        }

        .view-data a {
            display: inline-block;
            text-decoration: none;
            color: #fff;
            font-size: 14px;
        }

        .view-data button {
            background-color: #28a745;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .view-data button:hover {
            background-color: #218838;
        }

        .view-board-button {
            display: inline-block;
            margin-top: 15px;
            font-size: 14px;
            text-decoration: none;
            color: #BB86FC;
            padding: 10px 20px;
            border: 2px solid #BB86FC;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .view-board-button:hover {
            background-color: #BB86FC;
            color: #fff;
        }

        .button-container {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
            z-index: 1000;
        }

        .login-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #BB86FC;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .login-button:hover {
            background-color: #9C4DFF;
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
            }

            .form-container h1 {
                font-size: 20px;
            }

            .form-container button {
                font-size: 14px;
                padding: 10px;
            }
        }

        @media (max-width: 1024px) and (min-width: 768px) {
            .form-container {
                width: 70%;
                padding: 30px;
            }

            .form-container h1 {
                font-size: 24px;
            }

            .form-container button {
                font-size: 16px;
                padding: 10px;
            }
        }

        @media (max-width: 600px) {
            .form-container {
                padding: 15px;
            }

            .form-container h1 {
                font-size: 18px;
            }

            .form-container button {
                font-size: 14px;
                padding: 10px;
            }

            .button-container {
                position: relative;
                align-items: center;
            }

            .login-button {
                padding: 8px 12px;
                font-size: 12px;
            }
        }

    </style>
</head>
<body>
    <div class="form-container">
        <h1>Leave a Note?</h1>
        <p>We'd love to hear your thoughts! Please take a moment to fill out the form below.</p>
        <form action="<?php echo $save_url; ?>" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>
            <label for="text">Message:</label>
            <input type="text" id="text" name="text" placeholder="Write your message" required>
            <button type="submit">Submit</button>
        </form>
        <div class="view-data">
            <a href="<?php echo $view_data_url; ?>">
                <button>View All Data</button>
            </a>
        </div>
        <a href="<?php echo $galaxy_url; ?>" class="view-board-button">View Galaxy</a>
    </div>
    <div class="button-container">
        <a href="<?= htmlspecialchars("113/search.php") ?>" class="login-button">Search</a>
        <?php if ($_SESSION['role'] != 'admin'): ?>
            <a href="<?= htmlspecialchars($login_url) ?>" class="login-button">Admin Login</a>
        <?php endif; ?>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="<?= htmlspecialchars($logout_url) ?>" class="login-button">Log Out</a>
            <a href="<?= htmlspecialchars($deleted_user_url) ?>" class="login-button">Check Deleted Users</a>
            <a href="<?= htmlspecialchars($view_edit_url) ?>" class="login-button">Admin Edits</a>
            <a href="<?= htmlspecialchars($user_url) ?>" class="login-button">All User</a>
        <?php endif; ?>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin' && isset($_SESSION['id']) && $_SESSION['id'] == 1): ?>
            <a href="<?= htmlspecialchars($user_admin_url) ?>" class="login-button">Admin User</a>
            <a href="<?= htmlspecialchars($create_admin_url) ?>" class="login-button">Create Admin</a>
            <a href="<?= htmlspecialchars($delete_user_url) ?>" class="login-button">Delete User</a>
        <?php endif; ?>
    </div>
</body>
</html>
