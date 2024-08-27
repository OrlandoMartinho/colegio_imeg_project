<?php
// Função para fazer o upload da foto
function uploadPhoto($photo) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($photo["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Verificar se o arquivo é uma imagem
    $check = getimagesize($photo["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "O arquivo não é uma imagem.";
        $uploadOk = 0;
    }

    // Verificar se o arquivo já existe
    if (file_exists($target_file)) {
        echo "O arquivo já existe.";
        $uploadOk = 0;
    }

    // Verificar o tamanho do arquivo
    if ($photo["size"] > 500000) {
        echo "O arquivo é muito grande.";
        $uploadOk = 0;
    }

    // Verificar o formato do arquivo
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Somente arquivos JPG, JPEG, PNG e GIF são permitidos.";
        $uploadOk = 0;
    }

    // Verificar se $uploadOk está definido como 0 por um erro
    if ($uploadOk == 0) {
        echo "O arquivo não foi enviado.";
    // Se tudo estiver certo, tentar fazer o upload do arquivo
    } else {
        if (move_uploaded_file($photo["tmp_name"], $target_file)) {
            return $target_file;
        } else {
            echo "Houve um erro ao enviar o arquivo.";
        }
    }
}
?>
