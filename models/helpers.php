<?php

require_once './models/connection.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Vérifie si l'utilisateur a accès à une fonctionnalité en fonction de son rôle.
 *
 * @param string $requiredRole Le rôle requis pour accéder à la fonctionnalité.
 *
 * @return bool True si l'utilisateur a accès, sinon False.
 */
function checkAccess($requiredRole)
{

    $roleHierarchy = [
        'administrateur' => 3,
        'responsable' => 2,
        'interne' => 1,
        'externe' => 0
    ];
    $userRole = $_SESSION['role'] ?? 'externe';
    if (!array_key_exists($requiredRole, $roleHierarchy) || !array_key_exists($userRole, $roleHierarchy)) {
        error_log("Invalid role provided: $requiredRole ou $userRole");
        return false;
    }
    return $roleHierarchy[$userRole] >= $roleHierarchy[$requiredRole];
}

/**
 * Vérifie si l'utilisateur peut modifier une UE.
 *
 * @param int $ueId L'ID de l'UE.
 *
 * @return bool True si l'utilisateur peut modifier l'UE, sinon False.
 */
function canModifyUE($ueId)
{

    $db = dbConnect();
    $userRole = $_SESSION['role'] ?? 'externe';
    $userId = $_SESSION['user_id'] ?? null;
    // Role-based access control
    // Administrators can modify all UEs
    if ($userRole === 'administrateur') {
        return true;
    }

    // Restricted roles cannot modify UEs
    if (in_array($userRole, ['interne', 'externe'])) {
        return false;
    }

    // User is not logged in
    if (!$userId) {
        return false;
    }

    // Combined query for direct and indirect responsibility
    $sql = "SELECT 1
            FROM syllabus.module_annee ue
            WHERE ue.idmoduleannee = :ueId AND ue.idresponsable = :userId
            UNION
            SELECT 1
            FROM syllabus.module_annee ue
            JOIN syllabus.groupematiereenseignee ge ON ue.idmoduleannee = ge.idmoduleannee
            JOIN syllabus.matiereenseignee ecue ON ge.idgroupematiereenseignee = ecue.idgroupematiereenseignee
            WHERE ue.idmoduleannee = :ueId AND ecue.idresponsable = :userId
            LIMIT 1";
    $query = $db->prepare($sql);
    $query->bindParam(":ueId", $ueId, PDO::PARAM_INT);
    $query->bindParam(":userId", $userId, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchColumn() ? true : false;
}

/**
 * Vérifie si la session de l'utilisateur a expiré.
 */
function checkSessionTimeout()
{


    // Exit if the user is not logged in
    if (!isset($_SESSION['user_id'])) {
        return;
    }
    // Fetch session timeout duration from the database
    $db = dbConnect();
    $query = $db->query("SELECT timeout_duration FROM session_config LIMIT 1");
    $timeoutDuration = $query->fetchColumn();
    // Convert timeout duration to seconds
    $timeoutDurationInSeconds = $timeoutDuration * 60;
    // Check if the session has expired
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeoutDurationInSeconds) {
        // Destroy the session if expired
        session_unset();
        session_destroy();
        header("Location: index.php?page=login");
        exit();
    }

    // Update the timestamp for the last activity
    $_SESSION['LAST_ACTIVITY'] = time();
}

/**
 * Génère le fil d'Ariane pour la navigation.
 *
 * @param string $page La page actuelle.
 * @param array $params Les paramètres de la page.
 *
 * @return array Le fil d'Ariane.
 */
function getBreadcrumbTrail($page, $params)
{

    $breadcrumbs = [];
    if ($page === "modifierue" && isset($params['id'])) {
        $ueName = getUENameById($params['id']);
        $breadcrumbs["Syllabus"] = "/?page=ue";
        $breadcrumbs['modifier ' . $ueName] = '/?page=modifierue&id=' . htmlspecialchars($params['id']);
    } elseif ($page === 'modifierecue' && isset($params['id'])) {
        $ue = getUEDetailsByECUEId($params['id']);
        $ecueName = getECUENameById($params['id']);
        $breadcrumbs["Syllabus"] = "/?page=ue";
        $breadcrumbs[$ue['libelle']] = '/?page=modifierue&id=' . htmlspecialchars($ue['id']);
        $breadcrumbs['modifier ' . $ecueName] = '/?page=modifierecue&id=' . htmlspecialchars($params['id']);
    } elseif ($page === 'ajouterecue' && isset($params['id'])) {
        $ueName = getUENameById($params['id']);
        $breadcrumbs["Syllabus"] = "/?page=ue";
        $breadcrumbs[$ueName] = '/?page=modifierue&id=' . htmlspecialchars($params['id']);
        $breadcrumbs["ajouter un ECUE"] = '#';
    } elseif ($page === 'modifiercompetence' && isset($params['id'])) {
        $code = getBCCode($params['id']);
        $breadcrumbs['Les blocs de compétences'] = '/?page=competence';
        $breadcrumbs['modifier' . $code] = '#';
    }

    return $breadcrumbs;
}

/**
 * Récupère le nom d'une UE par son ID.
 *
 * @param int $id L'ID de l'UE.
 *
 * @return string Le nom de l'UE.
 */
function getUENameById($id)
{

    $db = dbConnect();
    $stmt = $db->prepare("SELECT libelle FROM syllabus.module_annee WHERE idmoduleannee =  :id");
    $stmt->execute(['id' => $id]);
    $name = $stmt->fetchColumn();
    return $name ?: "Unknown Syllabus";
}

/**
 * Récupère le nom d'une ECUE par son ID.
 *
 * @param int $id L'ID de l'ECUE.
 *
 * @return string Le nom de l'ECUE.
 */
function getECUENameById($id)
{

    $db = dbConnect();
    $stmt = $db->prepare("SELECT libelle FROM syllabus.matiereenseignee WHERE idmatiereenseignee = :id");
    $stmt->execute(['id' => $id]);
    $name = $stmt->fetchColumn();
    return $name ?: "Unknown Matiere";
}

/**
 * Récupère les détails d'une UE par l'ID d'une ECUE.
 *
 * @param int $id L'ID de l'ECUE.
 *
 * @return array Les détails de l'UE.
 */
function getUEDetailsByECUEId($id)
{

    $db = dbConnect();
    $sql = "SELECT gme.idmoduleannee as id, ma.libelle
            FROM syllabus.matiereenseignee AS me
            INNER JOIN syllabus.groupematiereenseignee AS gme
                ON me.idgroupematiereenseignee = gme.idgroupematiereenseignee
            INNER JOIN syllabus.module_annee AS ma
                ON gme.idmoduleannee = ma.idmoduleannee
            WHERE me.idmatiereenseignee =  :id";
    $stmt = $db->prepare($sql);
    $stmt->execute(['id' => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

/**
 * Récupère le code d'un bloc de compétences par son ID.
 *
 * @param int $id L'ID du bloc de compétences.
 *
 * @return string Le code du bloc de compétences.
 */
function getBCCode($id)
{

    $db = dbConnect();
    $stmt = $db->prepare("SELECT code FROM syllabus.bloccompetence WHERE idbloccompetence =:id");
    $stmt->execute(['id' => $id]);
    $name = $stmt->fetchColumn();
    return $name ?: "un bloc";
}
