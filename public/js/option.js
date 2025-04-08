var dataTable = null;
var itemToDelete = null;

$(document).ready(function () {
    new simpleDatatables.DataTable("#dataoption", {
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
    updateTable([]);


    $("#ajouteroption-form").on('submit',function (e) {
        e.preventDefault();
        $("#ajouteroption-form input[type='submit']").prop("disabled",true);
        if (this.checkValidity()) {
            ajouterOption($(this).serialize());
        } else {
            this.reportValidity();
        }
    });

    $("#modifieroption-form").on('submit',function (e) {
        e.preventDefault();
        $("#modifieroption-form input[type='submit']").prop("disabled",true);

        if (this.checkValidity()) {
            saveChangesOption($(this).serialize());
        } else {
            this.reportValidity();
        }
    });


    $('#yearFilter').change(function () {
        let selectedYear = $(this).val();
        if (selectedYear !== "") {
            loadData(selectedYear);
        } else {
            updateTable([]);
        }

    });

    $("#ajouteroptionannee-form").on('submit',function (e) {
        e.preventDefault();
        $("#ajouteroptionannee-form input[type='submit']").prop("disabled",true);

        if (this.checkValidity()) {
            ajouterOptionAnnee($(this).serialize());
        } else {
            this.reportValidity();
        }
    });

    $("#modifieroptionannee-form").on('submit',function (e) {
        e.preventDefault();
        $("#modifieroptionannee-form input[type='submit']").prop("disabled",true);

        if (this.checkValidity()) {
            saveChangesOptionAnnee($(this).serialize());
        } else {
            this.reportValidity();
        }
    });

    $("#ajouteroption .close").on('click',() => $('#ajouteroption').modal('hide'));
    $("#modifieroption .close").on('click',() => $('#modifieroption').modal('hide'));
    $("#ajouteroptionannee .close").on('click',() => $('#ajouteroptionannee').modal('hide'));
    $("#modifieroptionannee .close").on('click',() => $('#modifieroptionannee').modal('hide'));


    $("#openModalAjouteroptionBtn").on('click',() => $('#ajouteroption').modal('show'));
    $("#openModalAjouteroptionanneeBtn").on('click',() => $('#ajouteroptionannee').modal('show'));


    $("#confirmDeleteButton").click((e) => {
        $("#confirmDeleteButton").prop("disabled",true);
        supprimerOptionannee(itemToDelete);
    });

});


function ajouterOption(formData)
{
    $.ajax({
        url: 'index.php?page=option',
        method: 'POST',
        data: {
            action:"ajouteroption",
            formData:formData
        },
        dataType: 'json',
        success: function (res) {
            $('#ajouteroption').modal('hide')
            showInfoModal(res.message,res.status)
            if (res.status === "success") {
                setTimeout(() => {
                    window.location.reload();
                },1000)
            }

        },
        error: function (xhr, status, error) {
            console.error("An error occurred: " + error);
        }
    });
}

function ajouterOptionAnnee(formData)
{
    $.ajax({
        url: 'index.php?page=option',
        method: 'POST',
        data: {
            action:"ajouteroptionannee",
            formData:formData
        },
        dataType: 'json',
        success: function (res) {
            $('#ajouteroptionannee').modal('hide')
            showInfoModal(res.message,res.status)
            if (res.status === "success") {
                setTimeout(() => {
                    window.location.reload();
                },1000)
            }

        },
        error: function (xhr, status, error) {
            console.error("An error occurred: " + error);
        }
    });
}


function editOption(data)
{
    formModal = $("#modifieroption");

    formModal.find("#idoption").val(data.idoption);
    formModal.find("#libelle").val(data.libelle);

    const actifCheckbox = formModal.find('#actif');
    actifCheckbox.prop('checked',data.actif);

    if (data.actif) {
        actifCheckbox.trigger('change');
    }


    formModal.modal('show');
}

function editOptionannee(button)
{
    const formModal = $('#modifieroptionannee');
    const data = JSON.parse(button.getAttribute('data-oa'));

    formModal.find("#idanneescolaire").val(data.idanneescolaire);
    formModal.find("#idoption").val(data.idoption);
    formModal.find("#idoptionannee").val(data.idoptionannee);
    formModal.find('#libelle').val(data.libelle);
    formModal.find('#code').val(data.code);

    formModal.modal('show');
}

function saveChangesOption(formData)
{
    $.ajax({
        url: 'index.php?page=option',
        method: 'POST',
        data: {
            action:"modifieroption",
            formData:formData
        },
        dataType: 'json',
        success: function (res) {
            $('#modifieroption').modal('hide')
            showInfoModal(res.message,res.status)
            if (res.status === "success") {
                setTimeout(() => {
                    window.location.reload();
                },1000)
            }

        },
        error: function (xhr, status, error) {
            console.error("An error occurred: " + error);
        }
    });
}


function saveChangesOptionAnnee(formData)
{
    $.ajax({
        url: 'index.php?page=option',
        method: 'POST',
        data: {
            action:"modifieroptionannee",
            formData:formData
        },
        dataType: 'json',
        success: function (res) {
            $('#yearFilter').val("");
            $('#modifieroptionannee').modal('hide')
            showInfoModal(res.message,res.status)
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

function loadData(selectedYear)
{
    $.ajax({
        url: 'index.php?page=option',
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


function updateTable(data)
{
    if (dataTable) {
        dataTable.destroy();
        $('#dataOA-container').empty();
    }

    $('#dataOA-container').html(`
        <table id = "dataOA">
            <thead>
                <th> Libellé </th>
                <th> Code </th>
                <th> Actions </th>
            </thead>
            <tbody id = "dataOA-body"></tbody>
        </table>
        `)

    var tableBody = $('#dataOA-body');

    $.each(data,(index,oa) => {
        tableBody.append(
            $('<tr>').append(
                $('<td>').text(oa.libelle),
                $('<td>').text(oa.code),
                $('<td>').append(
                    $('<button>').addClass('btn btn-primary mr-1')
                        .text('Modifier')
                        .attr('data-oa', JSON.stringify(oa))
                        .on('click',function () {
                            editOptionannee(this);}),
                    $('<button>').addClass('btn btn-danger')
                        .text('Supprimer')
                        .on('click',function () {
                            setItemToDelete(oa.idoptionannee);})
                )
            )
        )

    });
    if (!allowDelete) {
        $('.btn-danger').not('.except').hide();
    }

    dataTable = new simpleDatatables.DataTable("#dataOA", {
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







function supprimerOptionannee(id)
{
    $.ajax({
        url: 'index.php?page=option',
        method: 'POST',
        data: {
            action:"supprimeroptionannee",
            id:id
        },
        dataType: 'json',
        success: function (res) {
            $('#deleteModal').modal('hide');
            res = JSON.parse(res);
            showInfoModal(res.message,res.status)
            $('#yearFilter').val("");
            $('#yearFilter').trigger("change");

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