<?php

require_once './models/departementManager.php';
/**
 * Ajoute un département.
 *
 * @param array $formData Les données du département.
 *
 * @return string Message de succès ou d'erreur.
 */
if (isset($_POST['action']) && $_POST['action'] === 'ajouterdepartement') {

    parse_str($_POST['formData'], $formData);
    $formData['actif'] = isset($formData['actif']);

    $formData = array_map(
        function ($value) {
            return $value === '' ? null : $value;
        },
        $formData
    );

    $res = ajouterDepartement($formData);
    echo $res;
    exit;
}

/**
 * Ajoute un département pour une année scolaire.
 *
 * @param array $formData Les données du département.
 *
 * @return string Message de succès ou d'erreur.
 */
if (isset($_POST['action']) && $_POST['action'] === 'ajouterdepartementannee') {

    parse_str($_POST['formData'], $formData);
    $formData = array_map(
        function ($value) {
            return $value === '' ? null : $value;
        },
        $formData
    );

    $res = ajouterDepartementannee($formData);
    echo $res;
    exit;
}

/**
 * Modifie un département.
 *
 * @param array $formData Les données du département.
 *
 * @return string Message de succès ou d'erreur.
 */
if (isset($_POST['action']) && $_POST['action'] === 'modifierdepartement') {

    parse_str($_POST['formData'], $formData);
    $formData['actif'] = isset($formData['actif']);

    $formData = array_map(
        function ($value) {
            return $value === '' ? null : $value;
        },
        $formData
    );

    $res = modifierDepartement($formData);
    echo $res;
    exit;
}

/**
 * Modifie un département pour une année scolaire.
 *
 * @param array $formData Les données du département.
 *
 * @return string Message de succès ou d'erreur.
 */
if (isset($_POST['action']) && $_POST['action'] === 'modifierdepartementannee') {

    parse_str($_POST['formData'], $formData);

    $formData = array_map(
        function ($value) {
            return $value === '' ? null : $value;
        },
        $formData
    );

    $res = modifierDepartementannee($formData);
    echo $res;
    exit;
}

/**
 * Filtre les départements par année.
 *
 * @param int $id L'ID de l'année scolaire.
 *
 * @return string JSON des départements filtrés.
 */
if (isset($_POST['action']) && $_POST['action'] === 'filter' && isset($_POST['year'])) {
    $id = intval($_POST['year']);
    $res = getDepartementByYear($id);
    echo json_encode($res);
    exit;
}

/**
 * Supprime un département pour une année scolaire.
 *
 * @param int $id L'ID du département à supprimer.
 *
 * @return string Message de succès ou d'erreur.
 */
if (isset($_POST['action']) && $_POST['action'] === 'supprimerdepartementannee' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $res = supprimerDepartementAnnee($id);
    echo json_encode($res);
    exit;
}

// Récupère les départements et les années distinctes
$departements = getDepartements();
$years = getDistinctYears();
// Définit le template à utiliser
$template = './views/pages/departement.php';
