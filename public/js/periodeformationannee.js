var dataTable = null;
var itemToDelete = null;

$(document).ready(function () {
    initializeFilters();

    // Gestion de la soumission du formulaire d'ajout
    $("#ajouterPFA-form").on('submit',function (e) {
        e.preventDefault();
        $("#ajouterPFA-form input[type='submit']").prop("disabled",true);
        if (this.checkValidity()) {
            ajouterPFA($(this).serialize());
        } else {
            this.reportValidity();
        }
    });

    // Gestion de la soumission du formulaire de modification
    $("#modifierPFA-form").on('submit',function (e) {
        e.preventDefault();
        $("#modifierPFA-form input[type='submit']").prop("disabled",true);
        if (this.checkValidity()) {
            saveChangesPFA($(this).serialize());
        } else {
            this.reportValidity();
        }
    });

    // Filtrage des données par année
    $('#yearFilter').change(function () {
        let selectedYear = $(this).val();
        initializeFilters(selectedYear);
    });

    // Filtrage des données par cycle
    $('#cycleFilter').change(function () {
        let selectedCycle = $(this).val();
        loadData(selectedCycle);
    });

    // Mise à jour des options de filtre en fonction de l'année sélectionnée
    $('#idanneescolaire').change(async function () {
        let selectedYear = $(this).val();
        if (selectedYear !== "") {
            await loadFilterOptions({ action: 'getCycleFilterOptions', year: selectedYear }, 'idcycleenseignementannee');
        } else {
            updateFilter('idcycleenseignementannee',[]);
        }
    });

    // Fermeture des modales d'ajout et de modification
    $("#modifierPFA .close").on('click',() => $('#modifierPFA').modal('hide'));
    $("#ajouterPFA .close").on('click',() => $('#ajouterPFA').modal('hide'));

    // Affichage de la modal d'ajout d'une nouvelle période de formation pour l'année scolaire
    $("#openModalAjouterPFABtn").on('click',() => $('#ajouterPFA').modal('show'));

    // Confirmation de la suppression
    $("#confirmDeleteButton").click((e) => {
        $("#confirmDeleteButton").prop("disabled",true);
        deletePFAnnee(itemToDelete);
    });
});

// Fonction pour ajouter une période de formation pour l'année scolaire
function ajouterPFA(formData)
{
    $.ajax({
        url: 'index.php?page=periodedeformationannee',
        method: 'POST',
        data: {
            action:"ajouterperiodedeformationannee",
            formData:formData
        },
        dataType: 'json',
        success: function (res) {
            $('#ajouterPFA').modal('hide')
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

// Fonction pour éditer une période de formation pour l'année scolaire
function editPFannee(button)
{
    const formModal = $('#modifierPFA');
    const data = JSON.parse(button.getAttribute('data-pa'));

    formModal.find("#idperiodeformationannee").val(data.idperiodeformationannee);
    formModal.find("#idperiodeformation").val(data.idperiodeformation);
    formModal.find('#libelle').val(data.libelle);
    formModal.find('#libellecourt').val(data.libellecourt);
    formModal.find('#code').val(data.code);

    formModal.modal('show');
}

// Fonction pour sauvegarder les modifications d'une période de formation pour l'année scolaire
function saveChangesPFA(formData)
{
    $.ajax({
        url: 'index.php?page=periodedeformationannee',
        method: 'POST',
        data: {
            action:"modifierperiodedeformationannee",
            formData:formData
        },
        dataType: 'json',
        success: function (res) {
            $('#modifierPFA').modal('hide')
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

// Fonction pour charger les données filtrées par cycle
function loadData(selectedCycle)
{
    $.ajax({
        url: 'index.php?page=periodedeformationannee',
        method: 'POST',
        data: {
            action: 'filter',
            cycle: selectedCycle,
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
        $('#dataPA-container').empty();
    }

    $('#dataPA-container').html(`
        <table id = "dataPA">
            <thead>
                <th> Libellé </th>
                <th> Libellé court </th>
                <th> Code </th>
                <th> Actions </th>
            </thead>
            <tbody id = "dataPA-body"> </tbody>
        </table>
    `)

    var tableBody = $('#dataPA-body');

    $.each(data,(index,pa) => {
        tableBody.append(
            $('<tr>').append(
                $('<td>').text(pa.libelle),
                $('<td>').text(pa.libellecourt),
                $('<td>').text(pa.code),
                $('<td>').append(
                    $('<button>').addClass('btn btn-primary mr-1')
                        .text('Modifier')
                        .attr('data-pa', JSON.stringify(pa))
                        .on('click',function () {
                            editPFannee(this);}),
                    $('<button>').addClass('btn btn-danger')
                        .text('Supprimer')
                        .on('click',function () {
                            setItemToDelete(pa.idperiodeformationannee);})
                )
            )
        )
    });

    if (!allowDelete) {
        $('.btn-danger').not('.except').hide();
    }

    dataTable = new simpleDatatables.DataTable("#dataPA", {
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

// Fonction pour charger les options de filtre
function loadFilterOptions(data, targetFilter)
{
    return new Promise((resolve, reject) => {
        $.ajax({
            url: 'index.php?page=periodedeformationannee',
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function (options) {
                updateFilter(targetFilter, options);
                resolve(options);
            },
            error: function (xhr, status, error) {
                console.error("An error occurred: " + error);
                reject(error);
            }
        });
    });
}

// Fonction pour initialiser les filtres
async function initializeFilters(selectedYear=$('#yearFilter').val())
{
    try {
        await loadFilterOptions({ action: 'getCycleFilterOptions', year: selectedYear }, 'cycleFilter');
        const selectedCycle = $('#cycleFilter').val();
        if (selectedCycle) {
            loadData(selectedCycle);
        }
    } catch (error) {
        console.error("Error loading filter options:", error);
    }
}

// Fonction pour mettre à jour les filtres
function updateFilter(filterId,options)
{
    var filter = $('#' + filterId);
    filter.empty();
    $.each(options, function (index, option) {
        filter.append($('<option></option>').attr('value', option['id']).text(option['libelle']));
    });
}

// Fonction pour supprimer une période de formation pour l'année scolaire
function deletePFAnnee(id)
{
    $.ajax({
        url: 'index.php?page=periodedeformationannee',
        method: 'POST',
        data: {
            action:"supprimerperiodedeformationannee",
            id:id
        },
        dataType: 'json',
        success: function (res) {
            $('#deleteModal').modal('hide');
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

// Fonction pour définir l'élément à supprimer
function setItemToDelete(itemId)
{
    itemToDelete = itemId;
    console.log(itemToDelete)
    $('#deleteModal').modal('show');
}