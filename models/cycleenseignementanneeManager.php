<?php

require_once './models/connection.php';

/**
 * Récupère toutes les années scolaires distinctes de la base de données,
 * triées par libellé (année scolaire).
 *
 * @return array Liste des années scolaires avec leurs IDs et libellés.
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
 * Récupère tous les cycles d'enseignement.
 *
 * @return array Liste des cycles d'enseignement avec leurs IDs et libellés.
 */
function getCyclesenseignement()
{

    $query = dbConnect()->prepare("SELECT idcycleenseignement,libelle FROM syllabus.cycleenseignement order by idcycleenseignement;");
    $query->execute();
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    return $res;
}

/**
 * Récupère les cycles d'enseignement associés à une année scolaire spécifique.
 *
 * @param int $id L'ID de l'année scolaire.
 *
 * @return array|string Liste des cycles d'enseignement
 * associés à l'année scolaire spécifiée.
 */
function getCycleenseignementanneeByYear($id)
{
    try {
        $sql = "SELECT * FROM syllabus.cycleenseignement_annee WHERE idanneescolaire=:id;";
        $query = dbConnect()->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return json_encode(['status' => 'error','message' => $e->getMessage()]);
    }
}

/**
 * Ajoute un cycle d'enseignement pour une année scolaire.
 *
 * @param array $data Données du cycle d'enseignement à ajouter.
 *
 * @return string Message de succès ou d'erreur.
 */
function ajouterCycleenseignementannee($data)
{

    try {
        $sql = "INSERT INTO syllabus.cycleenseignement_annee(idanneescolaire, idcycleenseignement,libelle,libellecourt,code) VALUES(:idanneescolaire, :idcycleenseignement,:libelle,:libellecourt,:code);";
        $query = dbConnect()->prepare($sql);
        $query->bindParam(':idanneescolaire', $data['idanneescolaire'], PDO::PARAM_INT);
        $query->bindParam(':idcycleenseignement', $data['idcycleenseignement'], PDO::PARAM_INT);
        $query->bindParam(':libelle', $data['libelle'], PDO::PARAM_STR);
        $query->bindParam(':libellecourt', $data['libellecourt'], PDO::PARAM_STR);
        $query->bindParam(':code', $data['code'], PDO::PARAM_STR);
        $query->execute();
        return json_encode(["status" => "success", "message" => "Le cycle d'enseignement a été ajouté avec succès."]);
    } catch (PDOException $e) {
        return json_encode(["status" => "error","message" => $e->getMessage()]);
    }
}


/**
 * Modifie un cycle d'enseignement pour une année scolaire.
 *
 * @param array $data Données du cycle d'enseignement à modifier.
 *
 * @return string Message de succès ou d'erreur.
 */
function modifierCycleenseignementannee($data)
{

    try {
        $sql = "UPDATE syllabus.cycleenseignement_annee SET idanneescolaire=:idanneescolaire, idcycleenseignement=:idcycleenseignement,libelle=:libelle,libellecourt=:libellecourt,code=:code WHERE idcycleenseignementannee=:idcycleenseignementannee;";
        $query = dbConnect()->prepare($sql);
        $query->bindParam(':idcycleenseignementannee', $data['idcycleenseignementannee'], PDO::PARAM_INT);
        $query->bindParam(':idanneescolaire', $data['idanneescolaire'], PDO::PARAM_INT);
        $query->bindParam(':idcycleenseignement', $data['idcycleenseignement'], PDO::PARAM_INT);
        $query->bindParam(':libelle', $data['libelle'], PDO::PARAM_STR);
        $query->bindParam(':libellecourt', $data['libellecourt'], PDO::PARAM_STR);
        $query->bindParam(':code', $data['code'], PDO::PARAM_STR);
        $query->execute();
        return json_encode(["status" => "success", "message" => "Le cycle d'enseignement a été modifié avec succès."]);
    } catch (PDOException $e) {
        return json_encode(["status" => "error","message" => $e->getMessage()]);
    }
}

/**
 * Supprime un cycle d'enseignement pour une année scolaire,
 *  y compris toutes les données associées (modules, périodes de formation).
 *
 * @param int $id L'ID du cycle d'enseignement à supprimer.
 *
 * @return string Message de succès ou d'erreur.
 */
function supprimerCycleenseignementannee($id)
{

    $conn = dbConnect();
    try {
        $conn->beginTransaction();
        // supprimer les modules
        $sql1 = "SELECT idmoduleannee from moduleperiodeformation_annee where idperiodeformationannee in (
	            select idperiodeformationannee from periodeformation_annee where idcycleenseignementannee = :id);";
        $query1 = $conn->prepare($sql1);
        $query1->bindParam(":id", $id, PDO::PARAM_INT);
        $query1->execute();
        $modules = $query1->fetchAll(PDO::FETCH_ASSOC);
        $query1->closeCursor();
        foreach ($modules as $module) {
            deleteUE($module['idmoduleannee']);
        }

        // supprimer les periodes de formation
        $sql2 = "DELETE FROM syllabus.periodeformation_annee where idcycleenseignementannee = :id;";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(":id", $id, PDO::PARAM_INT);
        $query2->execute();
        // supprimer le cycle
        $sql3 = "DELETE FROM syllabus.cycleenseignement_annee where idcycleenseignementannee = :id;";
        $query3 = $conn->prepare($sql3);
        $query3->bindParam(":id", $id, PDO::PARAM_INT);
        $query3->execute();
        $conn->commit();
        return json_encode(["status" => "success", "message" => "Cycle d'enseignement supprimé avec succès !"]);
    } catch (PDOException $e) {
        return json_encode(["status" => "error","message" => $e->getMessage()]);
    }
}

/**
 * Supprime toutes les unités d'enseignement (UE)
 * associées à un module d'année scolaire.
 *
 * @param int $id L'ID du module à supprimer.
 *
 * @return string Message de succès ou d'erreur.
 */
function deleteUE($id)
{

    $conn = dbConnect();
    try {
        $conn->beginTransaction();
        ;

        // supprimer les matieres enseignées
        $sql2 = "DELETE FROM syllabus.matiereenseignee where idgroupematiereenseignee in (
               select idgroupematiereenseignee from groupematiereenseignee where idmoduleannee =:id
                );";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(":id", $id, PDO::PARAM_INT);
        $query2->execute();
        // supprimer les groupes de matieres enseignées
        $sql3 = "DELETE FROM syllabus.groupematiereenseignee where idmoduleannee =:id;";
        $query3 = $conn->prepare($sql3);
        $query3->bindParam(":id", $id, PDO::PARAM_INT);
        $query3->execute();
        // supprimer les compétences
        $sql4 = "DELETE FROM syllabus.modulecompetence_annee where idmodulebloccompetenceannee in (
                select idmodulebloccompetenceannee from modulebloccompetence_annee where idmoduleannee =:id
                );";
        $query4 = $conn->prepare($sql4);
        $query4->bindParam(":id", $id, PDO::PARAM_INT);
        $query4->execute();
        // supprimer les blocs de compétences
        $sql5 = "DELETE FROM syllabus.modulebloccompetence_annee where idmoduleannee =:id;";
        $query5 = $conn->prepare($sql5);
        $query5->bindParam(":id", $id, PDO::PARAM_INT);
        $query5->execute();
        //supprimer periodeformation_annee
        $sql7 = "DELETE FROM syllabus.moduleperiodeformation_annee where idmoduleannee =:id;";
        $query7 = $conn->prepare($sql7);
        $query7->bindParam(":id", $id, PDO::PARAM_INT);
        $query7->execute();
        // supprimer moduledepartement_annee
        $sql8 = "DELETE FROM syllabus.moduledepartement_annee where idmoduleannee =:id;";
        $query8 = $conn->prepare($sql8);
        $query8->bindParam(":id", $id, PDO::PARAM_INT);
        $query8->execute();
        // supprimer moduleoption_annee
        $sql9 = "DELETE FROM syllabus.moduleoption_annee where idmoduleannee =:id;";
        $query9 = $conn->prepare($sql9);
        $query9->bindParam(":id", $id, PDO::PARAM_INT);
        $query9->execute();
        // supprimer le module
        $sql7 = "DELETE FROM syllabus.module_annee where idmoduleannee =:id;";
        $query7 = $conn->prepare($sql7);
        $query7->bindParam(":id", $id, PDO::PARAM_INT);
        $query7->execute();
        $conn->commit();
        return json_encode(["status" => "success", "message" => "Data deleted successfully"]);
    } catch (Exception $e) {
        $conn->rollBack();
        return json_encode(["status" => "error","message" => $e->getMessage()]);
    }
}
