<?php

require_once './models/connection.php';

/**
 * Récupère les années scolaires distinctes.
 *
 * @return array Les années scolaires.
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
 * Récupère les cycles d'enseignement pour une année scolaire donnée.
 *
 * @param int $yearId L'identifiant de l'année scolaire.
 *
 * @return array Les cycles d'enseignement.
 */
function getCycles($yearId)
{
    $sql = "SELECT idcycleenseignementannee as id,libelle 
            FROM syllabus.cycleenseignement_annee
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
 * Récupère les semestres pour un cycle d'enseignement donné.
 *
 * @param int $cycleId L'identifiant du cycle d'enseignement.
 *
 * @return array Les semestres.
 */
function getSemestres($cycleId)
{
    $sql = "SELECT idperiodeformationannee as id,libelle
             FROM syllabus.periodeformation_annee
            where idcycleenseignementannee =:cycleId";

    $query = dbConnect()->prepare($sql);
    $query->bindParam(":cycleId", $cycleId, PDO::PARAM_INT);
    $query->execute();
    $semestres = $query->fetchAll(PDO::FETCH_ASSOC);
    $query->closeCursor();
    return $semestres;
}

/**
 * Récupère les départements pour une année scolaire donnée.
 *
 * @param int $yearId L'identifiant de l'année scolaire.
 *
 * @return array Les départements.
 */
function getDepartemnts($yearId)
{
    $sql = "SELECT iddepartementannee as id, libelle  FROM syllabus.departement_annee
	    where idanneescolaire = :yearId ";

    $query = dbConnect()->prepare($sql);
    $query->bindParam(":yearId", $yearId, PDO::PARAM_INT);
    $query->execute();
    $departements = $query->fetchAll(PDO::FETCH_ASSOC);
    $query->closeCursor();
    return $departements;
}

/**
 * Récupère les options pour une année scolaire donnée.
 *
 * @param int $yearId L'identifiant de l'année scolaire.
 *
 * @return array Les options.
 */
function getOptions($yearId)
{
    $sql = "SELECT idoptionannee as id,libelle  FROM syllabus.option_annee
        where idanneescolaire =:yearId";

    $query = dbConnect()->prepare($sql);
    $query->bindParam(":yearId", $yearId, PDO::PARAM_INT);
    $query->execute();
    $options = $query->fetchAll(PDO::FETCH_ASSOC);
    $query->closeCursor();
    return $options;
}

/**
 * Ajoute une unité d'enseignement (UE) avec les données fournies.
 *
 * @param array $formData Les données du formulaire.
 *
 * @return string JSON contenant le statut et le message de l'opération.
 */
function ajouterUe($formData)
{
    $conn = dbConnect();

    try {
        $conn->beginTransaction();

        // ajouter  l'ue
        $sql1 = "INSERT INTO syllabus.module_annee(idanneescolaire, libelle, code, description, ects,idresponsable)
	            VALUES (:idanneescolaire, :libelle, :code, :description, :ects,:idresponsable);";

        $query1 = $conn->prepare($sql1);
        $query1->bindParam(":idanneescolaire", $formData["yearFilter"], PDO::PARAM_INT);
        $query1->bindParam(":libelle", $formData["libelle"], PDO::PARAM_STR);
        $query1->bindParam(":code", $formData["code"], PDO::PARAM_STR);
        $query1->bindParam(":description", $formData["description"], PDO::PARAM_STR);
        $query1->bindParam(":ects", $formData["ects"], PDO::PARAM_INT);
        $query1->bindParam(":idresponsable", $formData["responsable"], PDO::PARAM_INT);

        $query1->execute();

        $idmoduleannee = $conn->lastInsertId();

        // lier l'ue avec periodeformation_annee
        $sql2 = "INSERT INTO syllabus.moduleperiodeformation_annee(idmoduleannee, idperiodeformationannee)
	            VALUES (:idmoduleannee, :idperiodeformationannee);";

        $query2 = $conn->prepare($sql2);
        $query2->bindParam(":idmoduleannee", $idmoduleannee, PDO::PARAM_INT);
        $query2->bindParam(":idperiodeformationannee", $formData["periodeformationFilter"], PDO::PARAM_INT);

        $query2->execute();

        // lier l'ue avec le departement s'il exite
        if ($formData["departementFilter"] !== "") {
            $sql3 = "INSERT INTO syllabus.moduledepartement_annee(iddepartementannee, idmoduleannee)
	                VALUES (:iddepartementannee, :idmoduleannee);";

            $query3 = $conn->prepare($sql3);
            $query3->bindParam(":idmoduleannee", $idmoduleannee, PDO::PARAM_INT);
            $query3->bindParam(":iddepartementannee", $formData["departementFilter"], PDO::PARAM_INT);

            $query3->execute();
        }

        // lier l'ue avec l'option s'il exite
        if ($formData["optionFilter"] !== "") {
            $sql4 = "INSERT INTO syllabus.moduleoption_annee(idmoduleannee, idoptionannee) 
            VALUES (:idmoduleannee, :idoptionannee);";

            $query4 = $conn->prepare($sql4);
            $query4->bindParam(":idmoduleannee", $idmoduleannee, PDO::PARAM_INT);
            $query4->bindParam(":idoptionannee", $formData["optionFilter"], PDO::PARAM_INT);

            $query4->execute();
        }

        $conn->commit();
        return json_encode(["status" => "success", "message" => "UE ajouté avec succès","idmodule" => $idmoduleannee]);
    } catch (PDOException $e) {
        $conn->rollBack();
        return json_encode(["status" => "error","message" => $e->getMessage()]);
    }
}
