<?php
include 'db_config.php';

// Função para criar um novo aluno
function createStudent($name, $gender, $email, $room, $id_curso, $shift, $password, $photo) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO users (name, gender, email, room, id_curso, shift, password, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiisss", $name, $gender, $email, $room, $id_curso, $shift, $password, $photo);
    $stmt->execute();
    $stmt->close();
}

// Função para obter todos os alunos
function getAllStudents() {
    global $conn;
    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);
    $students = [];
    while($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
    return $students;
}

// Função para obter um aluno específico
function getStudent($id_user) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE id_user = ?");
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();
    return $student;
}

// Função para atualizar um aluno
function updateStudent($id_user, $name, $gender, $email, $room, $id_curso, $shift, $password, $photo) {
    global $conn;
    $stmt = $conn->prepare("UPDATE users SET name = ?, gender = ?, email = ?, room = ?, id_curso = ?, shift = ?, password = ?, photo = ? WHERE id_user = ?");
    $stmt->bind_param("sssiisssi", $name, $gender, $email, $room, $id_curso, $shift, $password, $photo, $id_user);
    $stmt->execute();
    $stmt->close();
}

// Função para excluir um aluno
function deleteStudent($id_user) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM users WHERE id_user = ?");
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $stmt->close();
}

// Fechar a conexão
$conn->close();
?>
