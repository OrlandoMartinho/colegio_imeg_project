<?php
session_start();

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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Alunos</title>
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/home.css">
    <style>
        .img-thumbnail {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div class="d-flex">
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
                <h2>Gerenciamento de Alunos</h2>

                <!-- Campo de pesquisa -->
                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Pesquisar por aluno..." class="form-control mb-3">
                <div class="input-group-append">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cadastroModal">Cadastrar Aluno</button>
                </div>

                <!-- Tabela de Alunos -->
                <div class="table-responsive mt-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Foto</th>
                                <th>Nome</th>
                                <th>Gênero</th>
                                <th>Email</th>
                                <th>Sala</th>
                                <th>Turno</th>
                                <th>Curso</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($aluno = $alunos->fetch_assoc()) {
                                // Sanitizar e preparar o valor do id_course para consulta
                                $id_course = $conn->real_escape_string($aluno['id_course']);
                                
                                // Obter nome do curso
                                $courseQuery = $conn->query("SELECT name FROM courses WHERE id_course = '$id_course'");
                                $courseResult = $courseQuery->fetch_assoc();
                                $courseName = $courseResult ? htmlspecialchars($courseResult['name']) : 'Desconhecido';
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($aluno['id_user']); ?></td>
                                    <td>
                                        <?php if ($aluno['photo']) { ?>
                                            <img src="data:image/jpeg;base64,<?php echo base64_encode($aluno['photo']); ?>" alt="Foto de <?php echo htmlspecialchars($aluno['name']); ?>" class="img-thumbnail rounded-circle">
                                        <?php } ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($aluno['name']); ?></td>
                                    <td><?php echo htmlspecialchars($aluno['gender']); ?></td>
                                    <td><?php echo htmlspecialchars($aluno['email']); ?></td>
                                    <td><?php echo htmlspecialchars($aluno['room']); ?></td>
                                    <td><?php echo htmlspecialchars($aluno['shift']); ?></td>
                                    <td><?php echo $courseName; ?></td>
                                    <td>
                                        <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editarModal" onclick="editStudent(<?php echo $aluno['id_user']; ?>, '<?php echo addslashes($aluno['name']); ?>', '<?php echo addslashes($aluno['gender']); ?>', '<?php echo addslashes($aluno['email']); ?>', <?php echo $aluno['room']; ?>, '<?php echo addslashes($aluno['shift']); ?>', <?php echo $aluno['id_course']; ?>, '<?php echo base64_encode($aluno['photo']); ?>')">Editar</a>
                                        <a href="#" class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $aluno['id_user']; ?>)">Excluir</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cadastrar -->
    <div class="modal fade" id="cadastroModal" tabindex="-1" aria-labelledby="cadastroModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cadastroModalLabel">Cadastrar Aluno</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nome</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="gender">Gênero</label>
                            <input type="text" id="gender" name="gender" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="room">Sala</label>
                            <input type="number" id="room" name="room" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="shift">Turno</label>
                            <input type="text" id="shift" name="shift" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="id_course">Curso</label>
                            <select id="id_course" name="id_course" class="form-control" required>
                                <?php
                                $cursos->data_seek(0); // Resetar o cursor do resultado dos cursos
                                while ($course = $cursos->fetch_assoc()) { ?>
                                    <option value="<?php echo $course['id_course']; ?>"><?php echo htmlspecialchars($course['name']); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="photo">Foto</label>
                            <input type="file" id="photo" name="photo" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="action" value="create">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar -->
    <div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editarModalLabel">Editar Aluno</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editId" name="id">
                        <div class="form-group">
                            <label for="editName">Nome</label>
                            <input type="text" id="editName" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editGender">Gênero</label>
                            <input type="text" id="editGender" name="gender" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editEmail">Email</label>
                            <input type="email" id="editEmail" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editRoom">Sala</label>
                            <input type="number" id="editRoom" name="room" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editShift">Turno</label>
                            <input type="text" id="editShift" name="shift" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editCourse">Curso</label>
                            <select id="editCourse" name="id_course" class="form-control" required>
                                <?php
                                $cursos->data_seek(0); // Resetar o cursor do resultado dos cursos
                                while ($course = $cursos->fetch_assoc()) { ?>
                                    <option value="<?php echo $course['id_course']; ?>"><?php echo htmlspecialchars($course['name']); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editPhoto">Foto</label>
                            <input type="file" id="editPhotoInput" name="photo" class="form-control">
                            <img id="editPhoto" src="#" alt="Foto" class="img-thumbnail rounded-circle mt-2">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="action" value="update">
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Confirmar Exclusão -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Você tem certeza de que deseja excluir este aluno?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a id="confirmDeleteButton" class="btn btn-danger">Excluir</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/aos/aos.js"></script>
    <script src="../assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="../assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>

    <script>
        // Função para editar um aluno
        function editStudent(id, name, gender, email, room, shift, id_course, photo) {
            document.getElementById('editId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editGender').value = gender;
            document.getElementById('editEmail').value = email;
            document.getElementById('editRoom').value = room;
            document.getElementById('editShift').value = shift;
            document.getElementById('editCourse').value = id_course;
            document.getElementById('editPhoto').src = photo ? 'data:image/jpeg;base64,' + photo : '#';
        }

        // Função para confirmar a exclusão
        function confirmDelete(id) {
            var confirmDeleteButton = document.getElementById('confirmDeleteButton');
            confirmDeleteButton.href = '?action=delete&id=' + id;
            var confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            confirmDeleteModal.show();
        }

        // Função para filtrar a tabela
        function filterTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById('searchInput');
            filter = input.value.toUpperCase();
            table = document.querySelector('.table');
            tr = table.getElementsByTagName('tr');

            for (i = 1; i < tr.length; i++) {
                td = tr[i].getElementsByTagName('td');
                if (td) {
                    txtValue = td[2].textContent || td[2].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = '';
                    } else {
                        tr[i].style.display = 'none';
                    }
                }
            }
        }

        // Atualizar a imagem no modal de edição em tempo real
        document.getElementById('editPhotoInput').addEventListener('change', function(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('editPhoto');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        });
    </script>
</body>

</html>
