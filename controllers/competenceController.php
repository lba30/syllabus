<?php

require_once './models/competenceManager.php';
// Vérifie si l'action est de supprimer un bloc de compétence et si l'ID est défini
if (isset($_POST['action']) && $_POST['action'] == 'supprimerBloccompetence' && isset($_POST['id'])) {
    // Supprime le bloc de compétence avec l'ID fourni
    $res = removeBlocCompetence($_POST['id']);
    // Renvoie le résultat en format JSON
    echo json_encode($res);
    exit;
}

// Vérifie si l'action est d'ajouter un bloc de compétence
if (isset($_POST['action']) && $_POST['action'] == 'ajouteBloccompetence') {

    // Analyse les données du formulaire
    parse_str($_POST['formData'], $formData);

    $formData = array_map(
        function ($value) {
            return $value === '' ? null : $value;
        },
        $formData
    );

    // Ajoute un nouveau bloc de compétence avec les données du formulaire
    $res = addBlocCompetence($formData);
    // Renvoie le résultat
    echo $res;
    exit;
}

// Récupère tous les blocs de compétences
$bloccompetences = getBlocCompetences();
// Définit le template à utiliser
$template = './views/pages/competence.php';
