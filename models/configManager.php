<?php

require_once './models/connection.php';

/**
 * Fonction pour obtenir la durée de la session
 *
 * @return int La durée de la session en minutes
 */
function getSessionTimeout()
{
    $db = dbConnect();
    $query = $db->query("SELECT timeout_duration FROM session_config LIMIT 1");
    return $query->fetchColumn();
}

/**
 * Fonction pour mettre à jour la durée de la session
 *
 * @param int $timeout La nouvelle durée de la session en minutes
 *
 * @return void
 */
function updateSessionTimeout($timeout)
{
    $db = dbConnect();
    $stmt = $db->prepare("UPDATE session_config SET timeout_duration = :timeout WHERE id = 1");
    $stmt->bindParam(':timeout', $timeout, PDO::PARAM_INT);
    $stmt->execute();
}
