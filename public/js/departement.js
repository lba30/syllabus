var dataTable = null;
var itemToDelete = null;

$(document).ready(function () {
    // Initialisation des tableaux de données avec des labels en français
    new simpleDatatables.DataTable("#datadepartement", {
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
    updateTable([]);

    // Gestion de la soumission du formulaire d'ajout de département
    $("#ajouterdepartement-form").on('submit', function (e) {
        e.preventDefault();
        $("#ajouterdepartement-form input[type='submit']").prop("disabled", true);
        if (this.checkValidity()) {
            ajouterDepartement($(this).serialize());
        } else {
            this.reportValidity();
        }
    });

    // Gestion de la soumission du formulaire de modification de département
    $("#modifierdepartement-form").on('submit', function (e) {
        e.preventDefault();
        $("#modifierdepartement-form input[type='submit']").prop("disabled", true);
        if (this.checkValidity()) {
            saveChangesDepartement($(this).serialize());
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

    // Gestion de la soumission du formulaire d'ajout de département pour une année scolaire
    $("#ajouterdepartementannee-form").on('submit', function (e) {
        e.preventDefault();
        $("#ajouterdepartementannee-form input[type='submit']").prop("disabled", true);
        if (this.checkValidity()) {
            ajouterDepartementAnnee($(this).serialize());
        } else {
            this.reportValidity();
        }
    });

    // Gestion de la soumission du formulaire de modification de département pour une année scolaire
    $("#modifierdepartementannee-form").on('submit', function (e) {
        e.preventDefault();
        $("#modifierdepartementannee-form input[type='submit']").prop("disabled", true);
        if (this.checkValidity()) {
            saveChangesDepartementAnnee($(this).serialize());
        } else {
            this.reportValidity();
        }
    });

    // Fermeture des modales d'ajout et de modification
    $("#ajouterdepartement .close").on('click', () => $('#ajouterdepartement').modal('hide'));
    $("#modifierdepartement .close").on('click', () => $('#modifierdepartement').modal('hide'));
    $("#ajouterdepartementannee .close").on('click', () => $('#ajouterdepartementannee').modal('hide'));
    $("#modifierdepartementannee .close").on('click', () => $('#modifierdepartementannee').modal('hide'));

    // Affichage des modales d'ajout
    $("#openModalAjouterdepartementBtn").on('click', () => $('#ajouterdepartement').modal('show'));
    $("#openModalAjouterdepartementAnneeBtn").on('click', () => $('#ajouterdepartementannee').modal('show'));

    // Confirmation de la suppression
    $("#confirmDeleteButton").click((e) => {
        $("#confirmDeleteButton").prop("disabled", true);
        supprimerDepartementAnnee(itemToDelete);
    });
});

// Fonction pour ajouter un département
function ajouterDepartement(formData) {
    $.ajax({
        url: 'index.php?page=departement',
        method: 'POST',
        data: {
            action: "ajouterdepartement",
            formData: formData
        },
        dataType: 'json',
        success: function (res) {
            $('#ajouterdepartement').modal('hide')
            showInfoModal(res.message, res.status)
            if (res.status === "success") {
                setTimeout(() => {
                    window.location.reload();
                }, 1000)
            }
        },
        error: function (xhr, status, error) {
            console.error("An error occurred: " + error);
        }
    });
}

// Fonction pour ajouter un département pour une année scolaire
function ajouterDepartementAnnee(formData) {
    $.ajax({
        url: 'index.php?page=departement',
        method: 'POST',
        data: {
            action: "ajouterdepartementannee",
            formData: formData
        },
        dataType: 'json',
        success: function (res) {
            $('#ajouterdepartementannee').modal('hide')
            showInfoModal(res.message, res.status)
            if (res.status === "success") {
                setTimeout(() => {
                    window.location.reload();
                }, 1000)
            }
        },
        error: function (xhr, status, error) {
            console.error("An error occurred: " + error);
        }
    });
}

// Fonction pour éditer un département
function editDepartement(data) {
    formModal = $("#modifierdepartement");

    formModal.find("#iddepartement").val(data.iddepartement);
    formModal.find("#libelle").val(data.libelle);

    const actifCheckbox = formModal.find('#actif');
    actifCheckbox.prop('checked', data.actif);

    if (data.actif) {
        actifCheckbox.trigger('change');
    }

    formModal.modal('show');
}

// Fonction pour éditer un département pour une année scolaire
function editDepartementannee(button) {
    const formModal = $('#modifierdepartementannee');
    const data = JSON.parse(button.getAttribute('data-da'));

    formModal.find("#idanneescolaire").val(data.idanneescolaire);
    formModal.find("#iddepartement").val(data.iddepartement);
    formModal.find("#iddepartementannee").val(data.iddepartementannee);
    formModal.find('#libelle').val(data.libelle);
    formModal.find('#code').val(data.code);

    formModal.modal('show');
}

// Fonction pour sauvegarder les modifications d'un département
function saveChangesDepartement(formData) {
    $.ajax({
        url: 'index.php?page=departement',
        method: 'POST',
        data: {
            action: "modifierdepartement",
            formData: formData
        },
        dataType: 'json',
        success: function (res) {
            $('#modifierdepartement').modal('hide')
            showInfoModal(res.message, res.status)
            if (res.status === "success") {
                setTimeout(() => {
                    window.location.reload();
                }, 1000)
            }
        },
        error: function (xhr, status, error) {
            console.error("An error occurred: " + error);
        }
    });
}

// Fonction pour sauvegarder les modifications d'un département pour une année scolaire
function saveChangesDepartementAnnee(formData) {
    $.ajax({
        url: 'index.php?page=departement',
        method: 'POST',
        data: {
            action: "modifierdepartementannee",
            formData: formData
        },
        dataType: 'json',
        success: function (res) {
            $('#modifierdepartementannee').modal('hide')
            showInfoModal(res.message, res.status)
            if (res.status === "success") {
                $('#yearFilter').val("");
                $('#yearFilter').trigger("change");
            }
        },
        error: function (xhr, status, error) {
            console.error("An error occurred: " + error);
        }
    });
}

// Fonction pour charger les données filtrées par année
function loadData(selectedYear) {
    $.ajax({
        url: 'index.php?page=departement',
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
function updateTable(data) {
    if (dataTable) {
        dataTable.destroy();
        $('#dataDA-container').empty();
    }

    $('#dataDA-container').html(`
        <table id = "dataDA">
            <thead>
                <th> Libellé </th>
                <th> Code </th>
                <th> Actions </th>
            </thead>
            <tbody id = "dataDA-body"> </tbody>
        </table>
    `)

    var tableBody = $('#dataDA-body');

    $.each(data, (index, da) => {
        tableBody.append(
            $('<tr>').append(
                $('<td>').text(da.libelle),
                $('<td>').text(da.code),
                $('<td>').append(
                    $('<button>').addClass('btn btn-primary mr-1')
                        .text('Modifier')
                        .attr('data-da', JSON.stringify(da))
                        .on('click', function () {
                            editDepartementannee(this);
                        }),
                    $('<button>').addClass('btn btn-danger')
                        .text('Supprimer')
                        .on('click', function () {
                            setItemToDelete(da.iddepartementannee);
                        })
                )
            )
        )
    });

    if (!allowDelete) {
        $('.btn-danger').not('.except').hide();
    }

    dataTable = new simpleDatatables.DataTable("#dataDA", {
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

// Fonction pour supprimer un département pour une année scolaire
function supprimerDepartementAnnee(id) {
    $.ajax({
        url: 'index.php?page=departement',
        method: 'POST',
        data: {
            action: "supprimerdepartementannee",
            id: id
        },
        dataType: 'json',
        success: function (res) {
            $('#deleteModal').modal('hide');
            res = JSON.parse(res);
            showInfoModal(res.message, res.status)
            $('#yearFilter').val("");
            $('#yearFilter').trigger("change");
        },
        error: function (xhr, status, error) {
            console.error("An error occurred: " + error);
        }
    });
}

// Fonction pour définir l'élément à supprimer
function setItemToDelete(itemId) {
    itemToDelete = itemId;
    console.log(itemToDelete)
    $('#deleteModal').modal('show');
}