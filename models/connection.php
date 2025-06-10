<?php

require_once './config/db.php';

/**
 * Établit une connexion à la base de données en utilisant PDO.
 *
 * @return PDO L'objet de connexion à la base de données.
 */
function dbConnect(): PDO
{
    if ($_SERVER['HTTP_HOST'] === 'syllabus.mines-ales.fr') {
        $CURRENT_CONFIG = DB_CONFIG_PROD;
    }
    elseif ($_SERVER['HTTP_HOST'] === 'syllabus-dev.mines-ales.fr') {
        $CURRENT_CONFIG = DB_CONFIG_PREPROD;
    } elseif ($_SERVER['HTTP_HOST'] === 'localhost') {
        $CURRENT_CONFIG = DB_CONFIG_DEV;
    }
    
    $db = new PDO(
        "pgsql:host=" . $CURRENT_CONFIG['host'] . ";port=" . $CURRENT_CONFIG['port'] . ";dbname=" . $CURRENT_CONFIG['dbname'] . ";sslmode=prefer",
        $CURRENT_CONFIG['username'],
        $CURRENT_CONFIG['password']
    );

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
}
