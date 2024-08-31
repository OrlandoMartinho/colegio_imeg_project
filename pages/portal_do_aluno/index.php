<?php
    include '../controllers/StudantControllers.php';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Painel do Aluno - IMEG</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="../../assets/img/logo.png" rel="icon">
  <link href="../../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="../../assets/css/main.css" rel="stylesheet">
</head>

<body>

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid position-relative d-flex align-items-center justify-content-between">
      <a href="index.html" class="logo d-flex align-items-center me-auto me-xl-0">
        <img src="../../assets/img/logo.png" alt="">
        <h1 class="sitename">IMEG</h1><span>.</span>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#notas">Ver Notas</a></li>
          <li><a href="#perfil">Perfil</a></li>
          <li><a href="#dados">Meus Dados</a></li>
          <li><a href="#configuracoes">Configurações</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-getstarted" href="../logout.php">Sair</a>
    </div>
  </header>

  <main class="main">

    <!-- Notas Section -->
    <section id="notas" class="notas section light-background">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="section-header">
          <h2>Suas Notas</h2>
          <p>Abaixo estão suas notas mais recentes.</p>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <table class="table table-bordered" id="gradesTable">
              <thead>
                  <tr>
                      <th>ID</th>
                      <th>Classe</th>
                      <th>Turno</th>
                      <th>Trimestre</th>
                      <th>Tipo de Prova</th>
                      <th>Disciplina</th>
                      <th>Nota</th>
                  </tr>
              </thead>
              <tbody>
                  <?php if ($result_grades->num_rows > 0): ?>
                      <?php while ($row = $result_grades->fetch_assoc()): ?>
                          <tr>
                              <td><?php echo htmlspecialchars($row['id_grade']); ?></td>
                              <td><?php echo htmlspecialchars($row['class']); ?></td>
                              <td><?php echo htmlspecialchars($row['shift']); ?></td>
                              <td><?php echo htmlspecialchars($row['quarter']); ?></td>
                              <td><?php echo htmlspecialchars($row['type_test']); ?></td>
                              <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                              <td><?php echo htmlspecialchars($row['score']); ?></td>
                          </tr>
                      <?php endwhile; ?>
                  <?php else: ?>
                      <tr>
                          <td colspan="7">Nenhuma nota encontrada.</td>
                      </tr>
                  <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section><!-- /Notas Section -->

    <!-- Perfil Section -->
    <section id="perfil" class="perfil section dark-background">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="section-header">
          <h2>Perfil do Aluno</h2>
          <p>Verifique suas informações pessoais.</p>
        </div>
        <div class="row">
          <div class="col-lg-4">
            <img src="../../assets/img/frod.jpg" class="img-fluid" alt="Perfil do Aluno" width="200">
          </div>
          <div class="col-lg-8">
            <h3><?php echo htmlspecialchars($student['name']); ?></h3>
            <ul class="list-unstyled">
              <li><strong>Matrícula:</strong> <?php echo htmlspecialchars($student['id_user']); ?></li>
              <li><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></li>
              <li><strong>Curso:</strong> <?php echo htmlspecialchars($student['course']); ?></li>
            </ul>
          </div>
        </div>
      </div>
    </section><!-- /Perfil Section -->

    <!-- Meus Dados Section -->
    <section id="dados" class="dados section light-background">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="section-header">
          <h2>Meus Dados</h2>
          <p>Verifique e atualize suas informações.</p>
        </div>
        <form method="post" action="update_student.php">
          <div class="row mb-3">
            <label for="nome" class="col-sm-2 col-form-label">Nome Completo</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="nome" name="name" value="<?php echo htmlspecialchars($student['name']); ?>">
            </div>
          </div>
          <div class="row mb-3">
            <label for="email" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
              <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>">
            </div>
          </div>
          <div class="row mb-3">
            <label for="curso" class="col-sm-2 col-form-label">Curso</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="curso" name="course" value="<?php echo htmlspecialchars($student['course']); ?>">
            </div>
          </div>
          <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </form>
      </div>
    </section><!-- /Meus Dados Section -->

    <!-- Configurações Section -->
    <section id="configuracoes" class="configuracoes section dark-background">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="section-header">
          <h2>Configurações</h2>
          <p>Ajuste suas preferências e privacidade.</p>
        </div>
        <form method="post" action="update_settings.php">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="notificacoes" name="notifications" <?php echo $student['notifications'] ? 'checked' : ''; ?>>
            <label class="form-check-label" for="notificacoes">
              Receber notificações por email
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="privacidade" name="privacy" <?php echo $student['privacy'] ? 'checked' : ''; ?>>
            <label class="form-check-label" for="privacidade">
              Manter perfil privado
            </label>
          </div>
          <button type="submit" class="btn btn-primary mt-3">Salvar Configurações</button>
        </form>
      </div>
    </section><!-- /Configurações Section -->

  </main>
   
  <footer id="footer" class="footer">
    <div class="container">
        <div class="copyright" style="width: 100%;">
            &copy; Copyright <strong><span>IMEG</span></strong>. Todos os direitos reservados.
          </div>
    </div>
  </footer>

  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../assets/vendor/aos/aos.js"></script>
  <script src="../../assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="../../assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="../../assets/js/portal_aluno.js"></script>
  <script src="../../assets/js/loader.js"></script>
</body>

</html>

<?php
// Close database connection
$conn->close();
?>
