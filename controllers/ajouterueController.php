<?php

require_once './models/ajouterueManager.php';
// Récupérer les années distinctes et les responsables
$years = getDistinctYears();
$responsables = getResponsables();
// Vérifier l'action pour obtenir les options de filtre de cycle
if (isset($_POST['action']) && $_POST['action'] == 'getCycleFilterOptions') {
    $yearId = $_POST['year'];
    $options = getCycles($yearId);
    echo json_encode($options);
    exit;
}

// Vérifier l'action pour obtenir les options de semestre
if (isset($_POST['action']) && $_POST['action'] == 'getSemestreOptions') {
    $cycleId = $_POST['cycle'];
    $options = getSemestres($cycleId);
    echo json_encode($options);
    exit;
}

// Vérifier l'action pour obtenir les options de département
if (isset($_POST['action']) && $_POST['action'] == 'getDepartementOptions') {
    $yearId = $_POST['year'];
    $options = getDepartemnts($yearId);
    echo json_encode($options);
    exit;
}

// Vérifier l'action pour obtenir les options d'option
if (isset($_POST['action']) && $_POST['action'] == 'getOptionOptions') {
    $yearId = $_POST['year'];
    $options = getOptions($yearId);
    echo json_encode($options);
    exit;
}

// Vérifier l'action pour ajouter une UE
if (isset($_POST['action']) && $_POST['action'] == 'ajouterue') {

    
    parse_str($_POST['formData'], $formData);
    if ($formData['responsable'] === '') {
        $formData['responsable'] = null;
    }

    $formData = array_map(
        function ($value) {
            return $value === '' ? null : $value;
        },
        $formData
    );
    
    $res = ajouterUe($formData);
    echo json_encode($res);
    exit;
}

// Définir le template à utiliser
$template = './views/pages/ajouterUe.php';
