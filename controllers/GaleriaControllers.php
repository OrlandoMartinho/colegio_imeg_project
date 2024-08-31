<?php
include '../config/db_config.php';


session_start();

// Check if user is logged in and if user_id is 1
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    header('Location: login.php'); // Redirect to login page if not logged in or user_id is not 1
    exit();
}


// Inserir nova imagem
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['categoria'], $_POST['descricao'])) {
    $categoria = $conn->real_escape_string($_POST['categoria']);
    $descricao = $conn->real_escape_string($_POST['descricao']);
    
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $fileTmpPath = $_FILES['foto']['tmp_name'];
        $fileName = $_FILES['foto']['name'];
        $fileSize = $_FILES['foto']['size'];
        $fileType = $_FILES['foto']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        
        $newFileName = uniqid() . '.' . $fileExtension;
        $uploadFileDir = '../assets/img/';
        $dest_path = $uploadFileDir . $newFileName;
        
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $sql = "INSERT INTO highlights (title, category, description, photo) VALUES ('$descricao', '$categoria', '$descricao', '$newFileName')";
            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Imagem cadastrada com sucesso!'); </script>";
            } else {
                echo "<script>alert('Erro ao cadastrar imagem.');</script>";
            }
        }
    }
}

// Editar imagem
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_categoria'], $_POST['edit_descricao'], $_POST['photo_id'])) {
    $edit_categoria = $conn->real_escape_string($_POST['edit_categoria']);
    $edit_descricao = $conn->real_escape_string($_POST['edit_descricao']);
    $photo_id = (int)$_POST['photo_id'];

    $sql = "UPDATE highlights SET category='$edit_categoria', description='$edit_descricao' WHERE id_highlight=$photo_id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Imagem editada com sucesso!'); </script>";
    } else {
        echo "<script>alert('Erro ao editar imagem.');</script>";
    }
}

// Deletar imagem
if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];

    $sql = "SELECT photo FROM highlights WHERE id_highlight=$delete_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $photo_path = '../assets/img/' . $row['photo'];
        if (file_exists($photo_path)) {
            unlink($photo_path);
        }
    }

    $sql = "DELETE FROM highlights WHERE id_highlight=$delete_id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Imagem deletada com sucesso!'); </script>";
    } else {
        echo "<script>alert('Erro ao deletar imagem.');</script>";
    }
}

// Recuperar imagens
$sql = "SELECT * FROM highlights";
$photos = $conn->query($sql);

?>