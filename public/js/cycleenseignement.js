$(document).ready(function () {
    dataTable = new simpleDatatables.DataTable("#datacycleenseignement", {
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


    // Gestion de la soumission du formulaire d'ajout d'un cycle d'enseignement
    $("#ajoutercycleenseignement-form").on('submit',function (e) {
        e.preventDefault();
        $("#ajoutercycleenseignement-form input[type='submit']").prop("disabled",true);

        if (this.checkValidity()) {
            ajouterCycleenseignement($(this).serialize());
        } else {
            this.reportValidity();
        }
    });

    // Gestion de la soumission du formulaire de modification d'un cycle d'enseignement
    $("#modifiercycleenseignement-form").on('submit',function (e) {
        e.preventDefault();
        $("#modifiercycleenseignement-form input[type='submit']").prop("disabled",true);

        if (this.checkValidity()) {
            saveChanges($(this).serialize());
        } else {
            this.reportValidity();
        }
    });

    // Fermeture des modales d'ajout et de modification
    $("#modifiercycleenseignement .close").on('click',() => $('#modifiercycleenseignement').modal('hide'));
    $("#ajoutercycleenseignement .close").on('click',() => $('#ajoutercycleenseignement').modal('hide'));

    // Affichage de la modal d'ajout d'un nouveau cycle d'enseignement
    $("#modalAddNewCycleBtn").on('click',() => $('#ajoutercycleenseignement').modal('show'));

});


/**
 * Fonction pour pré-remplir et afficher le formulaire de modification d'un cycle d'enseignement.
 *
 * @param {number} id L'ID du cycle d'enseignement à modifier
 * @param {string} libelle Le libellé du cycle d'enseignement
 * @param {boolean} actif L'état d'activité du cycle d'enseignement (coché ou non)
 */
function editCycleenseignement(id,libelle,actif)
{
    const formModal = $('#modifiercycleenseignement');
    formModal.find('#idcycleenseignement').val(id);
    formModal.find('#libelle').val(libelle);

    const actifCheckbox = formModal.find('#actif');
    actifCheckbox.prop('checked',actif);

    if (actif) {
        actifCheckbox.trigger('change');
    }

    formModal.modal('show');
}


/**
 * Fonction pour ajouter un cycle d'enseignement via une requête AJAX.
 *
 * @param {string} formData Les données du formulaire sérialisées
 */
function ajouterCycleenseignement(formData)
{
    $.ajax({
        url: 'index.php?page=cycleenseignement',
        method: 'POST',
        data: {
            action:"ajoutercycleenseignement",
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


/**
 * Fonction pour enregistrer les modifications d'un cycle d'enseignement via une requête AJAX.
 *
 * @param {string} formData Les données du formulaire sérialisées
 */
function saveChanges(formData)
{
    $.ajax({
        url: 'index.php?page=cycleenseignement',
        method: 'POST',
        data: {
            action:"modifiercycleenseignement",
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