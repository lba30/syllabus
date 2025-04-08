var itemToDelete = null;

$(document).ready(function () {
    dataTable = new simpleDatatables.DataTable("#datacompetence", {
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

    $("#ajouterbloccompetence-form").on('submit',function (e) {
        e.preventDefault();

        if (this.checkValidity()) {
            ajouterBlocCompetence($(this).serialize());
        } else {
            this.reportValidity();
        }
    });

    $("#confirmDeleteButton").click((e) => {
        supprimerBloccompetence(itemToDelete);
    });

});


function supprimerBloccompetence(id)
{
    $('#confirmDeleteButton').prop('disabled',true);
    $.ajax({
        url: 'index.php?page=competence',
        method: 'POST',
        data: {
            action:"supprimerBloccompetence",
            id:id
        },
        dataType: 'json',
        success: function (res) {
            res = JSON.parse(res)
            $('#deleteModal').modal('hide');
            if (res.status === "success") {
                showInfoModal("Le bloc de compétences supprimé avec succès !", "success")
                setTimeout(() => {
                    window.location.reload();
                },1000)
            } else {
                showInfoModal("Le bloc de compétences n'a pas pu être supprimé.", "error")
            }
            $('#confirmDeleteButton').prop('disabled',true);
        },
        error: function (xhr, status, error) {
            console.error("An error occurred: " + error);
        }
    });
}

function ajouterBlocCompetence(formData)
{
    $("#ajoutetBCBtn").prop("disabled",true);
    $.ajax({
        url: 'index.php?page=competence',
        method: 'POST',
        data: {
            action:"ajouteBloccompetence",
            formData:formData
        },
        dataType: 'json',
        success: function (res) {
            if (res.status === "success") {
                window.location.href = "index.php?page=modifiercompetence&id=" + res.idbc
            } else {
                showInfoModal(res.message,"error")
            }
        },
        error: function (xhr, status, error) {
            console.error("An error occurred: " + error);
        }
    });
}


function setItemToDelete(itemId)
{
    itemToDelete = itemId;
    console.log(itemToDelete)
    $('#deleteModal').modal('show');
}