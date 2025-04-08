<?php

require_once './models/responsableManager.php';
// Vérifie si l'action est de modifier un responsable
if (isset($_POST['action']) && $_POST['action'] == 'modifierresponsable') {

    parse_str($_POST['formData'], $formData);

    $formData = array_map(
        function ($value) {
            return $value === '' ? null : $value;
        },
        $formData
    );

    $res = editResponsable($formData);
    echo json_encode($res);
    exit;
}

// Vérifie si l'action est de supprimer un responsable et si l'ID est fourni
if (isset($_POST['action']) && $_POST['action'] == 'supprimerresponsable' && isset($_POST['id'])) {
    $res = removeResponsable($_POST['id']);
    echo $res;
    exit;
}

// Vérifie si l'action est de filtrer
if (isset($_POST['action']) && $_POST['action'] == 'filter') {
    if ($_POST['role'] !== "") {
        $roleId = intval($_POST['role']);
        $users = getUserByRole($roleId);
    } else {
        $users = getUsers();
    }
    echo json_encode($users);
    exit;
}

// Récupère les rôles
$roles = getRoles();
// Définit le template à utiliser
$template = './views/pages/responsable.php';
