<?php

include '../config/db_config.php';

session_start();

// Check if user is logged in and if user_id is 1
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    header('Location: login.php'); // Redirect to login page if not logged in or user_id is not 1
    exit();
}


// Processar a inserção e atualização de uma nota
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'insert') {
        $id_subject = $_POST['id_subject'];
        $id_user = $_POST['id_user'];
        $score = $_POST['score'];
        $quarter = $_POST['quarter'];
        $type_test = $_POST['type_test'];
        $class = $_POST['class'];

        $sql = "INSERT INTO Grades (id_subject, id_user, score, quarter, type_test, class)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iidsis", $id_subject, $id_user, $score, $quarter, $type_test, $class);

        if ($stmt->execute()) {
            echo "<script>alert('Nota cadastrada com sucesso!'); window.location.href = 'grades.php';</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar nota: " . $conn->error . "');</script>";
        }

        $stmt->close();
    } elseif ($_POST['action'] == 'update') {
        $id_grade = $_POST['id_grade'];
        $id_subject = $_POST['id_subject'];
        $id_user = $_POST['id_user'];
        $score = $_POST['score'];
        $quarter = $_POST['quarter'];
        $type_test = $_POST['type_test'];
        $class = $_POST['class'];

        $sql = "UPDATE Grades SET id_subject = ?, id_user = ?, score = ?, quarter = ?, type_test = ?, class = ? WHERE id_grade = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iidsisi", $id_subject, $id_user, $score, $quarter, $type_test, $class, $id_grade);

        if ($stmt->execute()) {
            echo "<script>alert('Nota atualizada com sucesso!'); window.location.href = 'grades.php';</script>";
        } else {
            echo "<script>alert('Erro ao atualizar nota: " . $conn->error . "');</script>";
        }

        $stmt->close();
    }
}

// Processar a exclusão de uma nota
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id_grade'])) {
    $id_grade = $_GET['id_grade'];

    $sql = "DELETE FROM Grades WHERE id_grade = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_grade);

    if ($stmt->execute()) {
        echo "<script>alert('Nota excluída com sucesso!'); window.location.href = 'grades.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir nota: " . $conn->error . "'); window.location.href = 'grades.php';</script>";
    }

    $stmt->close();
}

// Buscar dados para exibir na tabela
$sql = "SELECT g.id_grade, c.name AS course_name, g.class, u.shift, g.quarter, g.type_test, u.name AS student_name, s.name AS subject_name, g.score
        FROM Grades g
        JOIN subjects s ON g.id_subject = s.id_subject
        JOIN users u ON g.id_user = u.id_user
        JOIN courses c ON s.id_course = c.id_course";

$result = $conn->query($sql);

?>