<?php

require_once './models/connection.php';

// Fonction pour obtenir les rôles
function getRoles()
{
    $query = dbConnect()->prepare('SELECT idrole, label FROM syllabus.role');
    $query->execute();

    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    $query->closeCursor();
    return $res;
}

// Fonction pour obtenir les utilisateurs
function getUsers()
{
    $query = dbConnect()->prepare("SELECT u.idutilisateur,u.username,u.email,u.idrole,r.label FROM syllabus.utilisateur u,syllabus.role r WHERE u.idrole = r.idrole;");

    $query->execute();

    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    $query->closeCursor();
    return $res;
}

// Fonction pour obtenir les utilisateurs par rôle
function getUserByRole($idrole)
{
    $query = dbConnect()->prepare("SELECT  u.idutilisateur,u.username,u.email,u.idrole,r.label FROM syllabus.utilisateur u,syllabus.role r WHERE u.idrole=:idrole AND u.idrole = r.idrole;");
    $query-> bindParam(":idrole", $idrole, PDO::PARAM_INT);
    $query->execute();

    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    $query->closeCursor();
    return $res;
}

// Fonction pour supprimer un responsable
function removeResponsable($id)
{
    $conn = dbConnect();
    try {
        $conn->beginTransaction();
        $sql1 = "UPDATE syllabus.module_annee SET idresponsable=null WHERE idresponsable=:id;";
        $query1 = $conn->prepare($sql1);
        $query1->bindParam(":id", $id, PDO::PARAM_INT);

        $query1->execute();

        $sql2 = "UPDATE syllabus.matiereenseignee SET idresponsable=null WHERE idresponsable=:id;";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(":id", $id, PDO::PARAM_INT);

        $query2->execute();

        $sql3 = "DELETE FROM syllabus.utilisateur WHERE idutilisateur=:id";
        $query3 = $conn->prepare($sql3);
        $query3->bindParam(":id", $id, PDO::PARAM_INT);

        $query3->execute();

        $conn->commit();
        return json_encode(['status' => 'success','message' => 'Utilisateur supprimé avec succès']);
    } catch (PDOException $e) {
        $conn->rollBack();
        return json_encode(['status' => 'error','message' => 'Erreur: ' . $e->getMessage()]);
    }
}

// Fonction pour éditer un responsable
function editResponsable($formData)
{
    try {
        $sql = "UPDATE syllabus.utilisateur SET idrole=:idrole WHERE idutilisateur=:id";
        $query = dbConnect()->prepare($sql);

        $query->bindParam(":id", $formData['idresponsable'], PDO::PARAM_INT);
        $query->bindParam(":idrole", $formData['editRole'], PDO::PARAM_INT);

        $query->execute();
        return json_encode(['status' => 'success','message' => 'Utilisateur modifié avec succès']);
    } catch (PDOException $e) {
        return json_encode(['status' => 'error','message' => 'Erreur: ' . $e->getMessage()]);
    }
}
