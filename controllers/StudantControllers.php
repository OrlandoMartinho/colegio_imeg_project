<?php

include '../config/db_config.php';
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php'); // Redirect to login page if not logged in
    exit();
}


// Get user ID from session
$user_id = $_SESSION['user_id'];

// Fetch student data
$sql_student = "SELECT * FROM users WHERE id_user = ?";
$stmt = $conn->prepare($sql_student);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result_student = $stmt->get_result();
$student = $result_student->fetch_assoc();

// Fetch grades
$sql_grades = "
    SELECT 
        g.id_grade, 
        g.class, 
        g.shift, 
        g.quarter, 
        g.type_test, 
        s.name AS subject_name, 
        g.score
    FROM 
        grades g 
    JOIN 
        subjects s ON g.id_subject = s.id_subject
    WHERE 
        g.id_user = ?";
$stmt = $conn->prepare($sql_grades);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result_grades = $stmt->get_result();

?>