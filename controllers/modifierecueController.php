<?php

require_once './models/ecueManager.php';
require_once './models/userManager.php';

$idmatiere = intval($_GET['id']);
// Récupère l'identifiant de la matière depuis les paramètres GET

if (isset($_POST['action']) && $_POST['action'] === 'modifierEcue') {

    // Parse les données du formulaire
    parse_str($_POST['formData'], $formData);

    $formData['id'] = $idmatiere;

    $formData = array_map(
        function ($value) {
            return $value === '' ? null : $value;
        },
        $formData
    );

    // Définit à un tableau vide si non défini
    $formData['socioenvdimension'] ??= [];
    
    // Appelle la fonction pour modifier l'ECUE
    $res = modifierEcue($formData);
    echo $res;
    exit;
}

$ecue = getEcue($idmatiere);
// Récupère les informations de l'ECUE
$responsables = getResponsables();
// Récupère la liste des responsables
$onuOptions = getOnuOptions();
// Récupère les options ONU
$selectedONUs = getSelectedONUForEcue($idmatiere);
// Récupère les options ONU sélectionnées pour l'ECUE

$template = './views/pages/modifierEcue.php'; // Définit le template à utiliser
