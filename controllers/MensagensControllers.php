<?php
session_start();

include '../config/db_config.php';
// Check if user is logged in and if user_id is 1
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    header('Location: login.php'); // Redirect to login page if not logged in or user_id is not 1
    exit();
}


// Excluir mensagem se solicitado
if (isset($_POST['delete_message'])) {
    $id_contact = intval($_POST['id_contact']);
    $sql = "DELETE FROM contacts WHERE id_contact = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_contact);
    
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Mensagem excluída com sucesso.</div>";
    } else {
        echo "<div class='alert alert-danger'>Erro ao excluir mensagem: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

// Enviar resposta se solicitado
if (isset($_POST['reply_message'])) {
    $replyEmail = $_POST['replyEmail'];
    $replyMessage = $_POST['replyMessage'];

    // Aqui você pode configurar o envio de e-mail. Exemplo:
    $to = $replyEmail;
    $subject = "Codigo do Colegio Imeg";
    $headers = "From: no-reply@example.com";

    if (mail($to, $subject, $replyMessage, $headers)) {
        echo "<div class='alert alert-success'>Resposta enviada com sucesso.</div>";
    } else {
        echo "<div class='alert alert-danger'>Erro ao enviar resposta.</div>";
    }
}

// Buscar mensagens
$sql = "SELECT * FROM contacts";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="message-item">';
        echo '<div class="message-author">' . htmlspecialchars($row["email"]) . '</div>';
        echo '<div class="message-text">' . htmlspecialchars($row["message"]) . '</div>';
        echo '<div class="message-actions">';
        echo '<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#replyModal" data-email="' . htmlspecialchars($row["email"]) . '">Responder</button>';
        echo '<form method="POST" style="display:inline;">
                <input type="hidden" name="id_contact" value="' . $row["id_contact"] . '">
                <button type="submit" name="delete_message" class="btn btn-danger">Eliminar</button>
              </form>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo "Nenhuma mensagem encontrada.";
}

$conn->close();


?>