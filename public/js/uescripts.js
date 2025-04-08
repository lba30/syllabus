// Fonction pour modifier une UE
function modifierUE(button)
{
    let ueId = button.getAttribute('data-id');
    $.ajax({
        url: 'index.php',
        method: 'GET',
        data: {
            page: 'modifierue',
            id: ueId,
        }
    });
}

// Fonction pour imprimer le syllabus d'une UE
function imprimerSyllabus(button)
{
    let ueId = button.getAttribute('data-id');
    let showInfoRespo = $("#infopersoFilter").is(':checked');

    // Création d'un formulaire pour soumettre les données
    let form = document.createElement('form');
    form.method = 'GET';
    form.action = 'index.php';
    form.target = '_blank';

    // Ajout des champs cachés au formulaire
    let pageInput = document.createElement('input');
    pageInput.type = 'hidden';
    pageInput.name = 'page';
    pageInput.value = 'ue';

    let actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = 'imprimer';

    let idInput = document.createElement('input');
    idInput.type = 'hidden';
    idInput.name = 'id';
    idInput.value = ueId;

    let RespoInput = document.createElement('input');
    RespoInput.type = 'hidden';
    RespoInput.name = 'respoInfo';
    RespoInput.value = showInfoRespo;

    // Ajout des champs au formulaire
    form.appendChild(pageInput);
    form.appendChild(actionInput);
    form.appendChild(idInput);
    form.appendChild(RespoInput);

    // Soumission du formulaire
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

// Variables globales
var dataTable = null;
var itemToDelete = null;

$(document).ready(function () {
    $('#yearFilter').val("");

    // Gestionnaire d'événement pour le filtre par année
    $('#yearFilter').change(function () {
        let selectedYear = $(this).val();
        if (selectedYear !== "") {
            loadFilterOptions({ action: 'getCycleFilterOptions', year: selectedYear }, 'cycleFilter');
            loadUEData(selectedYear, '', '', '');
        } else {
            $('#cycleFilter').prop('disabled', true).empty().append('<option value="">Tous</option>');
            $('#semestreFilter').prop('disabled', true).empty().append('<option value="">Tous</option>');
            $('#departementFilter').prop('disabled', true).empty().append('<option value="">Sélectionnez une département</option>');
            updateTable([]);
        }
    });

    // Gestionnaire d'événement pour le filtre par cycle
    $('#cycleFilter').change(function () {
        var selectedYear = $('#yearFilter').val();
        var selectedCycle = $(this).val();

        if (selectedYear && selectedCycle) {
            loadFilterOptions({ action: 'getDepartementOptions', year: selectedYear, cycle: selectedCycle }, 'departementFilter');
            loadFilterOptions({ action: 'getSemestreOptions', year: selectedYear, cycle: selectedCycle }, 'semestreFilter');
            loadUEData(selectedYear, selectedCycle, '', '');
        } else {
            $('#semestreFilter').prop('disabled', true).empty().append('<option value="">Tous</option>');
            $('#departementFilter').prop('disabled', true).empty().append('<option value="">Sélectionnez une département</option>');
            loadUEData(selectedYear, '', '', '');
        }
    });

    // Gestionnaire d'événement pour le filtre par département
    $('#departementFilter').change(function () {
        var selectedYear = $('#yearFilter').val();
        var selectedCycle = $('#cycleFilter').val();
        var selectedDepartement = $(this).val();
        if (selectedYear && selectedCycle) {
            loadFilterOptions({ action: 'getSemestreOptions', year: selectedYear, cycle: selectedCycle }, 'semestreFilter');
            loadUEData(selectedYear, selectedCycle, selectedDepartement, '');
        } else {
            loadUEData(selectedYear, selectedCycle, '', '');
        }
    });

    // Gestionnaire d'événement pour le filtre par semestre
    $('#semestreFilter').change(function () {
        var selectedYear = $('#yearFilter').val();
        var selectedCycle = $('#cycleFilter').val();
        var selectedDepartement = $('#departementFilter').val();
        var selectedSemestre = $(this).val();
        if (selectedYear && selectedCycle && selectedSemestre) {
            loadUEData(selectedYear, selectedCycle, selectedDepartement, selectedSemestre);
        } else {
            loadUEData(selectedYear, selectedCycle, selectedDepartement, '');
        }
    });

    // Initialisation de la table avec des données vides
    updateTable([]);

    // Gestionnaire d'événement pour le bouton de confirmation de suppression
    $("#confirmDeleteButton").click((e) => {
        $("#confirmDeleteButton").prop("disabled",true);
        supprimerUE(itemToDelete);
    });
});

// Fonction pour supprimer une UE
function supprimerUE(ueId)
{
    $.ajax({
        url: 'index.php?page=ue',
        method: 'POST',
        data: {
            action: 'supprimerue',
            id: ueId
        },
        dataType: 'json',
        success: function (response) {
            response = JSON.parse(response);
            $('#deleteModal').modal('hide');
            if (response.status === 'success') {
                $("#yearFilter").val("");
                $('#yearFilter').trigger('change');
                showInfoModal("UE supprimée avec succès", "success");
            } else {
                showInfoModal("Une erreur s'est produite. UE n'a pas pu être supprimé.", "error");
            }
        }
    });
}

// Fonction pour charger les données des UE
function loadUEData(selectedYear, selectedCycle, selectedDepartement, selectedSemestre)
{
    $.ajax({
        url: 'index.php?page=ue',
        method: 'POST',
        data: {
            action: 'filter',
            year: selectedYear,
            cycle: selectedCycle,
            departement: selectedDepartement,
            semestre: selectedSemestre
        },
        dataType: 'json',
        success: function (response) {
            updateTable(response);
        }
    });
}

// Fonction pour charger les options de filtre
function loadFilterOptions(data, targetFilter)
{
    $.ajax({
        url: 'index.php?page=ue',
        method: 'POST',
        data: data,
        dataType: 'json',
        success: function (options) {
            updateFilter(targetFilter, options);
        },
        error: function (xhr, status, error) {
            console.error("An error occurred: " + error);
        }
    });
}

// Fonction pour mettre à jour les options de filtre
function updateFilter(filterId, options)
{
    var filter = $('#' + filterId);

    filter.empty().append('<option value="">Tous</option>');

    $.each(options, function (index, option) {
        filter.append($('<option></option>').attr('value', option['id']).text(option['libelle']));
    });
    filter.prop('disabled', false);
}

// Fonction pour mettre à jour la table avec les données des UE
function updateTable(data)
{
    if (dataTable) {
        dataTable.destroy();
        $('#dataue-container').empty();
    }

    $('#dataue-container').html(`
        <table id = "dataue">
            <thead>
                <th> Libellé </th>
                <th> Code </th>
                <th> Actions </th>
            </thead>
            <tbody id = "dataue-body" > </tbody>
        </table>
    `);

    var tableBody = $('#dataue-body');
    $.each(data, (index, ue) => {
        let url = 'index.php?page=modifierue&id=' + ue['idmoduleannee'];
        tableBody.append(
            '<tr>' +
            '<td>' + ue['libelle'] + '</td>' +
            '<td>' + ue['code'] + '</td>' +
            '<td>' +
            '<button class="btn btn-primary" data-id="' + ue['idmoduleannee'] + '" onclick="imprimerSyllabus(this)">Aperçu</button>' +
            (role == "administrateur" || role == "responsable" ? '<a class="btn btn-secondary ml-1 ' + ((ue['hasaccess'] === false) ? "disabled" : "" ) + '" href="' + ((ue['hasaccess'] === false) ? "#" : url ) + '">Modifier</a>' : "") +
            (role == "administrateur" ? '<button class="btn btn-danger ml-1" onclick="setItemToDelete(' + ue['idmoduleannee'] + ')">Supprimer</button>' : '') +
            '</td>' +
            '</tr>'
        );
    });
    if (!allowDelete) {
        $('.btn-danger').hide();
    }
    dataTable = new simpleDatatables.DataTable("#dataue", {
        labels: {
            placeholder: "Rechercher...", // Le placeholder de la barre de recherche
            perPage: "entrées par page", // Label pour les entrées par page
            noRows: "Aucune entrée trouvée", // Message affiché lorsqu'il n'y a aucune entrée
            info: "Affichage de {start} à {end} sur {rows} entrées", // Texte d'information
            noResults: "Aucune entrée correspondante trouvée", // Message affiché lorsqu'il n'y a aucun résultat correspondant
            loading: "Chargement...", // Texte de chargement
            infoFiltered: " (filtré de {rowsTotal} entrées au total)", // Texte d'information filtrée
            first: "Première", // Texte du bouton "Première"
            last: "Dernière", // Texte du bouton "Dernière"
            previous: "Précédente", // Texte du bouton "Précédente"
            next: "Suivante" // Texte du bouton "Suivante"
        }
    });
}

// Fonction pour définir l'élément à supprimer
function setItemToDelete(itemId)
{
    itemToDelete = itemId;
    console.log(itemToDelete);
    $('#deleteModal').modal('show');
}