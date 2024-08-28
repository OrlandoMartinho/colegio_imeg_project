<?php
 require_once('connection.php');


 function register($name, $gender, $room, $id_course, $type, $token, $password, $photo) {
    global $conn;
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    $sql = "INSERT INTO Users (name, gender, room, id_course, type, token, password, photo) 
            VALUES ('$name', '$gender', '$room', '$id_course', '$type', '$token', '$passwordHash', '$photo')";
    if ($conn->query($sql) === TRUE) {
        return "User registered successfully.";
    } else {
        return "Error: " . $sql . "<br>" . $conn->error;
    }
 }

// Função para editar um usuário existente
function edit($id_user, $name, $gender, $room, $id_course, $type, $token, $password, $photo) {
    global $conn;
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    $sql = "UPDATE Users SET 
            name='$name', gender='$gender', room='$room', id_course='$id_course', type='$type', 
            token='$token', password='$passwordHash', photo='$photo' 
            WHERE id_user=$id_user";
    if ($conn->query($sql) === TRUE) {
        return "User updated successfully.";
    } else {
        return "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Função para deletar um usuário
function delete($id_user) {
    global $conn;
    $sql = "DELETE FROM Users WHERE id_user=$id_user";
    if ($conn->query($sql) === TRUE) {
        return "User deleted successfully.";
    } else {
        return "Error: " . $sql . "<br>" . $conn->error;
    }
}


// Função para autenticar um usuário
function authenticate($email, $password) {
    global $conn;
    session_start();  // Inicia a sessão

    // Prepara a consulta SQL para evitar SQL Injection
    $stmt = $conn->prepare("SELECT * FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verifica se a senha está correta
        if (password_verify($password, $user['password'])) {
            // Gera um novo token
            $token = generateToken();

            // Armazena as informações do usuário na sessão
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_token'] = $token;
            $_SESSION['user_type'] = $user['type'];

            // Atualiza o token no banco de dados
            $updateTokenStmt = $conn->prepare("UPDATE Users SET token = ? WHERE id_user = ?");
            $updateTokenStmt->bind_param("si", $token, $user['id_user']);
            $updateTokenStmt->execute();

            return "Authentication successful.";
        } else {
            return "Invalid credentials.";
        }
    } else {
        return "No user found.";
    }
}

// Função para gerar o token (você pode implementar a lógica da função)
function generateToken() {
    return bin2hex(random_bytes(16)); // Gera um token seguro
}


// Função para visualizar todos os usuários
function view_all() {
    global $conn;
    $sql = "SELECT * FROM Users";
    $result = $conn->query($sql);
    $users = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    return $users;
}

// Função para visualizar um usuário específico
function view_a($id_user) {
    global $conn;
    $sql = "SELECT * FROM Users WHERE id_user=$id_user";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return "User not found.";
    }
}

// Controlador para determinar qual ação executar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action == 'register') {
            echo register($_POST['name'], $_POST['gender'], $_POST['room'], $_POST['id_course'], $_POST['type'], $_POST['token'], $_POST['password'], $_POST['photo']);
        } elseif ($action == 'edit') {
            echo edit($_POST['id_user'], $_POST['name'], $_POST['gender'], $_POST['room'], $_POST['id_course'], $_POST['type'], $_POST['token'], $_POST['password'], $_POST['photo']);
        } elseif ($action == 'delete') {
            echo delete($_POST['id_user']);
        } elseif ($action == 'authenticate') {
            echo authenticate($_POST['name'], $_POST['password']);
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id_user'])) {
        echo json_encode(view_a($_GET['id_user']));
    } else {
        echo json_encode(view_all());
    }
}

$conn->close();
?>
