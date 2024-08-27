<?php
// Conectar ao banco de dados
$servername = "localhost"; // Ajuste conforme necessário
$username = "root"; // Ajuste conforme necessário
$password = ""; // Ajuste conforme necessário
$dbname = "colegio_imeg_bd";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeria</title>
    <!-- Bootstrap CSS -->
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/home.css">
    <link rel="stylesheet" href="../assets/css/galeria.css">
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="d-flex align-items-center justify-content-center mb-4">
            <img src="../assets/img/logo.png" alt="Logo" style="width: 50px;">
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="home.html">
                    <i class="fa-solid fa-chart-line"></i><span>Estado</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="mensagens.html">
                    <i class="fa-solid fa-envelope"></i><span>Mensagens</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="galeria.php">
                    <i class="fa-solid fa-image"></i><span>Destaques</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="alunos.html">
                    <i class="fa-solid fa-users"></i><span>Alunos</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="courses.html">
                    <i class="fa-solid fa-book"></i><span>Cursos</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="subjects.html">
                    <i class="fa-solid fa-book-open"></i><span>Disciplinas</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="grades.html">
                    <i class="fa-solid fa-table"></i><span>Notas</span>
                </a>
            </li>
        </ul>
        <div class="mt-auto">
            <a href="#" class="nav-link">
                <i class="fa-solid fa-sign-out-alt"></i><span>Sair</span>
            </a>
        </div>
    </div>

    <!-- Content -->
    <div class="content flex-grow-1">
        <h2>Galeria</h2>
        <!-- Área de Busca e Cadastro -->
        <div class="d-flex justify-content-between mb-3">
            <input type="text" class="form-control w-50" placeholder="Pesquisar">
            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#cadastrarImagemModal">Cadastrar</button>
        </div>
        <!-- Galeria de Imagens -->
        <div class="photos-container">
            <?php if ($photos->num_rows > 0): ?>
                <?php while($photo = $photos->fetch_assoc()): ?>
                    <div class="photo-item">
                        <img src="../assets/img/<?php echo htmlspecialchars($photo['photo']); ?>" class="img-fluid" alt="Foto <?php echo htmlspecialchars($photo['id_highlight']); ?>">
                        <div class="photo-actions">
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editPhotoModal" onclick="setEditPhotoDetails('<?php echo htmlspecialchars($photo['id_highlight']); ?>', '<?php echo htmlspecialchars($photo['category']); ?>', '<?php echo htmlspecialchars($photo['description']); ?>')">
                                <i class="fa-solid fa-edit"></i> Editar
                            </button>
                            <a class="btn btn-danger btn-sm" href="?delete=<?php echo htmlspecialchars($photo['id_highlight']); ?>">
                                <i class="fa-solid fa-trash"></i> Eliminar
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Nenhuma imagem encontrada.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal para Cadastrar Imagem -->
<div class="modal fade" id="cadastrarImagemModal" tabindex="-1" aria-labelledby="cadastrarImagemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cadastrarImagemModalLabel">Cadastrar Nova Imagem</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoria</label>
                        <input type="text" class="form-control" id="categoria" name="categoria" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <input type="text" class="form-control" id="descricao" name="descricao" required>
                    </div>
                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Imagem -->
<div class="modal fade" id="editPhotoModal" tabindex="-1" aria-labelledby="editPhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPhotoModalLabel">Editar Imagem</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <input type="hidden" id="photo_id" name="photo_id">
                    <div class="mb-3">
                        <label for="edit_categoria" class="form-label">Categoria</label>
                        <input type="text" class="form-control" id="edit_categoria" name="edit_categoria" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_descricao" class="form-label">Descrição</label>
                        <input type="text" class="form-control" id="edit_descricao" name="edit_descricao" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script>
function setEditPhotoDetails(id, categoria, descricao) {
    document.getElementById('photo_id').value = id;
    document.getElementById('edit_categoria').value = categoria;
    document.getElementById('edit_descricao').value = descricao;
}
</script>
</body>
</html>

<?php
$conn->close();
?>
