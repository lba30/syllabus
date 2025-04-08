<?php

require_once './models/connection.php';

// Fonction pour obtenir un utilisateur par son ID
function getUserById($userId)
{
    $db = dbConnect();

    $sql = "SELECT u.idutilisateur, u.username, u.email, r.label AS role
            FROM syllabus.utilisateur u
            JOIN syllabus.role r ON u.idrole = r.idrole
            WHERE u.idutilisateur = :userId";

    $query = $db->prepare($sql);
    $query->bindParam(":userId", $userId, PDO::PARAM_INT);
    $query->execute();

    return $query->fetch(PDO::FETCH_ASSOC);
}
