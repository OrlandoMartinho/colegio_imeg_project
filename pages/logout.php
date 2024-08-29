<?php
session_start(); // Start the session

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Destroy the session
    session_unset();
    session_destroy();
}

// Redirect to the login page
header('Location: login.php');
exit();
?>
