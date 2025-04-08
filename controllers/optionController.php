<?php

require_once './models/optionManager.php';
// Vérifie l'action pour ajouter une option
if (isset($_POST['action']) && $_POST['action'] === 'ajouteroption') {

    parse_str($_POST['formData'], $formData);
    $formData['actif'] = isset($formData['actif']);

    $formData = array_map(
        function ($value) {
            return $value === '' ? null : $value;
        },
        $formData
    );

    $res = ajouterOption($formData);
    echo $res;
    exit;
}

// Vérifie l'action pour ajouter une option pour une année spécifique
if (isset($_POST['action']) && $_POST['action'] === 'ajouteroptionannee') {

    parse_str($_POST['formData'], $formData);

    $formData = array_map(
        function ($value) {
            return $value === '' ? null : $value;
        },
        $formData
    );

    $res = ajouterOptionannee($formData);
    echo $res;
    exit;
}

// Vérifie l'action pour modifier une option
if (isset($_POST['action']) && $_POST['action'] === 'modifieroption') {

    parse_str($_POST['formData'], $formData);
    $formData['actif'] = isset($formData['actif']);

    $formData = array_map(
        function ($value) {
            return $value === '' ? null : $value;
        },
        $formData
    );

    $res = modifierOption($formData);
    echo $res;
    exit;
}

// Vérifie l'action pour modifier une option pour une année spécifique
if (isset($_POST['action']) && $_POST['action'] === 'modifieroptionannee') {

    parse_str($_POST['formData'], $formData);

    $formData = array_map(
        function ($value) {
            return $value === '' ? null : $value;
        },
        $formData
    );

    $res = modifierOptionannee($formData);
    echo $res;
    exit;
}

// Vérifie l'action pour filtrer les options par année
if (isset($_POST['action']) && $_POST['action'] === 'filter' && isset($_POST['year'])) {
    $id = intval($_POST['year']);
    $res = getOptionByYear($id);
    echo json_encode($res);
    exit;
}

// Vérifie l'action pour supprimer une option pour une année spécifique
if (isset($_POST['action']) && $_POST['action'] === 'supprimeroptionannee' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $res = removeOptionannee($id);
    echo json_encode($res);
    exit;
}

// Récupère toutes les options et les années distinctes
$options = getOptions();
$years = getDistinctYears();
$template = './views/pages/option.php';
