<?php
    include '../controllers/GradesControllers.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Notas</title>
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
                <h2>Gerenciamento de Notas</h2>

                <!-- Campo de pesquisa -->
                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Pesquisar por aluno..." class="form-control mb-3">
                <div class="input-group-append mb-3">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cadastroModal">Cadastrar Nota</button>
                </div>

                <!-- Tabela de Notas -->
                <div class="table-responsive mt-3">
                    <table class="table table-bordered" id="gradesTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Curso</th>
                                <th>Classe</th>
                                <th>Turno</th>
                                <th>Trimestre</th>
                                <th>Tipo de Prova</th>
                                <th>Aluno</th>
                                <th>Disciplina</th>
                                <th>Nota</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['id_grade']; ?></td>
                                        <td><?php echo $row['course_name']; ?></td>
                                        <td><?php echo $row['class']; ?></td>
                                        <td><?php echo $row['shift']; ?></td>
                                        <td><?php echo $row['quarter']; ?></td>
                                        <td><?php echo $row['type_test']; ?></td>
                                        <td><?php echo $row['student_name']; ?></td>
                                        <td><?php echo $row['subject_name']; ?></td>
                                        <td><?php echo $row['score']; ?></td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updateModal" onclick="openUpdateModal(<?php echo htmlspecialchars(json_encode($row)); ?>)">Editar</button>
                                            <a href="?action=delete&id_grade=<?php echo $row['id_grade']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Você tem certeza que deseja excluir esta nota?')">Excluir</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="10">Nenhuma nota encontrada.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Cadastro -->
    <div class="modal fade" id="cadastroModal" tabindex="-1" aria-labelledby="cadastroModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cadastroModalLabel">Cadastrar Nota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="insertForm" method="post" action="grades.php">
                        <input type="hidden" name="action" value="insert">
                        <div class="mb-3">
                            <label for="id_subject" class="form-label">Disciplina</label>
                            <select class="form-select" id="id_subject" name="id_subject" required>
                                <option value="">Selecione uma disciplina</option>
                                <?php
                                // Preencher as disciplinas
                                $sql_subjects = "SELECT id_subject, name FROM subjects";
                                $result_subjects = $conn->query($sql_subjects);
                                while($row_subject = $result_subjects->fetch_assoc()) {
                                    echo "<option value='" . $row_subject['id_subject'] . "'>" . $row_subject['name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_user" class="form-label">Aluno</label>
                            <select class="form-select" id="id_user" name="id_user" required>
                                <option value="">Selecione um aluno</option>
                                <?php
                                // Preencher os alunos
                                $sql_users = "SELECT id_user, name FROM users";
                                $result_users = $conn->query($sql_users);
                                while($row_user = $result_users->fetch_assoc()) {
                                    echo "<option value='" . $row_user['id_user'] . "'>" . $row_user['name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="score" class="form-label">Nota</label>
                            <input type="text" class="form-control" id="score" name="score" required>
                        </div>
                        <div class="mb-3">
                            <label for="quarter" class="form-label">Trimestre</label>
                            <input type="text" class="form-control" id="quarter" name="quarter" required>
                        </div>
                        <div class="mb-3">
                            <label for="type_test" class="form-label">Tipo de Prova</label>
                            <input type="text" class="form-control" id="type_test" name="type_test" required>
                        </div>
                        <div class="mb-3">
                            <label for="class" class="form-label">Classe</label>
                            <input type="text" class="form-control" id="class" name="class" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Atualização -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Atualizar Nota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateForm" method="post" action="grades.php">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id_grade" id="update_id_grade">
                        <div class="mb-3">
                            <label for="update_id_subject" class="form-label">Disciplina</label>
                            <select class="form-select" id="update_id_subject" name="id_subject" required>
                                <option value="">Selecione uma disciplina</option>
                                <?php
                                // Preencher as disciplinas
                                $result_subjects->data_seek(0); // Reset the result pointer
                                while($row_subject = $result_subjects->fetch_assoc()) {
                                    echo "<option value='" . $row_subject['id_subject'] . "'>" . $row_subject['name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="update_id_user" class="form-label">Aluno</label>
                            <select class="form-select" id="update_id_user" name="id_user" required>
                                <option value="">Selecione um aluno</option>
                                <?php
                                // Preencher os alunos
                                $result_users->data_seek(0); // Reset the result pointer
                                while($row_user = $result_users->fetch_assoc()) {
                                    echo "<option value='" . $row_user['id_user'] . "'>" . $row_user['name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="update_score" class="form-label">Nota</label>
                            <input type="text" class="form-control" id="update_score" name="score" required>
                        </div>
                        <div class="mb-3">
                            <label for="update_quarter" class="form-label">Trimestre</label>
                            <input type="text" class="form-control" id="update_quarter" name="quarter" required>
                        </div>
                        <div class="mb-3">
                            <label for="update_type_test" class="form-label">Tipo de Prova</label>
                            <input type="text" class="form-control" id="update_type_test" name="type_test" required>
                        </div>
                        <div class="mb-3">
                            <label for="update_class" class="form-label">Classe</label>
                            <input type="text" class="form-control" id="update_class" name="class" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/aos/aos.js"></script>
    <script src="../assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="../assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>

    <script>
        function filterTable() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#gradesTable tbody tr');
            
            rows.forEach(row => {
                const studentName = row.querySelector('td:nth-child(7)').textContent.toLowerCase();
                row.style.display = studentName.includes(searchInput) ? '' : 'none';
            });
        }

        function openUpdateModal(data) {
            document.getElementById('update_id_grade').value = data.id_grade;
            document.getElementById('update_id_subject').value = data.id_subject;
            document.getElementById('update_id_user').value = data.id_user;
            document.getElementById('update_score').value = data.score;
            document.getElementById('update_quarter').value = data.quarter;
            document.getElementById('update_type_test').value = data.type_test;
            document.getElementById('update_class').value = data.class;
        }
    </script>
</body>

</html>

<?php
// Fechar conexão
$conn->close();
?>
