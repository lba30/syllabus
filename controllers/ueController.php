<?php

ob_start();
require_once './models/ueManager.php';
require_once   './vendor/autoload.php';
// Récupère les années distinctes
$years = getDistinctYears();
// Vérifie si l'action est de filtrer les UE
if (isset($_POST['action']) && $_POST['action'] == 'filter') {
    // Récupère les paramètres de filtrage
    $yearId = intval($_POST['year']) ?? null;
    $cycleId = intval($_POST['cycle']) ?? null;
    $periodeId = intval($_POST['semestre']) ?? null;
    $departementId = intval($_POST['departement']) ?? null;
    // Filtre les UE en fonction des paramètres
    if ($cycleId) {
        if ($departementId) {
            $ues = $periodeId ? getALLUEByDepartementAndPeriodeFormation($cycleId, $departementId, $periodeId) :  getALLUEByDepartementAndPeriodeFormation($cycleId, $departementId);
        } else {
            $ues = $periodeId ? getALLUEByCycleAndPeriodeFormation($cycleId, $periodeId) :  getALLUEByCycleAndPeriodeFormation($cycleId);
        }
    } else {
        $ues = $yearId ? getAllUEByYear($yearId) : [] ;
    }
    // Retourne les résultats en JSON
    echo json_encode($ues);
    exit;
}

// Récupère les options de filtre pour les cycles
if (isset($_POST['action']) && $_POST['action'] == 'getCycleFilterOptions') {
    $yearId = $_POST['year'];
    $options = getCycles($yearId);
    echo json_encode($options);
    exit;
}

// Récupère les options de filtre pour les semestres
if (isset($_POST['action']) && $_POST['action'] == 'getSemestreOptions') {
    $cycleId = $_POST['cycle'];
    $options = getSemestres($cycleId);
    echo json_encode($options);
    exit;
}

// Récupère les options de filtre pour les départements
if (isset($_POST['action']) && $_POST['action'] == 'getDepartementOptions') {
    $cycleId = $_POST['cycle'];
    $options = getDepartemnts($cycleId);
    echo json_encode($options);
    exit;
}

// Supprime une UE si l'utilisateur a les droits d'administrateur
if (isset($_POST['action']) && $_POST['action'] === 'supprimerue' && isset($_POST['id'])) {
    if (checkAccess('administrateur')) {
        $id = intval($_POST['id']);
        $res = deleteUE($id);
        echo json_encode($res);
    }
    exit;
}

// Génère un PDF pour une UE spécifique
if (isset($_GET['action']) && $_GET['action'] === 'imprimer' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $ue = getUEDetails($id);
    $showRespoInfo = checkAccess('interne') && isset($_GET['respoInfo']) && $_GET['respoInfo'] === 'true';
    ob_start();
    include "./views/syllabus_template.php";
    $html = ob_get_clean();
    $stylesheet = file_get_contents("./public/css/syllabus.css");
    // Corrige les chemins des images dans le CSS
    $pattern = "/url\('\.\.\/img\/([^\)]+)'\)/";
    $replacement = "url('./public/img/$1')";
    $stylesheet = preg_replace('/url\([\'"]?\.\.(.+?)[\'"]?\)/i', 'url("' . './public' . '$1")', $stylesheet);
    ob_end_clean();
    try {
        $mpdf = new \Mpdf\Mpdf(
            [
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'orientation' => 'L',
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 5,
            'margin_bottom' => 15,
            'margin_footer' => 2,
            ]
        );
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        // Ajoute le pied de page au PDF
        $mpdf->SetHTMLFooter($footer);
        $mpdf->WriteHTML($html);
        // Définit les en-têtes pour le téléchargement du fichier
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="syllabus_formation_ingenieur.pdf"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');
        // Génère le PDF et l'envoie au navigateur
        $mpdf->Output('syllabus_formation_ingenieur.pdf', 'I');
        exit();
    } catch (Exception $e) {
        echo "Une erreur est survenue lors de la génération du PDF: " . $e->getMessage();
    }
}

// Définit le template à utiliser
$template = './views/pages/ue.php';
