<?php

require_once './config/db.php';

/**
 * Établit une connexion à la base de données en utilisant PDO.
 *
 * @return PDO L'objet de connexion à la base de données.
 */
function dbConnect()
{
    $db = new PDO(
        "pgsql:host=" . DB_CONFIG['host'] . ";port=" . DB_CONFIG['port'] . ";dbname=" . DB_CONFIG['dbname'] . ";sslmode=prefer",
        DB_CONFIG['username'],
        DB_CONFIG['password']
    );

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
}
