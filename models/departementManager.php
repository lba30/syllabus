<?php

require_once './models/connection.php';

/**
 * Récupère tous les départements.
 *
 * @return array Les départements.
 */
function getDepartements()
{

    $query = dbConnect()->prepare("SELECT * FROM syllabus.departement ;");
    $query->execute();
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    return $res;
}

/**
 * Ajoute un département.
 *
 * @param array $data Les données du département.
 *
 * @return string Message de succès ou d'erreur.
 */
function ajouterDepartement($data)
{

    try {
        $sql = "INSERT INTO syllabus.departement(libelle,actif) VALUES(:libelle,:actif);";
        $query = dbConnect()->prepare($sql);
        $query->bindParam(":libelle", $data['libelle'], PDO::PARAM_STR);
        $query->bindParam(":actif", $data['actif'], PDO::PARAM_INT);
        $query->execute();
        return json_encode(['status' => 'success','message' => 'Le département a été ajouté avec succès.']);
    } catch (PDOException $e) {
        return json_encode(['status' => 'error','message' => $e->getMessage()]);
    }
}

/**
 * Ajoute un département pour une année scolaire.
 *
 * @param array $data Les données du département.
 *
 * @return string Message de succès ou d'erreur.
 */
function ajouterDepartementannee($data)
{

    try {
        $sql = "INSERT INTO syllabus.departement_annee(idanneescolaire,iddepartement,libelle,code) VALUES(:idanneescolaire,:iddepartement,:libelle,:code);";
        $query = dbConnect()->prepare($sql);
        $query->bindParam(":idanneescolaire", $data['idanneescolaire'], PDO::PARAM_INT);
        $query->bindParam(":iddepartement", $data['iddepartement'], PDO::PARAM_INT);
        $query->bindParam(":libelle", $data['libelle'], PDO::PARAM_STR);
        $query->bindParam(":code", $data['code'], PDO::PARAM_STR);
        $query->execute();
        return json_encode(['status' => 'success','message' => 'Le département a été ajouté avec succès.']);
    } catch (PDOException $e) {
        return json_encode(['status' => 'error','message' => $e->getMessage()]);
    }
}

/**
 * Modifie un département.
 *
 * @param array $data Les données du département.
 *
 * @return string Message de succès ou d'erreur.
 */
function modifierDepartement($data)
{

    try {
        $sql = "UPDATE syllabus.departement SET libelle=:libelle , actif=:actif WHERE iddepartement=:iddepartement;";
        $query = dbConnect()->prepare($sql);
        $query->bindParam(":libelle", $data['libelle'], PDO::PARAM_STR);
        $query->bindParam(":actif", $data['actif'], PDO::PARAM_INT);
        $query->bindParam(":iddepartement", $data['iddepartement'], PDO::PARAM_INT);
        $query->execute();
        return json_encode(['status' => 'success','message' => 'Le département a été modifié avec succès.']);
    } catch (PDOException $e) {
        return json_encode(['status' => 'error','message' => $e->getMessage()]);
    }
}

/**
 * Modifie un département pour une année scolaire.
 *
 * @param array $data Les données du département.
 *
 * @return string Message de succès ou d'erreur.
 */
function modifierDepartementannee($data)
{

    try {
        $sql = "UPDATE syllabus.departement_annee SET idanneescolaire=:idanneescolaire,iddepartement=:iddepartement,libelle=:libelle , code=:code WHERE iddepartementannee=:iddepartementannee;";
        $query = dbConnect()->prepare($sql);
        $query->bindParam(":idanneescolaire", $data['idanneescolaire'], PDO::PARAM_INT);
        $query->bindParam(":iddepartement", $data['iddepartement'], PDO::PARAM_INT);
        $query->bindParam(":libelle", $data['libelle'], PDO::PARAM_STR);
        $query->bindParam(":code", $data['code'], PDO::PARAM_STR);
        $query->bindParam(":iddepartementannee", $data['iddepartementannee'], PDO::PARAM_INT);
        $query->execute();
        return json_encode(['status' => 'success','message' => 'Le département a été modifié avec succès.']);
    } catch (PDOException $e) {
        return json_encode(['status' => 'error','message' => $e->getMessage()]);
    }
}

/**
 * Récupère toutes les années distinctes.
 *
 * @return array Les années distinctes.
 */
function getDistinctYears()
{

    $sql = "SELECT idanneescolaire,libelle
    FROM syllabus.anneescolaire ans
    ORDER BY ans.libelle DESC";
    $query = dbConnect()->prepare($sql);
    $query->execute();
    $years = $query->fetchAll(PDO::FETCH_ASSOC);
    $query->closeCursor();
    return $years;
}

/**
 * Récupère les départements pour une année donnée.
 *
 * @param int $yearId L'ID de l'année scolaire.
 *
 * @return array Les départements pour l'année donnée.
 */
function getDepartementByYear($yearId)
{

    $sql = "SELECT * FROM syllabus.departement_annee where idanneescolaire = :yearId ";
    $query = dbConnect()->prepare($sql);
    $query->bindParam(":yearId", $yearId, PDO::PARAM_INT);
    $query->execute();
    $departements = $query->fetchAll(PDO::FETCH_ASSOC);
    $query->closeCursor();
    return $departements;
}

/**
 * Supprime un département pour une année scolaire.
 *
 * @param int $id L'ID du département à supprimer.
 *
 * @return string Message de succès ou d'erreur.
 */
function supprimerDepartementAnnee($id)
{

    $conn = dbConnect();
    try {
        $conn->beginTransaction();
        $sql1 = "DELETE FROM syllabus.moduledepartement_annee WHERE iddepartementannee = :iddepartementannee;";
        $query1 = dbConnect()->prepare($sql1);
        $query1->bindParam(":iddepartementannee", $id, PDO::PARAM_INT);
        $query1->execute();
        $sql2 = "DELETE FROM syllabus.departement_annee WHERE iddepartementannee = :iddepartementannee;";
        $query2 = dbConnect()->prepare($sql2);
        $query2->bindParam(":iddepartementannee", $id, PDO::PARAM_INT);
        $query2->execute();
        $conn->commit();
        return json_encode(['status' => 'success','message' => 'Le département a été supprimé avec succès.']);
    } catch (PDOException $e) {
        $conn->rollBack();
        return json_encode(['status' => 'error','message' => $e->getMessage()]);
    }
}
