<?php

include '../config/db_config.php';
// Start session
session_start();


// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    // Redirect based on user ID
    if ($_SESSION['user_id'] == 1) {
        header('Location: home.php');
    } else {
        header('Location: portal_do_aluno/index.php'); // Redirect to another page for other users
    }
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute query
    $sql = "SELECT id_user, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password based on user ID
        if ($user['id_user'] == 1) {
            // For user ID 1, compare passwords directly
            if ($password === $user['password']) {
                // Store user ID in session
                $_SESSION['user_id'] = $user['id_user'];

                // Redirect based on user ID
                header('Location: home.php');
                exit();
            } else {
                $error_message = "Invalid password.";
            }
        } else {
            // For other users, use password_verify
            if (password_verify($password, $user['password'])) {
                // Store user ID in session
                $_SESSION['user_id'] = $user['id_user'];

                // Redirect based on user ID
                header('Location: portal_do_aluno/index.php');
                exit();
            } else {
                $error_message = "Invalid password.";
            }
        }
    } else {
        $error_message = "User not found.";
    }
}

?>