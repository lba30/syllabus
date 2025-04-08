var dataTable = null;
var itemToDelete = null;
$(document).ready(function () {


    loadData(null);

    $('#roleFilter').change(function () {
        let selectedRole = $(this).val();
        loadData(selectedRole);
    });


    $("#editresponsable-form").on('submit', function (e) {
        e.preventDefault();
        $("#editresponsable-form input[type='submit']").prop("disabled", true);
        if (this.checkValidity()) {
            saveChanges($(this).serialize());
        } else {
            this.reportValidity();
        }
    });

    $("#editModal .close").on('click', () => $('#editModal').modal('hide'));

    $("#confirmDeleteButton").click((e) => {
        $("#confirmDeleteButton").prop("disabled", true);
        supprimerResponsable(itemToDelete);
    });
});



function supprimerResponsable(id) {
    $.ajax({
        url: 'index.php?page=responsable',
        method: 'POST',
        data: {
            action: "supprimerresponsable",
            id: id
        },
        success: function (res) {
            $('#deleteModal').modal('hide');
            res = JSON.parse(res);
            showInfoModal(res.message, res.status)
            setTimeout(() => {
                window.location.reload();
            }, 1500)

        },
        error: function (xhr, status, error) {
            console.error("An error occurred: " + error);
        }
    });
}


function editResponsable(data) {
    const formModal = $('#editModal')

    formModal.find('#editIdResponsable').val(data.idutilisateur);
    formModal.find('#editNom').val(data.username);
    formModal.find('#editContact').val(data.email);
    formModal.find('#editRole').val(data.idrole);

    formModal.modal('show');
}


function saveChanges(formData) {
    $.ajax({
        url: 'index.php?page=responsable',
        method: 'POST',
        data: {
            action: "modifierresponsable",
            formData: formData
        },
        dataType: 'json',
        success: function (res) {
            $('#editModal').modal('hide');
            res = JSON.parse(res);
            showInfoModal(res.message, res.status)
            if (res.status === "success") {
                setTimeout(() => {
                    window.location.reload();
                }, 1500)
            }

        },
        error: function (xhr, status, error) {
            console.error("An error occurred: " + error);
        }
    });
}


function loadData(selectedRole) {
    selectedRole = selectedRole != "" ? selectedRole : null;
    $.ajax({
        url: 'index.php?page=responsable',
        method: 'POST',
        data: {
            action: 'filter',
            role: selectedRole,
        },
        dataType: 'json',
        success: function (response) {
            updateTable(response);
        }
    });
}


function updateTable(data) {

    if (dataTable) {
        dataTable.destroy();
        $('#datresponsable-container').empty();
    }


    $('#datresponsable-container').html(`
        <table id = "dataresponsable">
            <thead>
                <th> Identifiant </th>
                <th> Email </th>
                <th> Rôle </th>
                <th> Actions </th>
            </thead>
            <tbody id = "dataresponsable-body"> </tbody>
        </table>
        `)

    var tableBody = $('#dataresponsable-body');

    $.each(data, (index, u) => {
        tableBody.append(
            $('<tr>').append(
                $('<td>').text(u.username),
                $('<td>').text(u.email),
                $('<td>').text(u.label),
                $('<td>').append(
                    $('<button>').addClass('btn btn-primary mr-1')
                        .text('Modifier')
                        .attr('data-u', JSON.stringify(u))
                    ,
                    $('<button>').addClass('btn btn-danger')
                        .text('Supprimer')
                        .attr('data-id', u.idutilisateur)
                )
            )
        )
      
    });

    if (!allowDelete) {
        $('.btn-danger').hide();
    }

    $('#datresponsable-container').on('click', '.btn-primary', function () {
        const userData = JSON.parse($(this).attr('data-u'));
        editResponsable(userData);
    });

    $('#datresponsable-container').on('click', '.btn-danger', function () {
        const userId = $(this).attr('data-id');
        setItemToDelete(userId);
    });

    dataTable = new simpleDatatables.DataTable("#dataresponsable", {
        labels: {
            placeholder: "Rechercher...", // The search input placeholder
            perPage: "entrées par page", // Entries per page select label
            noRows: "Aucune entrée trouvée", // Message shown when there are no entries
            info: "Affichage de {start} à {end} sur {rows} entrées", // Info text
            noResults: "Aucune entrée correspondante trouvée", // Message shown when there are no matching search results
            loading: "Chargement...", // Loading text
            infoFiltered: " (filtré de {rowsTotal} entrées au total)", // Info filtered text
            first: "Première", // First button text
            last: "Dernière", // Last button text
            previous: "Précédente", // Previous button text
            next: "Suivante" // Next button text
        }
    });
}

function setItemToDelete(itemId) {
    itemToDelete = itemId;
    console.log(itemToDelete)
    $('#deleteModal').modal('show');
}