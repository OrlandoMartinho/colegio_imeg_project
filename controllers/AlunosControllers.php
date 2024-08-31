<?php
session_start();

include '../config/db_config.php';

// Check if user is logged in and if user_id is 1
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    header('Location: login.php'); // Redirect to login page if not logged in or user_id is not 1
    exit();
}
// Conectar ao banco de dados
$conn = new mysqli('localhost', 'root', '', 'colegio_imeg_bd');

// Verificar conexão
if ($conn->connect_error) {
    die('Conexão falhou: ' . $conn->connect_error);
}

// Função para lidar com o upload de imagem
function uploadImage($imageFile) {
    if ($imageFile['size'] > 0) {
        return file_get_contents($imageFile['tmp_name']);
    }
    return null;
}

// Criar, atualizar e excluir registros
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $email = $_POST['email'] ?? '';
    $room = $_POST['room'] ?? '';
    $shift = $_POST['shift'] ?? '';
    $id_course = $_POST['id_course'] ?? '';
    $photo = isset($_FILES['photo']) ? uploadImage($_FILES['photo']) : null;

    if ($action === 'create') {
        $sql = $conn->prepare("INSERT INTO users (name, gender, email, room, shift, id_course, password, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $password = password_hash('defaultpassword', PASSWORD_DEFAULT); // Alterar conforme necessário
        $sql->bind_param('ssssisis', $name, $gender, $email, $room, $shift, $id_course, $password, $photo);
        $sql->execute();
    } elseif ($action === 'update') {
        if ($photo) {
            $sql = $conn->prepare("UPDATE users SET name=?, gender=?, email=?, room=?, shift=?, id_course=?, photo=? WHERE id_user=?");
            $sql->bind_param('ssssisis', $name, $gender, $email, $room, $shift, $id_course, $photo, $id);
        } else {
            $sql = $conn->prepare("UPDATE users SET name=?, gender=?, email=?, room=?, shift=?, id_course=? WHERE id_user=?");
            $sql->bind_param('ssssisi', $name, $gender, $email, $room, $shift, $id_course, $id);
        }
        $sql->execute();
    } elseif ($action === 'delete') {
        $sql = $conn->prepare("DELETE FROM users WHERE id_user=?");
        $sql->bind_param('i', $id);
        $sql->execute();
    }
}

// Obter lista de alunos
$alunos = $conn->query("SELECT * FROM users WHERE id_user != 1");

// Obter lista de cursos para seleção
$cursos = $conn->query("SELECT * FROM courses");



?>
