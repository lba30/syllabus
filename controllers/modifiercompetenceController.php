<?php

require_once './models/competenceManager.php';
$id =  intval($_GET['id']);
// Récupérer le bloc de compétence par ID
$bc = getBlocCompetence($id);
// Vérifier si l'action est d'ajouter une compétence
if (isset($_POST['action']) && $_POST['action'] == 'ajoutercompetence') {

    parse_str($_POST['formData'], $formData);

    $formData = array_map(
        function ($value) {
            return $value === '' ? null : $value;
        },
        $formData
    );

    // Ajouter une nouvelle compétence
    echo addCompetence($formData);
    exit;
}

// Vérifier si l'action est de modifier une compétence
if (isset($_POST['action']) && $_POST['action'] == 'modifiercompetence') {

    parse_str($_POST['formData'], $formData);

    $formData = array_map(
        function ($value) {
            return $value === '' ? null : $value;
        },
        $formData
    );

    // Modifier une compétence existante
    echo modifierCompetence($formData);
    exit;
}

// Vérifier si l'action est de modifier un bloc de compétence
if (isset($_POST['action']) && $_POST['action'] == 'modifierBlocCompetence') {

    parse_str($_POST['formData'], $formData);

    $formData = array_map(
        function ($value) {
            return $value === '' ? null : $value;
        },
        $formData
    );

    // Modifier un bloc de compétence existant
    echo modifierBlocCompetence($formData);
    exit;
}

// Vérifier si l'action est de supprimer une compétence
if (isset($_POST['action']) && $_POST['action'] == 'supprimerCompetence' && isset($_POST['id'])) {
    $idC =  intval($_POST['id']);
    // Supprimer une compétence par ID
    echo removeCompetence($idC);
    exit;
}

// Définir le template de la page
$template = './views/pages/modifiercompetence.php';
