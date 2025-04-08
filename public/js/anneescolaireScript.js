$(document).ready(function () {
    dataTable = new simpleDatatables.DataTable("#dataanneescolaire", {
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


    $("#ajouteranneescolaire-form").on('submit',function (e) {
        e.preventDefault();
        $("#ajouteranneescolaire-form input[type='submit']").prop("disabled",true);
        if (this.checkValidity()) {
            ajouterAnneescolaire($(this).serialize());
        } else {
            this.reportValidity();
        }
    });

    $("#modifieranneescolaire-form").on('submit',function (e) {
        e.preventDefault();
        $("#modifieranneescolaire-form input[type='submit']").prop("disabled",true);
        if (this.checkValidity()) {
            saveChanges($(this).serialize());
        } else {
            this.reportValidity();
        }
    });

    $("#modifieranneescolaire .close").on('click',() => $('#modifieranneescolaire').modal('hide'));
    $("#ajouteranneescolaire .close").on('click',() => $('#ajouteranneescolaire').modal('hide'));


    $("#modalAddNewYearBtn").on('click',() => $('#ajouteranneescolaire').modal('show'));
});

function ajouterAnneescolaire(formData)
{
    $.ajax({
        url: 'index.php?page=anneescolaire',
        method: 'POST',
        data: {
            action:"ajouteranneescolaire",
            formData:formData
        },
        dataType: 'json',
        success: function (res) {
            $('#ajouteranneescolaire').modal('hide')
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



function editAnneescolaire(data)
{
    const formModal = $('#modifieranneescolaire');
    formModal.find('#idanneescolaire').val(data.idanneescolaire);
    formModal.find('#libelle').val(data.libelle);
    formModal.find('#datedebut').val(data.datedebut);
    formModal.find('#datefin').val(data.datefin);

    const actifCheckbox = formModal.find('#actif');
    actifCheckbox.prop('checked', data.actif);

    if (data.actif) {
        actifCheckbox.trigger('change');
    }

    formModal.modal('show');
}



function saveChanges(formData)
{
    $.ajax({
        url: 'index.php?page=anneescolaire',
        method: 'POST',
        data: {
            action:"modifieranneescolaire",
            formData:formData
        },
        dataType: 'json',
        success: function (res) {
            $('#modifieranneescolaire').modal('hide');
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