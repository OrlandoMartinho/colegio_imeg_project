<?php

include '../config/db_config.php';

session_start();

// Check if user is logged in and if user_id is 1
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    header('Location: login.php'); // Redirect to login page if not logged in or user_id is not 1
    exit();
}

// Conexão com o banco de dados
$host = getenv('DB_SERVERNAME');
$dbname = getenv('DB_NAME');
$username = getenv('DB_USERNAME');
$password =  getenv('DB_PASSWORD');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Conexão falhou: ' . $e->getMessage());
}

// Adicionar disciplina
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        $stmt = $pdo->prepare("INSERT INTO subjects (name, id_course) VALUES (?, ?)");
        $stmt->execute([$_POST['name'], $_POST['id_course']]);
    } elseif (isset($_POST['action']) && $_POST['action'] == 'edit') {
        $stmt = $pdo->prepare("UPDATE subjects SET name = ?, id_course = ? WHERE id_subject = ?");
        $stmt->execute([$_POST['name'], $_POST['id_course'], $_POST['id_subject']]);
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $stmt = $pdo->prepare("DELETE FROM subjects WHERE id_subject = ?");
        $stmt->execute([$_POST['id_subject']]);
    }
}

// Obter disciplinas
$stmt = $pdo->query("SELECT s.id_subject, s.name AS subject_name, c.name AS course_name 
                     FROM subjects s 
                     JOIN courses c ON s.id_course = c.id_course");
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obter cursos
$courses = $pdo->query("SELECT * FROM courses")->fetchAll(PDO::FETCH_ASSOC);

?>