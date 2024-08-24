
        // Função para filtrar a tabela
        function filterTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toUpperCase();
            const table = document.querySelector('table');
            const tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {  // Start from 1 to skip header row
                const td = tr[i].getElementsByTagName('td')[1];
                if (td) {
                    const txtValue = td.textContent || td.innerText;
                    tr[i].style.display = txtValue.toUpperCase().indexOf(filter) > -1 ? '' : 'none';
                }
            }
        }

        // Função para abrir o modal de edição
        function openEditModal(id, name, course) {
            document.getElementById('editDisciplinaId').value = id;
            document.getElementById('editInputDisciplinaNome').value = name;
            document.getElementById('editInputCurso').value = course;
            new bootstrap.Modal(document.getElementById('editarModal')).show();
        }

        // Função para abrir o modal de exclusão
        function openDeleteModal(id) {
            document.getElementById('confirmDeleteButton').onclick = function() {
                // Lógica para excluir a disciplina
                console.log(`Disciplina com ID ${id} excluída.`);
                new bootstrap.Modal(document.getElementById('confirmDeleteModal')).hide();
            };
            new bootstrap.Modal(document.getElementById('confirmDeleteModal')).show();
        }