<?php

require_once 'loadEnv.php';  // Inclui a função de carregamento do .env

// Carrega as variáveis de ambiente do arquivo .env localizado na raiz do projeto
loadEnv(__DIR__ . '../.env');

// Agora, você pode acessar as variáveis de ambiente usando getenv()
$servername = getenv('DB_SERVERNAME');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_NAME');

// Conectar ao banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se a conexão falhou
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";
