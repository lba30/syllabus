<?php

require_once './models/connection.php';
require_once './models/helpers.php';

/**
 * Récupère toutes les UE par année
 * @param int $yearId - L'identifiant de l'année
 *
 * @return array - Les UE de l'année spécifiée
 */
function getAllUEByYear($yearId)
{
    $db = dbConnect();
    $userRole = $_SESSION['role'] ?? 'externe';
    $userId = $_SESSION['user_id'] ?? null;
    // Si l'utilisateur est un administrateur, il peut modifier toutes les UE
    if ($userRole === 'administrateur') {
        $sql = "SELECT me.idmoduleannee, me.libelle, me.code, TRUE AS hasAccess
                FROM syllabus.module_annee me
                JOIN syllabus.anneescolaire ans ON me.idanneescolaire = ans.idanneescolaire
                WHERE ans.idanneescolaire = :yearId
                ORDER BY me.code;";
    } else {
        $sql = "SELECT me.idmoduleannee, me.libelle, me.code,
                       (CASE
                            WHEN me.idresponsable = :userId THEN TRUE
                            WHEN EXISTS (
                                SELECT 1
                                FROM syllabus.groupematiereenseignee ge
                                JOIN syllabus.matiereenseignee ecue ON ge.idgroupematiereenseignee = ecue.idgroupematiereenseignee
                                WHERE ge.idmoduleannee = me.idmoduleannee AND ecue.idresponsable = :userId
                            ) THEN TRUE
                            ELSE FALSE
                       END) AS hasAccess
                FROM syllabus.module_annee me
                JOIN syllabus.anneescolaire ans ON me.idanneescolaire = ans.idanneescolaire
                WHERE ans.idanneescolaire = :yearId
                ORDER BY me.code;";
    }

    $query = $db->prepare($sql);
    $query->bindParam(":yearId", $yearId, PDO::PARAM_INT);
    if ($userRole !== 'administrateur') {
        $query->bindParam(":userId", $userId, PDO::PARAM_INT);
    }
    $query->execute();

    return $query->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupère toutes les UE par cycle et période de formation
 * @param int $cycleId - L'identifiant du cycle
 * @param int|null $periodeId - L'identifiant de la période (optionnel)
 *
 * @return array - Les UE du cycle et de la période spécifiés
 */
function getALLUEByCycleAndPeriodeFormation($cycleId, $periodeId = null)
{
    $db = dbConnect();
    $userId = $_SESSION['user_id'] ?? null;
    $userRole = $_SESSION['role'] ?? 'externe';

    // Si l'utilisateur est un administrateur, il a accès à toutes les UE
    if ($userRole === 'administrateur') {
        if ($periodeId === null) {
            $sql = "SELECT ma.idmoduleannee, ma.libelle, ma.code, TRUE AS hasAccess
                    FROM syllabus.periodeformation_annee pfa
                    JOIN syllabus.moduleperiodeformation_annee mpfa ON pfa.idperiodeformationannee = mpfa.idperiodeformationannee
                    JOIN syllabus.module_annee ma ON mpfa.idmoduleannee = ma.idmoduleannee
                    WHERE pfa.idcycleenseignementannee = :cycleId";
            $query = $db->prepare($sql);
            $query->bindParam(":cycleId", $cycleId, PDO::PARAM_INT);
        } else {
            $sql = "SELECT ma.idmoduleannee, ma.libelle, ma.code, TRUE AS hasAccess
                    FROM syllabus.periodeformation_annee pfa
                    JOIN syllabus.moduleperiodeformation_annee mpfa ON pfa.idperiodeformationannee = mpfa.idperiodeformationannee
                    JOIN syllabus.module_annee ma ON mpfa.idmoduleannee = ma.idmoduleannee
                    WHERE pfa.idcycleenseignementannee = :cycleId AND pfa.idperiodeformation = :periodeId";
            $query = $db->prepare($sql);
            $query->bindParam(":cycleId", $cycleId, PDO::PARAM_INT);
            $query->bindParam(":periodeId", $periodeId, PDO::PARAM_INT);
        }
    } else {
        // Pour les non-administrateurs, calculer hasAccess en fonction de la responsabilité
        if ($periodeId === null) {
            $sql = "SELECT ma.idmoduleannee, ma.libelle, ma.code,
                           (CASE
                                WHEN ma.idresponsable = :userId THEN TRUE
                                WHEN EXISTS (
                                    SELECT 1
                                    FROM syllabus.groupematiereenseignee ge
                                    JOIN syllabus.matiereenseignee ecue ON ge.idgroupematiereenseignee = ecue.idgroupematiereenseignee
                                    WHERE ge.idmoduleannee = ma.idmoduleannee AND ecue.idresponsable = :userId
                                ) THEN TRUE
                                ELSE FALSE
                           END) AS hasAccess
                    FROM syllabus.periodeformation_annee pfa
                    JOIN syllabus.moduleperiodeformation_annee mpfa ON pfa.idperiodeformationannee = mpfa.idperiodeformationannee
                    JOIN syllabus.module_annee ma ON mpfa.idmoduleannee = ma.idmoduleannee
                    WHERE pfa.idcycleenseignementannee = :cycleId";
            $query = $db->prepare($sql);
            $query->bindParam(":cycleId", $cycleId, PDO::PARAM_INT);
            $query->bindParam(":userId", $userId, PDO::PARAM_INT);
        } else {
            $sql = "SELECT ma.idmoduleannee, ma.libelle, ma.code,
                           (CASE
                                WHEN ma.idresponsable = :userId THEN TRUE
                                WHEN EXISTS (
                                    SELECT 1
                                    FROM syllabus.groupematiereenseignee ge
                                    JOIN syllabus.matiereenseignee ecue ON ge.idgroupematiereenseignee = ecue.idgroupematiereenseignee
                                    WHERE ge.idmoduleannee = ma.idmoduleannee AND ecue.idresponsable = :userId
                                ) THEN TRUE
                                ELSE FALSE
                           END) AS hasAccess
                    FROM syllabus.periodeformation_annee pfa
                    JOIN syllabus.moduleperiodeformation_annee mpfa ON pfa.idperiodeformationannee = mpfa.idperiodeformationannee
                    JOIN syllabus.module_annee ma ON mpfa.idmoduleannee = ma.idmoduleannee
                    WHERE pfa.idcycleenseignementannee = :cycleId AND pfa.idperiodeformation = :periodeId";
            $query = $db->prepare($sql);
            $query->bindParam(":cycleId", $cycleId, PDO::PARAM_INT);
            $query->bindParam(":periodeId", $periodeId, PDO::PARAM_INT);
            $query->bindParam(":userId", $userId, PDO::PARAM_INT);
        }
    }

    $query->execute();
    $ues = $query->fetchAll(PDO::FETCH_ASSOC);

    $query->closeCursor();
    return $ues;
}

/**
 * Récupère toutes les UE par département et période de formation
 * @param int $cycleId - L'identifiant du cycle
 * @param int $departementId - L'identifiant du département
 * @param int|null $periodeId - L'identifiant de la période (optionnel)
 *
 * @return array - Les UE du département et de la période spécifiés
 */
function getALLUEByDepartementAndPeriodeFormation($cycleId, $departementId, $periodeId = null)
{
    $db = dbConnect();
    $userId = $_SESSION['user_id'] ?? null;
    $userRole = $_SESSION['role'] ?? 'externe';

    // Si l'utilisateur est un administrateur, il a accès à toutes les UE
    if ($userRole === 'administrateur') {
        if ($periodeId === null) {
            $sql = "SELECT ma.idmoduleannee, ma.libelle, ma.code, TRUE AS hasAccess
                    FROM syllabus.periodeformation_annee pfa
                    JOIN syllabus.moduleperiodeformation_annee mpfa ON pfa.idperiodeformationannee = mpfa.idperiodeformationannee
                    JOIN syllabus.module_annee ma ON mpfa.idmoduleannee = ma.idmoduleannee
                    JOIN syllabus.moduledepartement_annee mda ON ma.idmoduleannee = mda.idmoduleannee
                    JOIN syllabus.departement_annee da ON mda.iddepartementannee = da.iddepartementannee
                    WHERE pfa.idcycleenseignementannee = :cycleId AND da.iddepartementannee = :departementId;";
            $query = $db->prepare($sql);
            $query->bindParam(":cycleId", $cycleId, PDO::PARAM_INT);
            $query->bindParam(":departementId", $departementId, PDO::PARAM_INT);
        } else {
            $sql = "SELECT ma.idmoduleannee, ma.libelle, ma.code, TRUE AS hasAccess
                    FROM syllabus.periodeformation_annee pfa
                    JOIN syllabus.moduleperiodeformation_annee mpfa ON pfa.idperiodeformationannee = mpfa.idperiodeformationannee
                    JOIN syllabus.module_annee ma ON mpfa.idmoduleannee = ma.idmoduleannee
                    JOIN syllabus.moduledepartement_annee mda ON ma.idmoduleannee = mda.idmoduleannee
                    JOIN syllabus.departement_annee da ON mda.iddepartementannee = da.iddepartementannee
                    WHERE pfa.idcycleenseignementannee = :cycleId AND da.iddepartementannee = :departementId AND pfa.idperiodeformation = :periodeId;";
            $query = $db->prepare($sql);
            $query->bindParam(":cycleId", $cycleId, PDO::PARAM_INT);
            $query->bindParam(":departementId", $departementId, PDO::PARAM_INT);
            $query->bindParam(":periodeId", $periodeId, PDO::PARAM_INT);
        }
    } else {
        // Pour les non-administrateurs, calculer hasAccess en fonction de la responsabilité
        if ($periodeId === null) {
            $sql = "SELECT ma.idmoduleannee, ma.libelle, ma.code,
                           (CASE
                                WHEN ma.idresponsable = :userId THEN TRUE
                                WHEN EXISTS (
                                    SELECT 1
                                    FROM syllabus.groupematiereenseignee ge
                                    JOIN syllabus.matiereenseignee ecue ON ge.idgroupematiereenseignee = ecue.idgroupematiereenseignee
                                    WHERE ge.idmoduleannee = ma.idmoduleannee AND ecue.idresponsable = :userId
                                ) THEN TRUE
                                ELSE FALSE
                           END) AS hasAccess
                    FROM syllabus.periodeformation_annee pfa
                    JOIN syllabus.moduleperiodeformation_annee mpfa ON pfa.idperiodeformationannee = mpfa.idperiodeformationannee
                    JOIN syllabus.module_annee ma ON mpfa.idmoduleannee = ma.idmoduleannee
                    JOIN syllabus.moduledepartement_annee mda ON ma.idmoduleannee = mda.idmoduleannee
                    JOIN syllabus.departement_annee da ON mda.iddepartementannee = da.iddepartementannee
                    WHERE pfa.idcycleenseignementannee = :cycleId AND da.iddepartementannee = :departementId;";
            $query = $db->prepare($sql);
            $query->bindParam(":cycleId", $cycleId, PDO::PARAM_INT);
            $query->bindParam(":departementId", $departementId, PDO::PARAM_INT);
            $query->bindParam(":userId", $userId, PDO::PARAM_INT);
        } else {
            $sql = "SELECT ma.idmoduleannee, ma.libelle, ma.code,
                           (CASE
                                WHEN ma.idresponsable = :userId THEN TRUE
                                WHEN EXISTS (
                                    SELECT 1
                                    FROM syllabus.groupematiereenseignee ge
                                    JOIN syllabus.matiereenseignee ecue ON ge.idgroupematiereenseignee = ecue.idgroupematiereenseignee
                                    WHERE ge.idmoduleannee = ma.idmoduleannee AND ecue.idresponsable = :userId
                                ) THEN TRUE
                                ELSE FALSE
                           END) AS hasAccess
                    FROM syllabus.periodeformation_annee pfa
                    JOIN syllabus.moduleperiodeformation_annee mpfa ON pfa.idperiodeformationannee = mpfa.idperiodeformationannee
                    JOIN syllabus.module_annee ma ON mpfa.idmoduleannee = ma.idmoduleannee
                    JOIN syllabus.moduledepartement_annee mda ON ma.idmoduleannee = mda.idmoduleannee
                    JOIN syllabus.departement_annee da ON mda.iddepartementannee = da.iddepartementannee
                    WHERE pfa.idcycleenseignementannee = :cycleId AND da.iddepartementannee = :departementId AND pfa.idperiodeformation = :periodeId;";
            $query = $db->prepare($sql);
            $query->bindParam(":cycleId", $cycleId, PDO::PARAM_INT);
            $query->bindParam(":departementId", $departementId, PDO::PARAM_INT);
            $query->bindParam(":periodeId", $periodeId, PDO::PARAM_INT);
            $query->bindParam(":userId", $userId, PDO::PARAM_INT);
        }
    }

    $query->execute();
    $ues = $query->fetchAll(PDO::FETCH_ASSOC);

    $query->closeCursor();
    return $ues;
}

/**
 * Récupère les cycles par année
 *
 * @param int $yearId - L'identifiant de l'année
 *
 * @return array - Les cycles de l'année spécifiée
 */
function getCycles($yearId)
{
    $sql = "SELECT idcycleenseignementannee as id,libelle FROM syllabus.cycleenseignement_annee
	where idanneescolaire = :yearId
    ORDER BY libelle ASC";

    $query = dbConnect()->prepare($sql);
    $query->bindParam(":yearId", $yearId, PDO::PARAM_INT);
    $query->execute();
    $cycles = $query->fetchAll(PDO::FETCH_ASSOC);
    $query->closeCursor();
    return $cycles;
}

/**
 * Récupère les semestres par cycle
 *
 * @param int $cycleId - L'identifiant du cycle
 *
 * @return array - Les semestres du cycle spécifié
 */
function getSemestres($cycleId)
{
    $sql = "SELECT idperiodeformation as id,libelle FROM syllabus.periodeformation_annee
        where idcycleenseignementannee =:cycleId ORDER BY idperiodeformation";

    $query = dbConnect()->prepare($sql);
    $query->bindParam(":cycleId", $cycleId, PDO::PARAM_INT);
    $query->execute();
    $semestres = $query->fetchAll(PDO::FETCH_ASSOC);
    $query->closeCursor();
    return $semestres;
}

/**
 * Récupère les départements par cycle
 *
 * @param int $cycleId - L'identifiant du cycle
 *
 * @return array - Les départements du cycle spécifié
 */
function getDepartemnts($cycleId)
{
    $sql = "SELECT distinct da.iddepartementannee as id,da.libelle  FROM syllabus.periodeformation_annee pfa ,syllabus.moduleperiodeformation_annee mpfa,
	syllabus.module_annee ma ,syllabus.moduledepartement_annee mda ,syllabus.departement_annee da
	where pfa.idperiodeformationannee = mpfa.idperiodeformationannee
	and mpfa.idmoduleannee = ma.idmoduleannee and
	ma.idmoduleannee=mda.idmoduleannee and mda.iddepartementannee=da.iddepartementannee
	and pfa.idcycleenseignementannee =:cycleId  order by da.libelle ASC ";

    $query = dbConnect()->prepare($sql);
    $query->bindParam(":cycleId", $cycleId, PDO::PARAM_INT);
    $query->execute();
    $departements = $query->fetchAll(PDO::FETCH_ASSOC);
    $query->closeCursor();
    return $departements;
}

/**
 * Récupère les années distinctes
 *
 * @return array - Les années distinctes
 */
function getDistinctYears()
{
    try {
        $sql = "SELECT idanneescolaire,libelle
        FROM syllabus.anneescolaire ans
        ORDER BY ans.idanneescolaire DESC";

        $query = dbConnect()->prepare($sql);
        $query->execute();
        $years = $query->fetchAll(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return $years;
    } catch (Exception $e) {
        echo $e->getMessage();
        return json_encode(["status" => "error","message" => $e->getMessage()]);
    }
}

/**
 * Récupère les détails d'une UE
 *
 * @param int $id - L'identifiant de l'UE
 *
 * @return array - Les détails de l'UE spécifiée
 */
function getUEDetails($id)
{
    $sql = "SELECT ma.idmoduleannee,ma.description,ma.ects,ans.libelle as anneescolaire, ma.libelle, ma.code,pfa.code as semestre, cea.code as cycleenseignement 
            FROM syllabus.module_annee ma,syllabus.moduleperiodeformation_annee mpfa,syllabus.anneescolaire ans ,syllabus.periodeformation_annee pfa,syllabus.cycleenseignement_annee cea
            where ma.idanneescolaire = ans.idanneescolaire and ma.idmoduleannee=mpfa.idmoduleannee and cea.idcycleenseignementannee = pfa.idcycleenseignementannee 
	        and mpfa.idperiodeformationannee = pfa.idperiodeformationannee 
	        and ma.idmoduleannee= :id ;";

    $query = dbConnect()->prepare($sql);
    $query->bindParam(":id", $id, PDO::PARAM_INT);
    $query->execute();
    $uedetails = $query->fetch(PDO::FETCH_ASSOC);
    $query->closeCursor();

    $sqlResponsable = "SELECT u.* FROM syllabus.utilisateur u
            WHERE u.idutilisateur IN ( SELECT idresponsable FROM syllabus.module_annee WHERE idmoduleannee = :id)";

    $queryResponsable = dbConnect()->prepare($sqlResponsable);

    $queryResponsable->bindParam(":id", $id, PDO::PARAM_INT);
    $queryResponsable->execute();
    $uedetails["responsable"] = $queryResponsable->fetchAll(PDO::FETCH_ASSOC);
    $queryResponsable->closeCursor();

    $sqlBlocComp = "SELECT mbca.idmodulebloccompetenceannee as id,bc.code,mbca.actif FROM syllabus.modulebloccompetence_annee mbca,syllabus.bloccompetence bc
        WHERE mbca.idbloccompetence = bc.idbloccompetence
        AND  mbca.idmoduleannee = :id order by bc.code";

    $queryBlocComp = dbConnect()->prepare($sqlBlocComp);
    $queryBlocComp->bindParam(":id", $id, PDO::PARAM_INT);
    $queryBlocComp->execute();
    $bloccompetencesTemp = $queryBlocComp->fetchAll(PDO::FETCH_ASSOC);
    $queryBlocComp->closeCursor();

    $bloccompetences = [];
    foreach ($bloccompetencesTemp as $bloccompetence) {
        $sqlComp = "SELECT ec.code as etat,c.code
                    FROM syllabus.modulecompetence_annee mca, syllabus.etatcompetence ec, syllabus.competence c
                    WHERE ec.idetatcompetence = mca.idetatcompetence AND mca.idcompetence=c.idcompetence
                    and mca.idmodulebloccompetenceannee=:id order by c.code";

        $queryComp = dbConnect()->prepare($sqlComp);
        $queryComp->bindParam(":id", $bloccompetence["id"], PDO::PARAM_INT);
        $queryComp->execute();
        $competences = $queryComp->fetchAll(PDO::FETCH_ASSOC);
        $queryComp->closeCursor();


        $bloccompetences[$bloccompetence["id"]] = ["code" => $bloccompetence["code"],"actif" => $bloccompetence["actif"],"competences" => $competences];
    }
    $uedetails["bloccompetences"] = $bloccompetences;


    $sqlMatiere = "SELECT me.*
	FROM syllabus.groupematiereenseignee gm
	,syllabus.matiereenseignee me 
	where  idmoduleannee=:id and gm.idgroupematiereenseignee =me.idgroupematiereenseignee
	order by me.ordre;";

    $queryMatiere = dbConnect()->prepare($sqlMatiere);

    $queryMatiere->bindParam(":id", $id, PDO::PARAM_INT);
    $queryMatiere->execute();
    $uedetails["matiereenseignee"] = $queryMatiere->fetchAll(PDO::FETCH_ASSOC);

    foreach ($uedetails["matiereenseignee"] as &$ecue) {
        $sqlResponsable = "SELECT u.* FROM syllabus.utilisateur u
            WHERE u.idutilisateur IN ( SELECT idresponsable FROM syllabus.matiereenseignee WHERE idmatiereenseignee = :id)";


        $queryResponsable = dbConnect()->prepare($sqlResponsable);

        $queryResponsable->bindParam(":id", $ecue['idmatiereenseignee'], PDO::PARAM_INT);
        $queryResponsable->execute();
        $ecue["responsable"] = $queryResponsable->fetchAll(PDO::FETCH_ASSOC);


        $sqlOnu = "SELECT od.name FROM syllabus.ecue_onu eo
                    JOIN syllabus.onu_dimensions od ON eo.onu_dimension_id = od.id
                    WHERE eo.ecue_id = :id;";

        $queryOnu = dbConnect()->prepare($sqlOnu);
        $queryOnu->bindParam(":id", $ecue['idmatiereenseignee'], PDO::PARAM_INT);
        $queryOnu->execute();

        $ecue['socioenvdimension'] = $queryOnu->fetchAll(PDO::FETCH_COLUMN);
    }
    $queryMatiere->closeCursor();

    return $uedetails;
}

/**
 * Supprime une UE
 * @param int $id - L'identifiant de l'UE
 * @return string - Le statut de la suppression
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

/**
 * Récupère les UE par utilisateur
 * @param int $userId - L'identifiant de l'utilisateur
 * @return array - Les UE de l'utilisateur spécifié
 */
function getUEsByUser($userId)
{
    $db = dbConnect();

    $sql = "SELECT ma.idmoduleannee, ma.libelle, ma.code
            FROM syllabus.module_annee ma
            WHERE ma.idresponsable = :userId
            UNION
            SELECT ma.idmoduleannee, ma.libelle, ma.code
            FROM syllabus.module_annee ma
            JOIN syllabus.groupematiereenseignee gme ON ma.idmoduleannee = gme.idmoduleannee
            JOIN syllabus.matiereenseignee me ON gme.idgroupematiereenseignee = me.idgroupematiereenseignee
            WHERE me.idresponsable = :userId";

    $query = $db->prepare($sql);
    $query->bindParam(":userId", $userId, PDO::PARAM_INT);
    $query->execute();

    return $query->fetchAll(PDO::FETCH_ASSOC);
}
