<?php 
    include '../config.php'; // Include the config file
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        // If the user is not logged in or not an admin, redirect to index.php or another page
        header("Location: $admin_login_url");
        exit; 
    }
?>