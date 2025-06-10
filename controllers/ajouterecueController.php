<?php

require_once './models/ecueManager.php';
require_once './models/userManager.php';
$idmodule = intval($_GET['id']);

// Vérifie si une action d'ajout d'ECUE est demandée
if (isset($_POST['action']) && $_POST['action'] === 'ajouterEcue') {

    parse_str($_POST['formData'], $formData);
    $formData['idue'] = $idmodule;
   
    $formData = array_map(
        function ($value) {
            return $value === '' ? null : $value;
        },
        $formData
    );

    $formData['socioenvdimension'] ??= [];


    // Ajoute l'ECUE avec les données du formulaire
    $res = ajouterEcue($formData);
    header('Content-Type: application/json');
    echo $res;
    exit;
}

// Récupère les responsables et les options ONU pour le formulaire
$responsables = getResponsables();
$onuOptions = getOnuOptions();
// Définit le template de la page d'ajout d'ECUE
$template = './views/pages/ajouterEcue.php';
