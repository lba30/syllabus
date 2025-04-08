<?php

require_once './models/configManager.php';
// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['userNotLoggedIn' => true]);
    exit();
}

// Action pour obtenir les informations de session
if ($_POST['action'] === 'getSessionInfo') {
    $sessionTime = getSessionTimeout();
    echo json_encode(['sessionTime' => $sessionTime]);
    exit();
}

// Action pour étendre la session
if ($_POST['action'] === 'extendSession') {
    $_SESSION['LAST_ACTIVITY'] = time();
    echo json_encode(['sessionExtended' => true]);
    exit();
}

// Action pour détruire la session expirée
if ($_POST['action'] === 'sessionExpired') {
    session_unset();
    session_destroy();
    echo json_encode(['sessionExpired' => true]);
    exit();
}
