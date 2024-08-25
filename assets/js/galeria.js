function setEditPhotoDetails(src, description) {
    document.getElementById('photoSrc').value = src;
    document.getElementById('photoDescription').value = description;
}

function deletePhoto(src) {
    if (confirm('Você tem certeza de que deseja eliminar esta foto?')) {
        // Lógica para eliminar a foto
        console.log('Eliminar foto:', src);
    }
}

document.getElementById('editPhotoForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const src = document.getElementById('photoSrc').value;
    const description = document.getElementById('photoDescription').value;
    // Lógica para salvar as alterações
    console.log('Salvar alterações para a foto:', src, 'Descrição:', description);
    // Fechar o modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('editPhotoModal'));
    modal.hide();
});