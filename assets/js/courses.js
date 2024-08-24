  // Função para filtrar a tabela
  function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toUpperCase();
    const table = document.querySelector('table');
    const tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) { // Skip header row
        const td = tr[i].getElementsByTagName('td')[1];
        if (td) {
            const txtValue = td.textContent || td.innerText;
            tr[i].style.display = txtValue.toUpperCase().indexOf(filter) > -1 ? '' : 'none';
        }
    }
}

// Função para abrir o modal de edição
function openEditModal(id, name) {
    document.getElementById('editCursoId').value = id;
    document.getElementById('editInputCursoNome').value = name;
    new bootstrap.Modal(document.getElementById('editarModal')).show();
}

// Função para abrir o modal de exclusão
function openDeleteModal(id) {
    document.getElementById('confirmDeleteButton').onclick = function() {
        // Logic to delete the course with the given ID
        console.log('Course with ID ' + id + ' deleted.');
        // Optionally, you can use AJAX to make an API call to delete the course
        // Close the modal after deletion
        new bootstrap.Modal(document.getElementById('confirmDeleteModal')).hide();
    };
    new bootstrap.Modal(document.getElementById('confirmDeleteModal')).show();
}