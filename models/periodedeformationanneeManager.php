<?php

require_once './models/connection.php';

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
 * Récupère les cycles pour une année donnée.
 *
 * @param int $yearId L'ID de l'année scolaire.
 *
 * @return array Les cycles pour l'année donnée.
 */
function getCycles($yearId)
{
    $sql = "SELECT idcycleenseignementannee as id,libelle FROM syllabus.cycleenseignement_annee
	where idanneescolaire = :yearId
    ORDER BY idcycleenseignementannee ASC";

    $query = dbConnect()->prepare($sql);
    $query->bindParam(":yearId", $yearId, PDO::PARAM_INT);
    $query->execute();
    $cycles = $query->fetchAll(PDO::FETCH_ASSOC);
    $query->closeCursor();
    return $cycles;
}

/**
 * Récupère les périodes de formation pour un cycle donné.
 *
 * @param int $cycleId L'ID du cycle d'enseignement.
 *
 * @return array Les périodes de formation pour le cycle donné.
 */
function getPeriodeFormationannee($cycleId)
{
    $sql = "SELECT * FROM syllabus.periodeformation_annee where idcycleenseignementannee =:cycleId";

    $query = dbConnect()->prepare($sql);
    $query->bindParam(":cycleId", $cycleId, PDO::PARAM_INT);
    $query->execute();
    $pf = $query->fetchAll(PDO::FETCH_ASSOC);
    $query->closeCursor();
    return $pf;
}

/**
 * Récupère toutes les périodes de formation.
 *
 * @return array Les périodes de formation.
 */
function getPeriodeFormation()
{
    $sql = "SELECT idperiodeformation,libelle FROM syllabus.periodeformation";

    $query = dbConnect()->prepare($sql);
    $query->execute();
    $pf = $query->fetchAll(PDO::FETCH_ASSOC);
    $query->closeCursor();
    return $pf;
}

/**
 * Ajoute une période de formation pour une année scolaire.
 *
 * @param array $data Les données de la période de formation.
 *
 * @return string Message de succès ou d'erreur.
 */
function ajouterperiodedeformationannee($data)
{
    try {
        $sql = "INSERT INTO syllabus.periodeformation_annee(libelle,libellecourt,idperiodeformation,code,idcycleenseignementannee) VALUES(:libelle,:libellecourt,:idperiodeformation,:code,:idcycleenseignementannee);";

        $query = dbConnect()->prepare($sql);
        $query->bindParam(":libelle", $data['libelle'], PDO::PARAM_STR);
        $query->bindParam(":libellecourt", $data['libellecourt'], PDO::PARAM_STR);
        $query->bindParam(":code", $data['code'], PDO::PARAM_INT);
        $query->bindParam(":idperiodeformation", $data['idperiodeformation'], PDO::PARAM_INT);
        $query->bindParam(":idcycleenseignementannee", $data['idcycleenseignementannee'], PDO::PARAM_INT);
        $query->execute();

        return json_encode(['status' => 'success','message' => 'La période de formation pour l\'année scolaire a été ajoutée avec succès.']);
    } catch (PDOException $e) {
        return json_encode(['status' => 'error','message' => $e->getMessage()]);
    }
}

/**
 * Modifie une période de formation pour une année scolaire.
 *
 * @param array $data Les données de la période de formation.
 *
 * @return string Message de succès ou d'erreur.
 */
function modifierperiodedeformationannee($data)
{
    try {
        $sql = "UPDATE syllabus.periodeformation_annee SET libelle=:libelle,libellecourt=:libellecourt,idperiodeformation=:idperiodeformation,code=:code WHERE idperiodeformationannee=:idperiodeformationannee ;";

        $query = dbConnect()->prepare($sql);
        $query->bindParam(":libelle", $data['libelle'], PDO::PARAM_STR);
        $query->bindParam(":libellecourt", $data['libellecourt'], PDO::PARAM_STR);
        $query->bindParam(":code", $data['code'], PDO::PARAM_INT);
        $query->bindParam(":idperiodeformation", $data['idperiodeformation'], PDO::PARAM_INT);
        $query->bindParam(":idperiodeformationannee", $data['idperiodeformationannee'], PDO::PARAM_INT);
        $query->execute();

        return json_encode(['status' => 'success','message' => 'La période de formation pour l\'année scolaire a été modifiée avec succès.']);
    } catch (PDOException $e) {
        return json_encode(['status' => 'error','message' => $e->getMessage()]);
    }
}

/**
 * Supprime une période de formation pour une année scolaire, y compris toutes les données associées (modules, périodes de formation).
 *
 * @param int $id L'ID de la période de formation à supprimer.
 *
 * @return string Message de succès ou d'erreur.
 */
function supprimerperiodedeformationannee($id)
{
    $conn = dbConnect();
    try {
        $conn->beginTransaction();

        // supprimer les modules
        $sql1 = "SELECT idmoduleannee from moduleperiodeformation_annee where idperiodeformationannee =:id;";
        $query1 = $conn->prepare($sql1);
        $query1->bindParam(":id", $id, PDO::PARAM_INT);
        $query1->execute();
        $modules = $query1->fetchAll(PDO::FETCH_ASSOC);
        $query1->closeCursor();

        foreach ($modules as $module) {
            deleteUE($module['idmoduleannee']);
        }

        // supprimer les periodes de formation
        $sql2 = "DELETE FROM syllabus.periodeformation_annee where idperiodeformationannee =:id;";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(":id", $id, PDO::PARAM_INT);
        $query2->execute();

        $conn->commit();
        return json_encode(["status" => "success", "message" => "La période de formation pour l'année scolaire a été supprimée avec succès."]);
    } catch (PDOException $e) {
        return json_encode(["status" => "error","message" => $e->getMessage()]);
    }
}

/**
 * Supprime une unité d'enseignement (UE) et toutes les données associées.
 *
 * @param int $id L'ID de l'unité d'enseignement à supprimer.
 *
 * @return string Message de succès ou d'erreur.
 */
function deleteUE($id)
{
    $conn = dbConnect();
    try {
        $conn->beginTransaction();

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
