var itemToDelete = null;
$(document).ready(function () {

    $("#ajoutercompetence-form").on('submit',function (e) {
        e.preventDefault();
        $("#ajoutercompetence-form input[type='submit']").prop("disabled",true);
        if (this.checkValidity()) {
            ajouterCompetence($(this).serialize());
        } else {
            this.reportValidity();
        }
    });

    $("#form-info").on('submit',function (e) {
        e.preventDefault();
        $("#form-info input[type='submit']").prop("disabled",true);
        if (this.checkValidity()) {
            modifierBlocCompetence($(this).serialize());
        } else {
            this.reportValidity();
        }
    });

    $("#modifiercompetence-form").on('submit',function (e) {
        e.preventDefault();
        $("#modifiercompetence-form input[type='submit']").prop("disabled",true);
        if (this.checkValidity()) {
            saveChanges($(this).serialize());
        } else {
            this.reportValidity();
        }
    });

    $("#confirmDeleteButton").click((e) => {
        supprimerCompetence(itemToDelete);
    });

});




function ajouterCompetence(formData)
{
    id = $('#form-info').find("#idbloccompetence").val();
    $.ajax({
        url: 'index.php?page=modifiercompetence&id=' + id,
        method: 'POST',
        data: {
            action:"ajoutercompetence",
            formData:formData
        },
        success: function (res) {
            res = JSON.parse(res);
            if (res.status === "success") {
                const formModal = $("#ajoutercompetence-form");
                formModal.find('#codecompetence').val("");
                formModal.find('#actionobservable').val("");
                formModal.find('#ressourcesmobilisees').val("");
                formModal.find('#finalitesatteignables').val("");
                window.location.reload();
            } else {
                showInfoModal(res.message, "error")
            }
        },
        error: function (xhr, status, error) {
            console.error("An error occurred: " + error);
        }
    });
}


function supprimerCompetence(id)
{
    idbc = $('#form-info').find("#idbloccompetence").val();
    $("#confirmDeleteButton").prop("disabled",true);
    $.ajax({
        url: 'index.php?page=modifiercompetence&id=' + idbc,
        method: 'POST',
        data: {
            action:"supprimerCompetence",
            id:id
        },
        dataType: 'json',
        success: function (res) {
            if (res) {
                $('#deleteModal').modal('hide');
                showInfoModal(res.message,res.status);
                setTimeout(() => {
                    window.location.reload();
                },1000);
            }

        },
        error: function (xhr, status, error) {
            console.error("An error occurred: " + error);
        }
    });
}



function modifierBlocCompetence(formData)
{
    id = $('#form-info').find("#idbloccompetence").val();
    $.ajax({
        url: 'index.php?page=modifiercompetence&id=' + id,
        method: 'POST',
        data: {
            action:"modifierBlocCompetence",
            formData:formData
        },
        success: function (res) {
            res = JSON.parse(res);
            showInfoModal(res.message, res.status)

            $("#form-info input[type='submit']").prop("disabled",false);
        },
        error: function (xhr, status, error) {
            console.error("An error occurred: " + error);
        }
    });
}



function saveChanges(formData)
{
    id = $('#form-info').find("#idbloccompetence").val();
    $.ajax({
        url: 'index.php?page=modifiercompetence&id=' + id,
        method: 'POST',
        data: {
            action:"modifiercompetence",
            formData:formData
        },
        success: function (res) {
            res = JSON.parse(res);
            if (res.status === "success") {
                window.location.reload();
            } else {
                showInfoModal(res.message,"error");
            }
        },
        error: function (xhr, status, error) {
            console.error("An error occurred: " + error);
        }
    });
}


function editCompetence(data)
{
    const formModal = $('#modifiercompetence');
    formModal.find('#idcompetence').val(data.idcompetence);
    formModal.find('#codecompetence').val(data.code);
    formModal.find('#actionobservable').val(data.actionobservable);
    formModal.find('#ressourcesmobilisees').val(data.ressourcesmobilisees);
    formModal.find('#finalitesatteignables').val(data.finalitesatteignables);

    formModal.modal('show');
}


function setItemToDelete(itemId)
{
    itemToDelete = itemId;
    console.log(itemToDelete)
    $('#deleteModal').modal('show');
}