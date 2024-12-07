<?php
session_start();
include '../config.php'; // Include the config file
session_destroy(); // Destroy all session data
header("Location: $admin_index_url"); // Redirect to login page
exit;
?>
