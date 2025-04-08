<?php

require_once './models/periodedeformationanneeManager.php';

/**
 * Récupère les options de filtre de cycle pour une année donnée.
 *
 * @param int $yearId L'ID de l'année scolaire.
 *
 * @return string JSON des options de filtre de cycle.
 */
if (isset($_POST['action']) && $_POST['action'] == 'getCycleFilterOptions') {
    $yearId = $_POST['year'];
    $oycles = getCycles($yearId);
    echo json_encode($oycles);
    exit;
}

/**
 * Filtre les périodes de formation par cycle.
 *
 * @param int $CycleId L'ID du cycle d'enseignement.
 *
 * @return string JSON des périodes de formation filtrées.
 */
if (isset($_POST['action']) && $_POST['action'] == 'filter' && $_POST['cycle']) {
    $CycleId = intval($_POST['cycle']);
    $pf = getPeriodeFormationannee($CycleId);
    echo json_encode($pf);
    exit;
}

/**
 * Ajoute une période de formation pour une année scolaire.
 *
 * @param array $formData Les données de la période de formation.
 *
 * @return string Message de succès ou d'erreur.
 */
if (isset($_POST['action']) && $_POST['action'] == 'ajouterperiodedeformationannee') {

    parse_str($_POST['formData'], $formData);

    $formData = array_map(
        function ($value) {
            return $value === '' ? null : $value;
        },
        $formData
    );

    $res = ajouterperiodedeformationannee($formData);
    echo $res;
    exit;
}

/**
 * Modifie une période de formation pour une année scolaire.
 *
 * @param array $formData Les données de la période de formation.
 *
 * @return string Message de succès ou d'erreur.
 */
if (isset($_POST['action']) && $_POST['action'] == 'modifierperiodedeformationannee') {

    parse_str($_POST['formData'], $formData);

    $formData = array_map(
        function ($value) {
            return $value === '' ? null : $value;
        },
        $formData
    );

    $res = modifierperiodedeformationannee($formData);
    echo $res;
    exit;
}

/**
 * Supprime une période de formation pour une année scolaire.
 *
 * @param int $id L'ID de la période de formation à supprimer.
 *
 * @return string Message de succès ou d'erreur.
 */
if (isset($_POST['action']) && $_POST['action'] == 'supprimerperiodedeformationannee' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $res = supprimerperiodedeformationannee($id);
    echo $res;
    exit;
}

// Récupère les périodes de formation et les années distinctes
$periodeF = getPeriodeformation();
$years = getDistinctYears();

// Définit le template à utiliser
$template = './views/pages/periodedeformationannee.php';
