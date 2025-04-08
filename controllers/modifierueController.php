<?php

require_once './models/modifierueManager.php';
// Récupère l'identifiant du module à partir des paramètres GET
$idmodule = intval($_GET['id']);
// Vérifie si l'action est de modifier une UE
if (isset($_POST['action']) && $_POST['action'] === 'modifierUE') {

    // Parse les données du formulaire
    parse_str($_POST['formData'], $formData);
    $competences = $_POST['competences'];
    // Ajoute l'identifiant du module aux données du formulaire
    $formData['id'] = $idmodule;
    

   
    $formData = array_map(
        function ($value) {
            return $value === '' ? null : $value;
        },
        $formData
    );

    // Appelle la fonction pour modifier l'UE
    $res = modifierUE($formData, $competences);
    // Retourne la réponse en JSON
    header('Content-Type: application/json');
    echo json_encode($res);
    exit;
}

// Vérifie si l'action est d'ajouter une compétence à un bloc de compétence
if (isset($_POST['action']) && $_POST['action'] === 'ajouterbccompetence' && isset($_POST['bcId'])) {

    $bcId = intval($_POST['bcId']);
    $res = ajouterbccompetence($idmodule, $bcId);
    echo $res;
    exit;
}

// Vérifie si l'action est de supprimer une ECUE
if (isset($_POST['action']) && $_POST['action'] === 'removeecue' && isset($_POST['idecue'])) {
    $idecue = intval($_POST['idecue']);
    $res = removeecue($idecue);
    echo $res;
    exit;
}

// Vérifie si l'action est de supprimer un bloc de compétence
if (isset($_POST['action']) && $_POST['action'] == 'supprimerBlocCompetence' && isset($_POST['idb'])) {
    $idB =  intval($_POST['idb']);
    $res = supprimerBlocCompetenceUe($idB, $idmodule);
    echo $res;
    exit;
}

// Récupère les options de blocs de compétence non ajoutés
$bcOptions = getBlocCompetenceNonAjouter($idmodule);
// Récupère les responsables
$responsables = getResponsables();
// Récupère les détails de l'UE
$ue = getUEDetails($idmodule);
// Définit le template à utiliser
$template = './views/pages/modifierUe.php';
