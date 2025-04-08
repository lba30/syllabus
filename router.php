<?php

require_once './config/routes.php';
require_once './models/helpers.php';

$availableRouteNames = array_keys(AVAILABLE_ROUTES);

checkSessionTimeout();

if (isset($_GET['page']) && in_array($_GET['page'], $availableRouteNames, true)) {
    $controller = AVAILABLE_ROUTES[$_GET['page']];

    if (array_key_exists($_GET['page'], PROTECTED_ROUTES) && !checkAccess(PROTECTED_ROUTES[$_GET['page']])) {
        header("Location: no_access.php");
        exit();
    }

    // Special case for 'modifierue' to include additional logic
    if ($_GET['page'] === 'modifierue' && isset($_GET['id'])) {
        // Use custom access logic for modifying a UE
        if (!canModifyUE($_GET['id'])) {
            header("Location: no_access.php");
            exit();
        }
    }
    $breadcrumbs = getBreadcrumbTrail($_GET['page'], $_GET);
} else {
    $controller = DEFAULT_ROUTE;
}


require './controllers/' . $controller;
