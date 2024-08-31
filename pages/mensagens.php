<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensagens Recebidas</title>
    <!-- Bootstrap CSS -->
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/home.css">
    <link rel="stylesheet" href="../assets/css/mensagem.css">
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
        <h2>Mensagens Recebidas</h2>
        <!-- Lista de Mensagens -->
        <div id="messages-container">
        <?php
            include '../controllers/MensagensControllers.php';
        ?>
        </div>
    </div>
</div>

<!-- Modal para Responder -->
<div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="replyModalLabel">Responder Mensagem</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" id="replyEmailHidden" name="replyEmail">
                    <div class="mb-3">
                        <label for="replyEmail" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="replyEmail" name="replyEmail" required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="replyMessage" class="form-label">Mensagem</label>
                        <textarea class="form-control" id="replyMessage" name="replyMessage" rows="4" required></textarea>
                    </div>
                    <button type="submit" name="reply_message" class="btn btn-primary">Enviar</button>
                </form>
            </div>
        </div>
    </div>
</div>



<script src="../assets/js/subjects.js"></script>
<script src="../../assets/js/loader.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/php-email-form/validate.js"></script>
<script src="../assets/vendor/aos/aos.js"></script>
<script src="../assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="../assets/vendor/purecounter/purecounter_vanilla.js"></script>
<script src="../assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
<script src="../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
<script src="../assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="../assets/js/mensagens.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const replyModal = document.getElementById('replyModal');
    replyModal.addEventListener('show.bs.modal', (event) => {
        const button = event.relatedTarget;
        const email = button.getAttribute('data-email');
        const replyEmailInput = document.getElementById('replyEmail');
        const replyEmailHidden = document.getElementById('replyEmailHidden');
        replyEmailInput.value = email;
        replyEmailHidden.value = email;
    });
});
</script>

</body>
</html>
