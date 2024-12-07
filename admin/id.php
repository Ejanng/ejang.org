<?php 
    include '../config.php'; // Include the config file
    // Restrict access to user with ID 1
    if (!isset($_SESSION['id']) || $_SESSION['id'] !== 1) {
        header("Location: $admin_login_url"); // Redirect to index.php if not authorized
        exit;
    }
?>
