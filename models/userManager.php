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

/**
 * Récupère les responsables (administrateurs et responsables).
 *
 * @return array La liste des responsables.
 */
function getResponsables(): array
{
    // Récupérer les responsables (administrateurs et responsables)
    $sql = "SELECT u.idutilisateur as id, username as nomresponsable, email as emailresponsable FROM syllabus.utilisateur u
            JOIN syllabus.role r ON u.idrole = r.idrole
            WHERE r.label IN ('administrateur', 'responsable');";
    $query = dbConnect()->prepare(query: $sql);
    $query->execute();
    $res = $query->fetchAll(mode: PDO::FETCH_ASSOC);
    $query->closeCursor();
    return $res;
}
