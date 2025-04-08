<?php

require_once './models/cycleenseignementanneeManager.php';
/*
 * Vérifie l'action 'filter'
   et retourne les cycles d'enseignement pour une année donnée.
 *
 * @param array $_POST Contient l'année scolaire
    pour filtrer les cycles d'enseignement.
 * @return array Contient les cycles d'enseignement pour l'année spécifiée.
*/
if (
    isset($_POST['action']) && $_POST['action'] === 'filter' && isset($_POST['year'])
) {
    $id = intval($_POST['year']);
    $res = getCycleenseignementanneeByYear($id);
    echo json_encode($res);
    exit;
}


/*
 * Traitement pour l'ajout d'un cycle d'enseignement.
 *
 * Cette section vérifie si l'action `ajoutercycleenseignement`
  a été envoyée via une requête POST.
 * Si c'est le cas, elle traite les données du formulaire,
  définit la valeur du champ 'actif' et appelle
 * la fonction `ajouterCycleenseignement` pour
   insérer les données dans la base de données.
 */

if (
    isset($_POST['action']) && $_POST['action'] === 'ajoutercycleenseignementannee'
) {

    parse_str($_POST['formData'], $formData);

    $formData = array_map(
        function ($value) {
            return $value === '' ? null : $value;
        },
        $formData
    );

    $res = ajouterCycleenseignementannee($formData);
    echo $res;
    exit;
}

/*
 * Vérifie l'action 'modifercycleenseignementannee'
  et modifie un cycle d'enseignement existant.
 *
 * @param array $_POST Contient les données du formulaire sous forme sérialisée.
 * @return json Confirmation de la modification ou message d'erreur.
 */
if (
    isset($_POST['action']) && $_POST['action'] === 'modifercycleenseignementannee'
) {

    parse_str($_POST['formData'], $formData);

    $formData = array_map(
        function ($value) {
            return $value === '' ? null : $value;
        },
        $formData
    );

    $res = modifierCycleenseignementannee($formData);
    echo $res;
    exit;
}

/*
 * Vérifie l'action 'supprimercycleenseignementannee'
 * et supprime un cycle d'enseignement pour une année.
 *
 * @param array $_POST Contient l'ID du cycle d'enseignement à supprimer.
 * @return json Confirmation de la suppression ou message d'erreur.
 */
if (
    isset($_POST['action']) && $_POST['action'] === 'supprimercycleenseignementannee'
    && isset($_POST['id'])
) {
    $id = intval($_POST['id']);
    $res = supprimerCycleenseignementannee($id);
    echo $res;
    exit;
}

$cycles = getCyclesenseignement();
$years = getDistinctYears();
// Affichage du template correspondant
$template = './views/pages/cycleenseignementannee.php';
