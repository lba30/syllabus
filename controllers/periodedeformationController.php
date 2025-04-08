<?php

require_once './models/periodedeformationManager.php';
// Vérifie si l'action est d'ajouter un type de période de formation
if (isset($_POST['action']) && $_POST['action'] == 'ajoutertypeperiodedeformation') {

    parse_str($_POST['formData'], $formData);
    $formData['actif'] = isset($formData['actif']);

    $formData = array_map(
        function ($value) {
            return $value === '' ? null : $value;
        },
        $formData
    );

    $res = ajoutertypeperiodedeformation($formData);
    echo $res;
    exit;
}

// Vérifie si l'action est de modifier un type de période de formation
if (isset($_POST['action']) && $_POST['action'] == 'modifiertypeperiodedeformation') {

    parse_str($_POST['formData'], $formData);
    $formData['actif'] = isset($formData['actif']);

    $formData = array_map(
        function ($value) {
            return $value === '' ? null : $value;
        },
        $formData
    );

    $res = modifiertypeperiodedeformation($formData);
    echo $res;
    exit;
}

// Vérifie si l'action est d'ajouter une période de formation
if (isset($_POST['action']) && $_POST['action'] == 'ajouterperiodedeformation') {

    parse_str($_POST['formData'], $formData);
    $formData['actif'] = isset($formData['actif']);

        $formData = array_map(
        function ($value) {
            return $value === '' ? null : $value;
        },
        $formData
    );

    $res = ajouterperiodedeformation($formData);
    echo $res;
    exit;
}

// Vérifie si l'action est de modifier une période de formation
if (isset($_POST['action']) && $_POST['action'] == 'modifierperiodedeformation') {

    parse_str($_POST['formData'], $formData);
    $formData['actif'] = isset($formData['actif']);

    $formData = array_map(
        function ($value) {
            return $value === '' ? null : $value;
        },
        $formData
    );

    $res = modifierperiodedeformation($formData);
    echo $res;
    exit;
}

// Récupère les types de périodes de formation et les périodes de formation
$typePF = getTypePeriodeFormation();
$periodeF = getPeriodeFormation();
// Définit le template à utiliser
$template = './views/pages/periodedeformation.php';
