function filterTable() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.querySelector("table");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[4]; // Aluno é a quinta coluna (index 4)
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

function cadastrarNota() {
    var curso = document.getElementById('selectCurso').options[document.getElementById('selectCurso').selectedIndex].text;
    var classe = document.getElementById('selectClasse').options[document.getElementById('selectClasse').selectedIndex].text;
    var turno = document.getElementById('selectTurno').options[document.getElementById('selectTurno').selectedIndex].text;
    var aluno = document.getElementById('selectAluno').options[document.getElementById('selectAluno').selectedIndex].text;
    var disciplina = document.getElementById('selectDisciplina').options[document.getElementById('selectDisciplina').selectedIndex].text;
    var nota = document.getElementById('inputNota').value;

    var table = document.getElementById('gradesTable').getElementsByTagName('tbody')[0];
    var newRow = table.insertRow();

    newRow.insertCell(0).innerText = table.rows.length + 1;
    newRow.insertCell(1).innerText = curso;
    newRow.insertCell(2).innerText = classe;
    newRow.insertCell(3).innerText = turno;
    newRow.insertCell(4).innerText = aluno;
    newRow.insertCell(5).innerText = disciplina;
    newRow.insertCell(6).innerText = nota;

    var actionsCell = newRow.insertCell(7);
    actionsCell.innerHTML = `
        <button class="btn btn-warning btn-sm" onclick="editGrade(this)">Editar</button>
        <button class="btn btn-danger btn-sm" onclick="deleteGrade(this)">Excluir</button>
    `;

    document.getElementById('formCadastroNota').reset();
    var modal = bootstrap.Modal.getInstance(document.getElementById('cadastroModal'));
    modal.hide();
}

function editGrade(button) {
    var row = button.closest('tr');
    var cells = row.getElementsByTagName('td');

    document.getElementById('selectCurso').value = getValueIndex(document.getElementById('selectCurso'), cells[1].innerText);
    document.getElementById('selectClasse').value = getValueIndex(document.getElementById('selectClasse'), cells[2].innerText);
    document.getElementById('selectTurno').value = getValueIndex(document.getElementById('selectTurno'), cells[3].innerText);
    document.getElementById('selectAluno').value = getValueIndex(document.getElementById('selectAluno'), cells[4].innerText);
    document.getElementById('selectDisciplina').value = getValueIndex(document.getElementById('selectDisciplina'), cells[5].innerText);
    document.getElementById('inputNota').value = cells[6].innerText;

    var modal = new bootstrap.Modal(document.getElementById('cadastroModal'));
    modal.show();
    document.querySelector('#cadastroModal .btn-primary').onclick = function() {
        updateGrade(row);
    };
}

function updateGrade(row) {
    var curso = document.getElementById('selectCurso').options[document.getElementById('selectCurso').selectedIndex].text;
    var classe = document.getElementById('selectClasse').options[document.getElementById('selectClasse').selectedIndex].text;
    var turno = document.getElementById('selectTurno').options[document.getElementById('selectTurno').selectedIndex].text;
    var aluno = document.getElementById('selectAluno').options[document.getElementById('selectAluno').selectedIndex].text;
    var disciplina = document.getElementById('selectDisciplina').options[document.getElementById('selectDisciplina').selectedIndex].text;
    var nota = document.getElementById('inputNota').value;

    row.cells[1].innerText = curso;
    row.cells[2].innerText = classe;
    row.cells[3].innerText = turno;
    row.cells[4].innerText = aluno;
    row.cells[5].innerText = disciplina;
    row.cells[6].innerText = nota;

    var modal = bootstrap.Modal.getInstance(document.getElementById('cadastroModal'));
    modal.hide();
}

function deleteGrade(button) {
    if (confirm("Tem certeza que deseja excluir esta nota?")) {
        var row = button.closest('tr');
        row.remove();
    }
}

function getValueIndex(selectElement, text) {
    for (var i = 0; i < selectElement.options.length; i++) {
        if (selectElement.options[i].text === text) {
            return selectElement.options[i].value;
        }
    }
    return null;
}

function confirmDeleteGrade(element) {
    // Save reference to the row or grade data
    var gradeRow = element.closest('tr');
    // Set the action to delete the specific grade
    document.querySelector('#confirmDeleteModal .btn-danger').onclick = function () {
        deleteGrade(gradeRow);
    };
    // Show the confirmation modal
    new bootstrap.Modal(document.getElementById('confirmDeleteModal')).show();
}

function deleteGrade(gradeRow) {
    // Implement deletion logic here
    // Example: remove the row from the table
    gradeRow.remove();
    // Optionally, perform an API request to delete the grade from the server
}
