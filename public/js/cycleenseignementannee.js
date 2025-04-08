var dataTable = null;
var itemToDelete = null;

$(document).ready(function () {
    // Gestion de la soumission du formulaire d'ajout
    $("#ajoutercycleenseignement-form").on('submit',function (e) {
        e.preventDefault();
        $("#ajoutercycleenseignement-form input[type='submit']").prop("disabled",true);
        if (this.checkValidity()) {
            ajouterCycleenseignement($(this).serialize());
        } else {
            this.reportValidity();
        }
    });

    // Gestion de la soumission du formulaire de modification
    $("#modifiercycleenseignement-form").on('submit',function (e) {
        e.preventDefault();
        $("#modifiercycleenseignement-form input[type='submit']").prop("disabled",true);
        if (this.checkValidity()) {
            saveChanges($(this).serialize());
        } else {
            this.reportValidity();
        }
    });

    // Filtrage des données par année
    $('#yearFilter').change(function () {
        let selectedYear = $(this).val();
        if (selectedYear !== "") {
            loadData(selectedYear);
        } else {
            updateTable([]);
        }
    });

    // Fermeture des modales d'ajout et de modification
    $("#modifiercycleenseignement .close").on('click',() => $('#modifiercycleenseignement').modal('hide'));
    $("#ajoutercycleenseignement .close").on('click',() => $('#ajoutercycleenseignement').modal('hide'));

    // Affichage de la modal d'ajout d'un nouveau cycle d'enseignement
    $("#modalAddNewCycleBtn").on('click',() => $('#ajoutercycleenseignement').modal('show'));

    // Chargement initial des données
    loadData($('#yearFilter').val());

    // Confirmation de la suppression
    $("#confirmDeleteButton").click((e) => {
        $("#confirmDeleteButton").prop("disabled",true);
        deleteCycleenseignementannee(itemToDelete);
    });
});

// Fonction pour ajouter un cycle d'enseignement
function ajouterCycleenseignement(formData)
{
    $.ajax({
        url: 'index.php?page=cycleenseignementannee',
        method: 'POST',
        data: {
            action:"ajoutercycleenseignementannee",
            formData:formData
        },
        success: function (res) {
            $('#ajoutercycleenseignement').modal('hide')
            res = JSON.parse(res);
            showInfoModal(res.message,res.status)
            if (res.status === "success") {
                setTimeout(() => {
                    window.location.reload();
                },1500)
            }
        },
        error: function (xhr, status, error) {
            console.error("An error occurred: " + error);
        }
    });
}

// Fonction pour éditer un cycle d'enseignement
function editCycleenseignementannee(button)
{
    const formModal = $('#modifiercycleenseignement');
    const data = JSON.parse(button.getAttribute('data-ce'));

    formModal.find("#idanneescolaire").val(data.idanneescolaire);
    formModal.find("#idcycleenseignement").val(data.idcycleenseignement);
    formModal.find('#idcycleenseignementannee').val(data.idcycleenseignementannee);
    formModal.find('#libelle').val(data.libelle);
    formModal.find('#libellecourt').val(data.libellecourt);
    formModal.find('#code').val(data.code);

    formModal.modal('show');
}

// Fonction pour sauvegarder les modifications
function saveChanges(formData)
{
    $.ajax({
        url: 'index.php?page=cycleenseignementannee',
        method: 'POST',
        data: {
            action:"modifercycleenseignementannee",
            formData:formData
        },
        success: function (res) {
            $('#modifiercycleenseignement').modal('hide');
            res = JSON.parse(res);
            showInfoModal(res.message,res.status)
            if (res.status === "success") {
                setTimeout(() => {
                    window.location.reload();
                },1500)
            }
        },
        error: function (xhr, status, error) {
            console.error("An error occurred: " + error);
        }
    });
}

// Fonction pour supprimer un cycle d'enseignement
function deleteCycleenseignementannee(id)
{
    $.ajax({
        url: 'index.php?page=cycleenseignementannee',
        method: 'POST',
        data: {
            action:"supprimercycleenseignementannee",
            id:id
        },
        success: function (res) {
            $('#deleteModal').modal('hide');
            res = JSON.parse(res);
            showInfoModal(res.message,res.status)
            setTimeout(() => {
                window.location.reload();
            },1500)
        },
        error: function (xhr, status, error) {
            console.error("An error occurred: " + error);
        }
    });
}

// Fonction pour charger les données filtrées par année
function loadData(selectedYear)
{
    $.ajax({
        url: 'index.php?page=cycleenseignementannee',
        method: 'POST',
        data: {
            action: 'filter',
            year: selectedYear,
        },
        dataType: 'json',
        success: function (response) {
            updateTable(response);
        }
    });
}

// Fonction pour mettre à jour le tableau avec les données
function updateTable(data)
{
    if (dataTable) {
        dataTable.destroy();
        $('#data-container').empty();
    }

    $('#data-container').html(`
        <table id = "data">
            <thead>
                <th> Libellé </th>
                <th> Libellé court </th>
                <th> Code </th>
                <th> Actions </th>
            </thead>
            <tbody id = "data-body"> </tbody>
        </table>
    `)

    var tableBody = $('#data-body');

    $.each(data,(index,ce) => {
        tableBody.append(
            $('<tr>').append(
                $('<td>').text(ce.libelle),
                $('<td>').text(ce.libellecourt),
                $('<td>').text(ce.code),
                $('<td>').append(
                    $('<button>').addClass('btn btn-primary mr-1')
                        .text('Modifier')
                        .attr('data-ce', JSON.stringify(ce))
                        .on('click',function () {
                            editCycleenseignementannee(this);}),
                    $('<button>').addClass('btn btn-danger')
                        .text('Supprimer')
                        .on('click',function () {
                            setItemToDelete(ce.idcycleenseignementannee);})
                )
            )
        )
    });

    if (!allowDelete) {
        $('.btn-danger').not('.except').hide();
    }

    dataTable = new simpleDatatables.DataTable("#data", {
        labels: {
            placeholder: "Rechercher...", // Le placeholder de la barre de recherche
            perPage: "entrées par page", // Label pour les entrées par page
            noRows: "Aucune entrée trouvée", // Message affiché lorsqu'il n'y a aucune entrée
            info: "Affichage de {start} à {end} sur {rows} entrées", // Texte d'information
            noResults: "Aucune entrée correspondante trouvée", // Message affiché lorsqu'il n'y a aucun résultat correspondant
            loading: "Chargement...", // Texte de chargement
            infoFiltered: " (filtré de {rowsTotal} entrées au total)", // Texte d'information filtrée
            first: "Première", // Texte du bouton première
            last: "Dernière", // Texte du bouton dernière
            previous: "Précédente", // Texte du bouton précédente
            next: "Suivante" // Texte du bouton suivante
        }
    });
}

// Fonction pour définir l'élément à supprimer
function setItemToDelete(itemId)
{
    itemToDelete = itemId;
    console.log(itemToDelete)
    $('#deleteModal').modal('show');
}