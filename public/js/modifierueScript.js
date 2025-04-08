// Variable pour stocker l'ECUE à supprimer
var ecueToDelete = null;

// Fonction pour supprimer un bloc de compétence
function supprimerBlocCompetence(id,idb)
{
    $("#competency-table button").prop("disabled",true);
    $.ajax({
        url: 'index.php?page=modifierue&id=' + id,
        method: 'POST',
        data: {
            action:"supprimerBlocCompetence",
            idb:idb
        },
        success: function (res) {
            res = JSON.parse(res);
            if (res.status === "success") {
                console.log(res);
                updateBlocCompLayout(res.newBC,res.idUE);
                updateSelectOptionBC(res.nonAddedBC);
            } else {
                alert(res.message);
            }
            $("#competency-table button").prop("disabled",false);
        },
        error: function (xhr, status, error) {
            console.error("An error occurred: " + error);
            $("#competency-table button").prop("disabled",false);
        }
    });
}

// Fonction pour ajouter un bloc de compétence
function addbloccompetence(id)
{
    const selectedBC = $('#ajouterBC').val();
    $("#addBCBtn").prop("disabled",true);
    $.ajax({
        url:'index.php?page=modifierue&id=' + id,
        method: 'POST',
        data: {
            action:'ajouterbccompetence',
            bcId : selectedBC
        },
        success:function (res) {
            res = JSON.parse(res);
            updateBlocCompLayout(res.newBC,res.idUE);
            updateSelectOptionBC(res.nonAddedBC);
            $("#addBCBtn").prop("disabled",false);
        },
        error: function (xhr, status, error) {
            console.error("An error occurred: " + error);
            $("#addBCBtn").prop("disabled",false);
        }
    })
}

// Fonction pour mettre à jour les options du sélecteur de blocs de compétence
function updateSelectOptionBC(selectOptions)
{
    const selectOptionBC = $("#ajouterBC");
    selectOptionBC.empty();

    selectOptions.forEach(option => {
        $("<option>",{
            value:option.idbloccompetence,
            text: `${option.code} - ${option.libelle}`
        }).appendTo(selectOptionBC)
    })
}

// Fonction pour mettre à jour la mise en page des blocs de compétence
function updateBlocCompLayout(bc,idUE)
{
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (tooltipTriggerEl) {
        const tooltipInstance = bootstrap.Tooltip.getInstance(tooltipTriggerEl);
        if (tooltipInstance) {
            tooltipInstance.hide();
            tooltipInstance.dispose();
        }
    });

    const container = $("#competency-table");
    container.empty();
    const maxCompetencies = Math.max(...bc.map(bc => bc.competences.length));

    bc.forEach((bloc) => {
        const blocTable = $("<table>").addClass("bloc-comp");

        blocTable.append(
            $("<tr>").append(
                $("<td>").append(
                    $("<button>")
                    .addClass("btn btn-danger")
                    .text("×")
                    .on("click", () => supprimerBlocCompetence(idUE,bloc.id))
                )
            )
        );

        blocTable.append(
            $("<tr>").append(
                $("<td>")
                .attr({
                    id: bloc.id,
                    "data-bs-toggle": "tooltip",
                    "data-bs-title": bloc.libelle || " "
                })
                .addClass(`bloc-header ${bloc.actif ? "bloc-actif" : ""}`)
                .text(bloc.code)
            )
        );

        let index = 0;
        bloc.competences.forEach((competence) => {
            index++;
            blocTable.append(
                $("<tr>").append(
                    $("<td>")
                    .attr({
                        id: competence.id,
                        "data-bs-toggle": "tooltip",
                        "data-bs-title": competence.libelle
                    })
                    .addClass(`comp ${competence.etat}`)
                    .text(competence.code)
                )
            );
          });

    for (let i = index; i < maxCompetencies; i++) {
        blocTable.append($("<tr>").append($("<td>").addClass("vide")));
    }

        container.append($("<td>").append(blocTable));
    })

    initializeBlocs();
}

// Fonction pour mettre à jour une UE
function updateUE(id)
{
    $('#bcModifyBtn').prop('disabled', true);
    let formData = $('#form-infogenerale');
    if (formData[0].checkValidity()) {
        $.ajax({
            url:'index.php?page=modifierue&id=' + id,
            method:'POST',
            data:{
                action:'modifierUE',
                competences:getState(),
                formData:formData.serialize()
            },
            success : function (res) {
                res = JSON.parse(res);
                if (res.status === 'success') {
                    showInfoModal(res.message, "success")
                } else {
                    showInfoModal("Une erreur est survenue lors de la mise à jour des compétences.", "error")
                }
                $('#bcModifyBtn').prop('disabled', false);
            }
        })
    } else {
        formData[0].reportValidity();
        $('#bcModifyBtn').prop('disabled', false);
    }

}

// Fonction pour supprimer une ECUE
function removeECUE(ecueToDelete)
{
    $('#deleteModal').modal('show');
    $.ajax({
        url:'index.php?page=modifierue&id=' + ecueToDelete.ue,
        method:'POST',
        data:{
            action:'removeecue',
            idecue:ecueToDelete.ecue,
        },
        success : function (res) {
            res = JSON.parse(res);
            window.location.reload();
        }

    })
}

// Fonction pour définir l'ECUE à supprimer
function setItemToDelete(ueId,ecueId)
{
    ecueToDelete = {'ue':ueId,'ecue':ecueId};
    $('#deleteModal').modal('show');
}

// Initialisation des événements au chargement du document
$(document).ready(function () {
    const textarea = document.getElementById('description');
    const currentLength = document.getElementById('currentLength');
    currentLength.textContent = `${textarea.value.length}`;
    textarea.addEventListener('input', function () {
        currentLength.textContent = `${this.value.length}`;
    });

    const ects = document.getElementById('ects');

    ects.addEventListener('input', function (e) {
        const cursorPosition = this.selectionStart;
        const oldValue = this.value;
        const newValue = oldValue.replace(/[^0-9.]/g, '');

        if (oldValue !== newValue) {
            this.value = newValue;

            const cursorAdjustment = oldValue.length - newValue.length;
            this.setSelectionRange(cursorPosition - cursorAdjustment, cursorPosition - cursorAdjustment);
        }


        if (this.value.split('.').length > 2) {
            const parts = this.value.split('.');
            this.value = parts[0] + '.' + parts.slice(1).join('');
        }
    });

    initializeBlocs();

    $("#confirmDeleteButton").click((e) => {
        $("#confirmDeleteButton").prop('disabled', true);
        removeECUE(ecueToDelete);
    });
})

// Fonction pour basculer l'état actif d'un bloc de compétence
function toggleBC(bcHeader)
{
    const shapes = ['nad','ens', 'eva', 'enseva','meo'];
    bcHeader.classList.toggle('bloc-actif');
    const isActive = bcHeader.classList.contains('bloc-actif');

    if (!isActive) {
        const compElements = bcHeader.closest('.bloc-comp').querySelectorAll('.comp');

        compElements.forEach(comp => {
            comp.classList.remove(...shapes);
            comp.classList.add(shapes[0]);
        });
    }

}

// Fonction pour initialiser les blocs de compétence
function initializeBlocs()
{
    const bcHeaders = document.querySelectorAll('.bloc-header');

    bcHeaders.forEach(bcHeader => {
        bcHeader.addEventListener('click', () => toggleBC(bcHeader));
        bcHeader.closest('.bloc-comp').querySelectorAll('.comp').forEach(comp => {
            comp.addEventListener('click', () => cycleShape(bcHeader,comp));
        });
    });



    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

}

// Fonction pour faire défiler les états des compétences
function cycleShape(bcHeader,compElement)
{
    const isActive = bcHeader.classList.contains('bloc-actif');

    if (isActive) {
        const shapes = ['nad','ens', 'eva', 'enseva','meo'];
        let currentShape = shapes.find(shape => compElement.classList.contains(shape)) || shapes[0];
        let nextShapeIndex = (shapes.indexOf(currentShape) + 1) % shapes.length;
        compElement.classList.remove(...shapes);
        compElement.classList.add(shapes[nextShapeIndex]);
    }
}

// Fonction pour obtenir l'état actuel des blocs de compétence
function getState()
{
    const bcHeaders = document.querySelectorAll('.bloc-header');
    let bcs = [...bcHeaders].map(
        bcHeader =>
        ({
            'id':bcHeader.id,
            'actif':bcHeader.classList.contains('bloc-actif'),
            competences: [...(bcHeader.closest('.bloc-comp').querySelectorAll('.comp'))].map(comp => ({
                id:comp.id,
                etat: comp.className.split(' ')[1]
                }))
        })
    );

    return bcs;
}


