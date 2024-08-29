<?php
session_start();

// Check if user is logged in and if user_id is 1
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    header('Location: login.php'); // Redirect to login page if not logged in or user_id is not 1
    exit();
}
// Conexão com o banco de dados
$servername = "localhost"; // ou o nome do seu servidor
$username = "root"; // seu nome de usuário do banco de dados
$password = ""; // sua senha do banco de dados
$dbname = "colegio_imeg_bd";

// Criação da conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificação da conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Cursos</title>
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/home.css">
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
                    <a class="nav-link" href="home.php">
                        <i class="fa-solid fa-chart-line"></i><span>Estado</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="mensagens.php">
                        <i class="fa-solid fa-envelope"></i><span>Mensagens</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="galeria.php">
                        <i class="fa-solid fa-image"></i><span>Destaques</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="alunos.php">
                        <i class="fa-solid fa-users"></i><span>Alunos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="courses.php">
                        <i class="fa-solid fa-book"></i><span>Cursos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="subjects.php">
                        <i class="fa-solid fa-book-open"></i><span>Disciplinas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="grades.php">
                        <i class="fa-solid fa-table"></i><span>Notas</span>
                    </a>
                </li>
            </ul>
            <div class="mt-auto">
                <a href="logout.php" class="nav-link">
                    <i class="fa-solid fa-sign-out-alt"></i><span>Sair</span>
                </a>
            </div>
        </div>

        <!-- Content -->
        <div class="content flex-grow-1">
            <div class="container mt-4">
                <h2>Gerenciamento de Cursos</h2>
                
                <!-- Campo de pesquisa -->
                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Pesquisar por nome..." class="form-control mb-3">
                <div class="input-group-append mb-3">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cadastroModal">Cadastrar Curso</button>
                </div>

                <!-- Tabela de Cursos -->
                <div class="table-responsive mt-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome do Curso</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="courseTableBody">
                            <?php
                            $courses = getCourses($conn);
                            while ($row = $courses->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$row['id_course']}</td>
                                    <td>{$row['name']}</td>
                                    <td>
                                        <a href='#' class='btn btn-warning btn-sm' onclick='openEditModal({$row['id_course']}, \"{$row['name']}\")'>
                                            <i class='fa-solid fa-pen'></i>
                                        </a>
                                        <a href='#' class='btn btn-danger btn-sm' onclick='openDeleteModal({$row['id_course']})'>
                                            <i class='fa-solid fa-trash'></i>
                                        </a>
                                    </td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para cadastrar curso -->
    <div class="modal fade" id="cadastroModal" tabindex="-1" aria-labelledby="cadastroModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cadastroModalLabel">Cadastrar Curso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formCadastroCurso" method="post">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label for="inputCursoNome" class="form-label">Nome do Curso</label>
                            <input type="text" class="form-control" id="inputCursoNome" name="name" placeholder="Digite o nome do curso">
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar curso -->
    <div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarModalLabel">Editar Curso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarCurso" method="post">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" id="editCursoId" name="id">
                        <div class="mb-3">
                            <label for="editInputCursoNome" class="form-label">Nome do Curso</label>
                            <input type="text" class="form-control" id="editInputCursoNome" name="name" placeholder="Digite o nome do curso">
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar alterações</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para confirmar exclusão -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Tem certeza de que deseja excluir este curso? Esta ação não pode ser desfeita.
                </div>
                <div class="modal-footer">
                    <form id="formDeleteCurso" method="post">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" id="deleteCursoId" name="id">
                        <button type="submit" class="btn btn-danger">Excluir</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/php-email-form/validate.js"></script>
    <script src="../assets/vendor/aos/aos.js"></script>
    <script src="../assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="../assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="../assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="../assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script>
        function filterTable() {
            let input = document.getElementById('searchInput').value.toLowerCase();
            let rows = document.querySelectorAll('table tbody tr');

            rows.forEach(row => {
                let cells = row.querySelectorAll('td');
                let found = false;

                cells.forEach(cell => {
                    if (cell.textContent.toLowerCase().includes(input)) {
                        found = true;
                    }
                });

                row.style.display = found ? '' : 'none';
            });
        }

        function openEditModal(id, name) {
            document.getElementById('editCursoId').value = id;
            document.getElementById('editInputCursoNome').value = name;
            new bootstrap.Modal(document.getElementById('editarModal')).show();
        }

        function openDeleteModal(id) {
            document.getElementById('deleteCursoId').value = id;
            new bootstrap.Modal(document.getElementById('confirmDeleteModal')).show();
        }
    </script>
</body>
</html>

<?php
// Fechamento da conexão
$conn->close();
?>
