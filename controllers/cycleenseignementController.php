<?php

require_once './models/cycleenseignementManager.php';
/**
 * Traitement pour l'ajout d'un cycle d'enseignement.
 *
 * Cette section vérifie si l'action `ajoutercycleenseignement` a été envoyée via une requête POST.
 * Si c'est le cas, elle traite les données du formulaire, définit la valeur du champ 'actif' et appelle
 * la fonction `ajouterCycleenseignement` pour insérer les données dans la base de données.
 */

if (isset($_POST['action']) && $_POST['action'] === 'ajoutercycleenseignement') {
    parse_str($_POST['formData'], $formData);
    $formData['actif'] = isset($formData['actif']);

    $formData = array_map(
        function ($value) {
            return $value === '' ? null : $value;
        },
        $formData
    );

    $res = ajouterCycleenseignement($formData);
    echo $res;
    exit;
}


/**
 * Traitement pour la modification d'un cycle d'enseignement.
 *
 * Cette section vérifie si l'action `modifiercycleenseignement` a été envoyée via une requête POST.
 * Si c'est le cas, elle traite les données du formulaire, définit la valeur du champ 'actif' et appelle
 * la fonction `modifierCycleenseignement` pour mettre à jour les données dans la base de données.
 */


if (isset($_POST['action']) && $_POST['action'] === 'modifiercycleenseignement') {
    parse_str($_POST['formData'], $formData);
    $formData['actif'] = isset($formData['actif']);

    $formData = array_map(
        function ($value) {
            return $value === '' ? null : $value;
        },
        $formData
    );

    $res = modifierCycleenseignement($formData);
    echo $res;
    exit;
}


/**
 * Récupère la liste de tous les cycles d'enseignement.
 *
 * Appel de la fonction `getCyclesenseignement` pour récupérer la liste des cycles d'enseignement.
 */

$cyclesenseignement = getCyclesenseignement();
/**
 * Chemin du fichier de vue à afficher.
 */

$template = './views/pages/cycleenseignement.php';
