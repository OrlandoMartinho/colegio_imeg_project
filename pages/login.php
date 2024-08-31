<?php
    include '../controllers/LoginControllers.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
  <title>IMEG - Iniciar sessão</title>
  <!-- MDB icon -->
  <link rel="icon" href="../assets/img/logo.png" type="image/x-icon" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css" />
  <!-- Google Fonts Roboto -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" />
  <!-- MDB -->
  <link rel="stylesheet" href="../assets/css/bootstrap-login-form.min.css" />
  <!-- Main CSS File -->
  <link href="../assets/css/main.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/login.css">
</head>

<body>
  <!-- Start your project here-->
  <section class="vh-100" style="background-color: #F3AB40;">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
          <div class="card shadow-2-strong" style="border-radius: 1rem; height: 80vh;">
            <div class="card-body p-5 text-center">
              <img src="../assets/img/logo.png" alt="" class="logo" width="55">
              <h3 class="mb-5">Login</h3>

              <?php if (isset($error_message)): ?>
                  <div class="alert alert-danger" role="alert">
                      <?php echo htmlspecialchars($error_message); ?>
                  </div>
              <?php endif; ?>

              <form method="post" action="">
                <div class="form-outline mb-4">
                  <input type="email" id="typeEmailX-2" name="email" class="form-control form-control-lg" required />
                  <label class="form-label" for="typeEmailX-2">Email</label>
                </div>

                <div class="form-outline mb-4">
                  <input type="password" id="typePasswordX-2" name="password" class="form-control form-control-lg" required />
                  <label class="form-label" for="typePasswordX-2">Password</label>
                </div>

                <button class="btn btn-primary btn-lg btn-block" style="background-color: #F3AB40; margin-top: 8vh;" type="submit">Login</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End your project here-->

  <!-- Preloader -->
  <div id="preloader"></div>

  <script src="../assets/js/login.js"></script>
  <!-- MDB -->
  <script type="text/javascript" src="../assets/js/mdb.min.js"></script>
</body>

</html>

<?php
// Close database connection
$conn->close();
?>
