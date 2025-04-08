<?php

require_once './models/anneescolaireManager.php';
// Vérifie si l'action est d'ajouter une année scolaire
if (isset($_POST['action']) && $_POST['action'] == 'ajouteranneescolaire') {

    parse_str($_POST['formData'], $formData);
    $formData['actif'] = isset($formData['actif']);
    $res = ajouterAnneescolaire($formData);
    echo json_encode($res);
    exit;
}

// Vérifie si l'action est de modifier une année scolaire
if (isset($_POST['action']) && $_POST['action'] == 'modifieranneescolaire') {

    parse_str($_POST['formData'], $formData);
    $formData['actif'] = isset($formData['actif']);

    $formData = array_map(
        function ($value) {
            return $value === '' ? null : $value;
        },
        $formData
    );

    $res = modifierAnneescolaire($formData);
    echo json_encode($res);
    exit;
}

// Vérifie si l'action est de supprimer une année scolaire
if (
    isset($_POST['action']) &&
    $_POST['action'] == 'supprimeranneescolaire' && isset($_POST['id'])
) {
    $id = intval($_POST['id']);
    $res = supprimerAnneescolaire($id);
    echo json_encode($res);
    exit;
}

// Récupère toutes les années scolaires
$anneesscolaires = getAnneesscolaires();
// Définit le template à utiliser
$template = './views/pages/anneescolaire.php';
