<?php

include '../config/db_config.php';

session_start();

// Check if user is logged in and if user_id is 1
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    header('Location: login.php'); // Redirect to login page if not logged in or user_id is not 1
    exit();
}


// Obter dados do desempenho dos alunos por disciplina
$performanceQuery = "SELECT subjects.name AS subject_name, users.name AS student_name, AVG(Grades.score) AS average_score
                     FROM Grades
                     JOIN users ON Grades.id_user = users.id_user
                     JOIN subjects ON Grades.id_subject = subjects.id_subject
                     GROUP BY subjects.id_subject, users.id_user";
$performanceResult = $conn->query($performanceQuery);

$studentPerformance = [];
if ($performanceResult->num_rows > 0) {
    while ($row = $performanceResult->fetch_assoc()) {
        $studentPerformance[] = $row;
    }
}

// Obter dados das fontes de receita (highlights)
$revenueQuery = "SELECT category, COUNT(id_highlight) AS count
                 FROM highlights
                 GROUP BY category";
$revenueResult = $conn->query($revenueQuery);

$revenueSources = [];
if ($revenueResult->num_rows > 0) {
    while ($row = $revenueResult->fetch_assoc()) {
        $revenueSources[] = $row;
    }
}

// Obter o total de alunos cadastrados
$totalStudentsQuery = "SELECT COUNT(*) AS total_students FROM users";
$totalStudentsResult = $conn->query($totalStudentsQuery);
$totalStudents = $totalStudentsResult->fetch_assoc()['total_students'];

// Obter o total de contatos (ou mensagens, dependendo da definição de "solicitações")
$totalContactsQuery = "SELECT COUNT(*) AS total_contacts FROM contacts";
$totalContactsResult = $conn->query($totalContactsQuery);
$totalContacts = $totalContactsResult->fetch_assoc()['total_contacts'];

// Obter o total de publicações
$totalHighlightsQuery = "SELECT COUNT(*) AS total_highlights FROM highlights";
$totalHighlightsResult = $conn->query($totalHighlightsQuery);
$totalHighlights = $totalHighlightsResult->fetch_assoc()['total_highlights'];

// Exemplo de total de solicitações pendentes - aqui usamos contatos como exemplo
$totalRequests = $totalContacts; // Substitua conforme necessário

$conn->close();


?>