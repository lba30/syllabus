$(document).ready(function () {
    // Initialisation des tableaux de données avec des labels en français
    new simpleDatatables.DataTable("#datatype", {
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

    new simpleDatatables.DataTable("#dataperiode", {
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

    // Gestion de la soumission du formulaire d'ajout de type
    $("#ajoutertype-form").on('submit',function (e) {
        e.preventDefault();
        $("#ajoutertype-form input[type='submit']").prop("disabled",true);
        if (this.checkValidity()) {
            ajouterTypeperiodeformation($(this).serialize());
        } else {
            this.reportValidity();
        }
    });

    // Gestion de la soumission du formulaire de modification de type
    $("#modifiertype-form").on('submit',function (e) {
        e.preventDefault();
        $("#modifiertype-form input[type='submit']").prop("disabled",true);
        if (this.checkValidity()) {
            saveChangesType($(this).serialize());
        } else {
            this.reportValidity();
        }
    });

    // Gestion de la soumission du formulaire d'ajout de période
    $("#ajouterperiode-form").on('submit',function (e) {
        e.preventDefault();
        $("#ajouterperiode-form input[type='submit']").prop("disabled",true);
        if (this.checkValidity()) {
            ajouterPeriodeformation($(this).serialize());
        } else {
            this.reportValidity();
        }
    });

    // Gestion de la soumission du formulaire de modification de période
    $("#modifierperiode-form").on('submit',function (e) {
        e.preventDefault();
        $("#modifierperiode-form input[type='submit']").prop("disabled",true);
        if (this.checkValidity()) {
            saveChangesPeriode($(this).serialize());
        } else {
            this.reportValidity();
        }
    });

     // Fermeture des modales d'ajout et de modification
     $("#modifierperiode .close").on('click',() => $('#modifierperiode').modal('hide'));
     $("#ajouterperiode .close").on('click',() => $('#ajouterperiode').modal('hide'));

     // Fermeture des modales d'ajout et de modification
     $("#modifiertype .close").on('click',() => $('#modifiertype').modal('hide'));
     $("#ajoutertype .close").on('click',() => $('#ajoutertype').modal('hide'));

     $("#openModalAjoutertypeBtn").on('click',() => $('#ajoutertype').modal('show'));
     $("#openModalAjouterperiodeBtn").on('click',() => $('#ajouterperiode').modal('show'));


});

// Fonction pour ajouter un type de période de formation
function ajouterTypeperiodeformation(formData)
{
    $.ajax({
        url: 'index.php?page=periodedeformation',
        method: 'POST',
        data: {
            action:"ajoutertypeperiodedeformation",
            formData:formData
        },
        dataType: 'json',
        success: function (res) {
            $('#ajoutertype').modal('hide')
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

// Fonction pour éditer un type de période de formation
function editType(type)
{
    const formModal = $('#modifiertype');
    formModal.find("#idtypeperiodeformation").val(type.idtypeperiodeformation);
    formModal.find("#libelle").val(type.libelle);
    formModal.find("#code").val(type.code);

    const actifCheckbox = formModal.find('#actif');
    actifCheckbox.prop('checked',type.actif);

    if (type.actif) {
        actifCheckbox.trigger('change');
    }

    $(formModal).modal('show');
}

// Fonction pour sauvegarder les modifications d'un type de période de formation
function saveChangesType(formData)
{
    $.ajax({
        url: 'index.php?page=periodedeformation',
        method: 'POST',
        data: {
            action:"modifiertypeperiodedeformation",
            formData:formData
        },
        dataType: 'json',
        success: function (res) {
            $('#modifiertype').modal('hide')
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

// Fonction pour ajouter une période de formation
function ajouterPeriodeformation(formData)
{
    $.ajax({
        url: 'index.php?page=periodedeformation',
        method: 'POST',
        data: {
            action:"ajouterperiodedeformation",
            formData:formData
        },
        dataType: 'json',
        success: function (res) {
            $('#ajouterperiode').modal('hide')
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

// Fonction pour éditer une période de formation
function editPeriode(type)
{
    const formModal = $('#modifierperiode');
    formModal.find("#idperiodeformation").val(type.idperiodeformation);
    formModal.find("#idtypeperiodeformation").val(type.idtypeperiodeformation);
    formModal.find("#libelle").val(type.libelle);

    const actifCheckbox = formModal.find('#actif');
    actifCheckbox.prop('checked',type.actif);

    if (type.actif) {
        actifCheckbox.trigger('change');
    }

    $(formModal).modal('show');
}

// Fonction pour sauvegarder les modifications d'une période de formation
function saveChangesPeriode(formData)
{
    $.ajax({
        url: 'index.php?page=periodedeformation',
        method: 'POST',
        data: {
            action:"modifierperiodedeformation",
            formData:formData
        },
        dataType: 'json',
        success: function (res) {
            $('#modifierperiode').modal('hide')
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
