<?php

session_start();

// Check if user is logged in and if user_id is 1
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    header('Location: login.php'); // Redirect to login page if not logged in or user_id is not 1
    exit();
}
// Conectar ao banco de dados
$host = 'localhost'; // Ou o endereço do seu servidor
$dbname = 'colegio_imeg_bd';
$username = 'root'; // Ou o usuário do seu banco de dados
$password = ''; // Ou a senha do seu banco de dados

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obter dados do desempenho dos alunos por disciplina
$performanceQuery = "SELECT subjects.name AS subject_name, users.name AS student_name, AVG(Grades.score) AS average_score
                     FROM Grades
                     JOIN users ON Grades.id_user = users.id_user
                     JOIN subjects ON Grades.id_subject = subjects.id_subject
                     GROUP BY subjects.id_subject, users.id_user";
$performanceResult = $conn->query($performanceQuery);

$studentPerformance = [];
if ($performanceResult->num_rows > 0) {
    while ($row = $performanceResult->fetch_assoc()) {
        $studentPerformance[] = $row;
    }
}

// Obter dados das fontes de receita (highlights)
$revenueQuery = "SELECT category, COUNT(id_highlight) AS count
                 FROM highlights
                 GROUP BY category";
$revenueResult = $conn->query($revenueQuery);

$revenueSources = [];
if ($revenueResult->num_rows > 0) {
    while ($row = $revenueResult->fetch_assoc()) {
        $revenueSources[] = $row;
    }
}

// Obter o total de alunos cadastrados
$totalStudentsQuery = "SELECT COUNT(*) AS total_students FROM users";
$totalStudentsResult = $conn->query($totalStudentsQuery);
$totalStudents = $totalStudentsResult->fetch_assoc()['total_students'];

// Obter o total de contatos (ou mensagens, dependendo da definição de "solicitações")
$totalContactsQuery = "SELECT COUNT(*) AS total_contacts FROM contacts";
$totalContactsResult = $conn->query($totalContactsQuery);
$totalContacts = $totalContactsResult->fetch_assoc()['total_contacts'];

// Obter o total de publicações
$totalHighlightsQuery = "SELECT COUNT(*) AS total_highlights FROM highlights";
$totalHighlightsResult = $conn->query($totalHighlightsQuery);
$totalHighlights = $totalHighlightsResult->fetch_assoc()['total_highlights'];

// Exemplo de total de solicitações pendentes - aqui usamos contatos como exemplo
$totalRequests = $totalContacts; // Substitua conforme necessário

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colégio Imeg Dashboard</title>
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <div class="cards-container">
                <div class="card shadow-sm">
                    <h5 class="card-title" style="color: #FE7D15;">Alunos Cadastrados</h5>
                    <p class="card-text display-6"><?php echo $totalStudents; ?></p>
                </div>
                <div class="card shadow-sm">
                    <h5 class="card-title" style="color: #FE7D15;">Contactos</h5>
                    <p class="card-text display-6"><?php echo $totalContacts; ?></p>
                </div>
                <div class="card shadow-sm">
                    <h5 class="card-title" style="color: #FE7D15;">Publicações</h5>
                    <p class="card-text display-6"><?php echo $totalHighlights; ?></p>
                </div>
                <div class="card shadow-sm">
                    <h5 class="card-title" style="color: #FE7D15;">Solicitações Pendentes</h5>
                    <p class="card-text display-6"><?php echo $totalRequests; ?></p>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="row mt-4">
                <div class="col-lg-8">
                    <div class="card shadow-sm chart-card">
                        <h5 class="card-title">Desempenho dos Alunos por Disciplina</h5>
                        <div class="chart-container">
                            <canvas id="studentPerformanceChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card shadow-sm chart-card">
                        <h5 class="card-title">Fontes de Receita</h5>
                        <div class="chart-container">
                            <canvas id="revenueSourcesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Dados do gráfico de desempenho dos alunos
    var studentPerformanceData = <?php echo json_encode($studentPerformance); ?>;
    var studentPerformanceLabels = studentPerformanceData.map(function(item) {
        return item.subject_name + ' - ' + item.student_name;
    });
    var studentPerformanceValues = studentPerformanceData.map(function(item) {
        return item.average_score;
    });

    var studentPerformanceCtx = document.getElementById('studentPerformanceChart').getContext('2d');
    new Chart(studentPerformanceCtx, {
        type: 'bar',
        data: {
            labels: studentPerformanceLabels,
            datasets: [{
                label: 'Média de Notas',
                data: studentPerformanceValues,
                backgroundColor: '#FE7D15',
                borderColor: '#FE7D15',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Dados do gráfico de fontes de receita
    var revenueSourcesData = <?php echo json_encode($revenueSources); ?>;
    var revenueSourcesLabels = revenueSourcesData.map(function(item) {
        return item.category;
    });
    var revenueSourcesValues = revenueSourcesData.map(function(item) {
        return item.count;
    });

    var revenueSourcesCtx = document.getElementById('revenueSourcesChart').getContext('2d');
    new Chart(revenueSourcesCtx, {
        type: 'pie',
        data: {
            labels: revenueSourcesLabels,
            datasets: [{
                label: 'Fontes de Receita',
                data: revenueSourcesValues,
                backgroundColor: ['#FE7D15', '#FF9F40', '#FFC107', '#FF5722'],
                borderColor: '#fff',
                borderWidth: 1
            }]
        }
    });
    </script>
</body>
</html>
