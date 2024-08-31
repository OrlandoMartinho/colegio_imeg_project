<?php
    include '../controllers/SubjectsControllers.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Cabeçalho permanece o mesmo -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Disciplinas</title>
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
        <!-- Sidebar permanece o mesmo -->
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
                <h2>Gerenciamento de Disciplinas</h2>

                <!-- Campo de pesquisa -->
                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Pesquisar por nome..." class="form-control mb-3">
                <div class="input-group-append">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cadastroModal">Cadastrar Disciplina</button>
                </div>

                <!-- Tabela de Disciplinas -->
                <div class="table-responsive mt-3">
                    <table class="table table-bordered" id="subjectTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome da Disciplina</th>
                                <th>Curso</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="subjectTableBody">
                            <?php foreach ($subjects as $subject): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($subject['id_subject']); ?></td>
                                <td><?php echo htmlspecialchars($subject['subject_name']); ?></td>
                                <td><?php echo htmlspecialchars($subject['course_name']); ?></td>
                                <td>
                                    <a href="#" class="btn btn-warning btn-sm" onclick="openEditModal(<?php echo htmlspecialchars($subject['id_subject']); ?>, '<?php echo htmlspecialchars($subject['subject_name']); ?>', <?php echo htmlspecialchars($subject['id_subject']); ?>)">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <a href="#" class="btn btn-danger btn-sm" onclick="openDeleteModal(<?php echo htmlspecialchars($subject['id_subject']); ?>)">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para cadastrar disciplina -->
    <div class="modal fade" id="cadastroModal" tabindex="-1" aria-labelledby="cadastroModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cadastroModalLabel">Cadastrar Disciplina</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formCadastroDisciplina" method="post">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label for="inputDisciplinaNome" class="form-label">Nome da Disciplina</label>
                            <input type="text" class="form-control" id="inputDisciplinaNome" name="name" placeholder="Digite o nome da disciplina" required>
                        </div>
                        <div class="mb-3">
                            <label for="inputCurso" class="form-label">Curso</label>
                            <select class="form-select" id="inputCursoCadastro" name="id_course" required>
                                <?php foreach ($courses as $course): ?>
                                <option value="<?php echo htmlspecialchars($course['id_course']); ?>"><?php echo htmlspecialchars($course['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar disciplina -->
    <div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarModalLabel">Editar Disciplina</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarDisciplina" method="post">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" id="editDisciplinaId" name="id_subject">
                        <div class="mb-3">
                            <label for="editInputDisciplinaNome" class="form-label">Nome da Disciplina</label>
                            <input type="text" class="form-control" id="editInputDisciplinaNome" name="name" placeholder="Digite o nome da disciplina" required>
                        </div>
                        <div class="mb-3">
                            <label for="editInputCurso" class="form-label">Curso</label>
                            <select class="form-select" id="editInputCurso" name="id_course" required>
                                <?php foreach ($courses as $course): ?>
                                <option value="<?php echo htmlspecialchars($course['id_course']); ?>"><?php echo htmlspecialchars($course['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
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
                    Tem certeza de que deseja excluir esta disciplina? Esta ação não pode ser desfeita.
                </div>
                <div class="modal-footer">
                    <form id="formExcluirDisciplina" method="post">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" id="deleteDisciplinaId" name="id_subject">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Excluir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Javascript -->
    <script>
        function openEditModal(id, name, course_id) {
            document.getElementById('editDisciplinaId').value = id;
            document.getElementById('editInputDisciplinaNome').value = name;
            document.getElementById('editInputCurso').value = course_id;
            var modal = new bootstrap.Modal(document.getElementById('editarModal'));
            modal.show();
        }

        function openDeleteModal(id) {
            document.getElementById('deleteDisciplinaId').value = id;
            var modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            modal.show();
        }

        function filterTable() {
            var input = document.getElementById("searchInput");
            var filter = input.value.toLowerCase();
            var table = document.getElementById("subjectTable");
            var tr = table.getElementsByTagName("tr");

            for (var i = 1; i < tr.length; i++) {
                var td = tr[i].getElementsByTagName("td")[1];
                if (td) {
                    var txtValue = td.textContent || td.innerText;
                    tr[i].style.display = txtValue.toLowerCase().indexOf(filter) > -1 ? "" : "none";
                }
            }
        }
    </script>

    <!-- Scripts JS -->
    <script src="../assets/js/subjects.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/php-email-form/validate.js"></script>
    <script src="../assets/vendor/aos/aos.js"></script>
    <script src="../assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="../assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="../assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="../assets/vendor/swiper/swiper-bundle.min.js"></script>
   
</body>

</html>
