<?php

include '../config/db_config.php';

session_start();

// Check if user is logged in and if user_id is 1
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    header('Location: login.php'); // Redirect to login page if not logged in or user_id is not 1
    exit();
}


// Função para buscar cursos
function getCourses($conn) {
    $sql = "SELECT * FROM courses"; // Supondo que o nome da tabela seja `courses`
    $result = $conn->query($sql);
    return $result;
}

// Lógica para adicionar curso
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == 'add') {
        $name = $conn->real_escape_string($_POST['name']);
        $sql = "INSERT INTO courses (name) VALUES ('$name')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Curso cadastrado com sucesso!');
            </script>";
        } else {
            echo "<script>alert('Erro ao cadastrar curso: " . $conn->error . "');</script>";
        }
    } elseif ($action == 'edit') {
        $id = (int)$_POST['id'];
        $name = $conn->real_escape_string($_POST['name']);
        $sql = "UPDATE courses SET name='$name' WHERE id_course=$id";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Curso atualizado com sucesso!'); </script>";
        } else {
            echo "<script>alert('Erro ao atualizar curso: " . $conn->error . "');</script>";
        }
    } elseif ($action == 'delete') {
        $id = (int)$_POST['id'];
        $sql = "DELETE FROM courses WHERE id_course=$id";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Curso excluído com sucesso!'); </script>";
        } else {
            echo "<script>alert('Erro ao excluir curso: " . $conn->error . "');</script>";
        }
    }
}


?>
