function filterTable() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    const table = document.querySelector('.table tbody');
    const rows = table.querySelectorAll('tr');
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        let showRow = false;
        cells.forEach(cell => {
            if (cell.textContent.toLowerCase().includes(input)) {
                showRow = true;
            }
        });
        row.style.display = showRow ? '' : 'none';
    });
}

function editStudent(id, nome, sala, curso, classe, turno, genero, email) {
    document.getElementById('editId').value = id;
    document.getElementById('editNome').value = nome;
    document.getElementById('editSala').value = sala;
    document.getElementById('editCurso').value = curso;
    document.getElementById('editClasse').value = classe;
    document.getElementById('editTurno').value = turno;
    document.getElementById('editGenero').value = genero;
    document.getElementById('editEmail').value = email;
}

function confirmDelete(id) {
    const confirmDeleteButton = document.getElementById('confirmDeleteButton');
    confirmDeleteButton.onclick = function () {
        // Adicione a lógica para excluir o aluno com o ID fornecido
        console.log(`Aluno com ID ${id} excluído`);
        $('#confirmDeleteModal').modal('hide');
    };
}
