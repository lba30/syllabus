<?php

require_once './models/connection.php';

/**
 * Récupère les blocs de compétences non ajoutés pour un module donné.
 *
 * @param int $id L'identifiant du module.
 * @return array Les blocs de compétences non ajoutés.
 */
function getBlocCompetenceNonAjouter($id)
{
    $sql = "SELECT idbloccompetence,code,libelle
        FROM syllabus.bloccompetence bc
        where not exists (
            SELECT 1
            FROM syllabus.modulebloccompetence_annee mbca
            WHERE bc.idbloccompetence =mbca.idbloccompetence and mbca.idmoduleannee=:id
        ) order by code;";

    $query = dbConnect()->prepare($sql);
    $query->bindParam(":id", $id, PDO::PARAM_INT);
    $query->execute();
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    $query->closeCursor();
    return $res;
}

/**
 * Ajoute un bloc de compétences à un module.
 *
 * @param int $idmodule L'identifiant du module.
 * @param int $bcId L'identifiant du bloc de compétences.
 * @return string Le résultat de l'opération en JSON.
 */
function ajouterbccompetence($idmodule, $bcId)
{
    $conn = dbConnect();

    try {
        $conn->beginTransaction();
        $sql1 = "INSERT INTO syllabus.modulebloccompetence_annee(idbloccompetence, idmoduleannee, actif)
	    VALUES (:bcId,:idmodule,0);";

        $query1 = $conn->prepare($sql1);
        $query1->bindParam(":idmodule", $idmodule, PDO::PARAM_INT);
        $query1->bindParam(":bcId", $bcId, PDO::PARAM_INT);
        $query1->execute();

        $firsTableId = $conn->lastInsertId();

        $sql2 = "SELECT idetatcompetence FROM syllabus.etatcompetence where code ='nad';";
        $query2 = $conn->prepare($sql2);
        $query2->execute();

        $idEtat = $query2->fetch(PDO::FETCH_ASSOC)["idetatcompetence"];

        $sql3 = "SELECT idcompetence FROM syllabus.competence where idbloccompetence = :bcId;";
        $query3 = $conn->prepare($sql3);
        $query3->bindParam(":bcId", $bcId, PDO::PARAM_INT);
        $query3->execute();

        $competences = $query3->fetchAll(PDO::FETCH_ASSOC);

        foreach ($competences as $competence) {
            $sql4 = "INSERT INTO syllabus.modulecompetence_annee(idcompetence, idmodulebloccompetenceannee, idetatcompetence)
	        VALUES (:idcomp,:idmbc, :idetat);";
            $query4 = $conn->prepare($sql4);
            $query4->bindParam(":idcomp", $competence['idcompetence'], PDO::PARAM_INT);
            $query4->bindParam(':idmbc', $firsTableId, PDO::PARAM_INT);
            $query4->bindParam(':idetat', $idEtat, PDO::PARAM_INT);
            $query4->execute();
        }

        $conn->commit();

        $bcs = getBlocCompetences($idmodule);
        $nonAddedBC = getBlocCompetenceNonAjouter($idmodule);


        return json_encode(["status" => "success", "message" => "Bloc de compétences ajouté avec succès",'idUE' => intval($idmodule),"newBC" => $bcs,"nonAddedBC" => $nonAddedBC]);
    } catch (PDOException $e) {
        $conn->rollBack();
        return json_encode(["status" => "error","message" => "Erreur : " . $e->getMessage()]);
    }
}

/**
 * Supprime un bloc de compétences d'un module.
 *
 * @param int $idB L'identifiant du bloc de compétences.
 * @param int $idmodule L'identifiant du module.
 * @return string Le résultat de l'opération en JSON.
 */
function supprimerBlocCompetenceUe($idB, $idmodule)
{
    $conn = dbConnect();
    try {
        $conn->beginTransaction();

        $sql1 = "DELETE FROM syllabus.modulecompetence_annee WHERE idmodulebloccompetenceannee=:idB;";
        $query1 = $conn->prepare($sql1);
        $query1->bindParam(":idB", $idB, PDO::PARAM_INT);

        $query1->execute();

        $sql2 = "DELETE FROM syllabus.modulebloccompetence_annee WHERE idmodulebloccompetenceannee=:idB;";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(":idB", $idB, PDO::PARAM_INT);

        $query2->execute();

        $conn->commit();

        $bcs = getBlocCompetences($idmodule);
        $nonAddedBC = getBlocCompetenceNonAjouter($idmodule);

        return json_encode(['status' => 'success','message' => 'Bloc de compétences supprimé avec succès','idUE' => intval($idmodule),"newBC" => $bcs,"nonAddedBC" => $nonAddedBC]);
    } catch (PDOException $e) {
        $conn->rollBack();
        return json_encode(['status' => 'error','message' => "Erreur : " . $e->getMessage()]);
    }
}

/**
 * Modifie une unité d'enseignement (UE).
 *
 * @param array $data Les données de l'UE.
 * @param array $bccompetences Les blocs de compétences de l'UE.
 * @return string Le résultat de l'opération en JSON.
 */
function modifierUE($data, $bccompetences)
{
    $conn = dbConnect();

    try {
        $conn->beginTransaction();

        $sql = "UPDATE syllabus.module_annee
        SET libelle=:libelle, code=:code, description=:description, ects=:ects,idresponsable =:idresponsable
        WHERE idmoduleannee=:id;";

        $query = $conn->prepare($sql);
        $query->bindParam(":id", $data['id'], PDO::PARAM_INT);
        $query->bindParam(":ects", $data['ects'], PDO::PARAM_INT);
        $query->bindParam(":description", $data['description'], PDO::PARAM_STR);
        $query->bindParam(":code", $data['code'], PDO::PARAM_STR);
        $query->bindParam(":libelle", $data['libelle'], PDO::PARAM_STR);
        $query->bindParam(":idresponsable", $data['responsable'], PDO::PARAM_STR);

        $query->execute();

        foreach ($bccompetences as $bccompetence) {
            $sql1 = "UPDATE syllabus.modulebloccompetence_annee SET actif=:actif WHERE idmodulebloccompetenceannee=:id;";
            $query1 = $conn->prepare($sql1);
            $query1->bindParam(":id", $bccompetence["id"], PDO::PARAM_INT);
            $actif = ($bccompetence["actif"] === 'true') ? 1 : 0;
            $query1->bindParam(":actif", $actif, PDO::PARAM_INT);
            $query1->execute();

            foreach ($bccompetence['competences'] as $competence) {
                $sql2 = "SELECT idetatcompetence FROM syllabus.etatcompetence where code =:etat;";
                $query2 = $conn->prepare($sql2);
                $query2->bindParam(":etat", $competence["etat"], PDO::PARAM_STR);
                $query2->execute();

                $idEtat = $query2->fetch(PDO::FETCH_ASSOC)["idetatcompetence"];
                $sql3 = "UPDATE syllabus.modulecompetence_annee SET idetatcompetence=:idetat WHERE idmodulecompetenceannee=:id;";
                $query3 = $conn->prepare($sql3);
                $query3->bindParam(":id", $competence["id"], PDO::PARAM_INT);
                $query3->bindParam(":idetat", $idEtat, PDO::PARAM_INT);
                $query3->execute();
            }
        }
        $conn->commit();

        return json_encode(["status" => "success", "message" => "UE mise à jour avec succès"]);
    } catch (PDOException $e) {
        $conn->rollBack();
        return json_encode(["status" => "error","message" => "Erreur : " . $e->getMessage()]);
    }
}

/**
 * Récupère les détails d'une unité d'enseignement (UE).
 *
 * @param int $id L'identifiant de l'UE.
 * @return array Les détails de l'UE.
 */
function getUEDetails($id)
{
    $sql = "SELECT ma.idmoduleannee,ma.description,ma.ects, ma.libelle, ma.code,ma.idresponsable as responsable FROM syllabus.module_annee ma where  ma.idmoduleannee= :id ;";

    $query = dbConnect()->prepare($sql);
    $query->bindParam(":id", $id, PDO::PARAM_INT);
    $query->execute();
    $uedetails = $query->fetch(PDO::FETCH_ASSOC);
    $query->closeCursor();

    $sqlBlocComp = "SELECT mbca.idmodulebloccompetenceannee as id,bc.code,mbca.actif,bc.libelle FROM syllabus.modulebloccompetence_annee mbca,syllabus.bloccompetence bc
        WHERE mbca.idbloccompetence = bc.idbloccompetence
        AND  mbca.idmoduleannee = :id order by bc.code";

    $queryBlocComp = dbConnect()->prepare($sqlBlocComp);
    $queryBlocComp->bindParam(":id", $id, PDO::PARAM_INT);
    $queryBlocComp->execute();
    $bloccompetencesTemp = $queryBlocComp->fetchAll(PDO::FETCH_ASSOC);
    $queryBlocComp->closeCursor();

    $bloccompetences = [];
    foreach ($bloccompetencesTemp as $bloccompetence) {
        $sqlComp = "SELECT mca.idmodulecompetenceannee as id, ec.code as etat,c.code,c.actionobservable as libelle
                    FROM syllabus.modulecompetence_annee mca, syllabus.etatcompetence ec, syllabus.competence c
                    WHERE ec.idetatcompetence = mca.idetatcompetence AND mca.idcompetence=c.idcompetence
                    and mca.idmodulebloccompetenceannee=:id order by c.code ";

        $queryComp = dbConnect()->prepare($sqlComp);
        $queryComp->bindParam(":id", $bloccompetence["id"], PDO::PARAM_INT);
        $queryComp->execute();
        $competences = $queryComp->fetchAll(PDO::FETCH_ASSOC);
        $queryComp->closeCursor();


        $bloccompetences[] = ["id" => $bloccompetence["id"],"code" => $bloccompetence["code"],"actif" => $bloccompetence["actif"],"libelle" => $bloccompetence["libelle"],"competences" => $competences];
    }
    $uedetails["bloccompetences"] = $bloccompetences;


    $sqlMatiere = "SELECT me.idmatiereenseignee, me.libelle
	FROM syllabus.groupematiereenseignee gm,syllabus.matiereenseignee me 
	where  idmoduleannee=:id and gm.idgroupematiereenseignee =me.idgroupematiereenseignee
	order by me.ordre;";

    $queryMatiere = dbConnect()->prepare($sqlMatiere);

    $queryMatiere->bindParam(":id", $id, PDO::PARAM_INT);
    $queryMatiere->execute();
    $uedetails["matiereenseignee"] = $queryMatiere->fetchAll(PDO::FETCH_ASSOC);
    $queryMatiere->closeCursor();

    return $uedetails;
}

/**
 * Supprime une matière enseignée.
 *
 * @param int $id L'identifiant de la matière enseignée.
 * @return string Le résultat de l'opération en JSON.
 */
function removeecue($id)
{
    $conn = dbConnect();

    try {
        $conn->beginTransaction();

        // select l'id de groupematiereenseignee
        $query1 = $conn->prepare("SELECT idgroupematiereenseignee FROM syllabus.matiereenseignee where idmatiereenseignee=:id;");
        $query1->bindParam(":id", $id, PDO::PARAM_INT);
        $query1->execute();

        $idgroupematiereenseignee = $query1->fetch(PDO::FETCH_ASSOC)['idgroupematiereenseignee'];

        // supprimer matiereenseignee
        $sql2 = "DELETE FROM syllabus.matiereenseignee WHERE idmatiereenseignee=:id; ";

        $query2 = $conn->prepare($sql2);
        $query2->bindParam(':id', $id, PDO::PARAM_INT);
        $query2->execute();

        // supprimer groupematiereenseignee
        $sql3 = "DELETE FROM syllabus.groupematiereenseignee WHERE idgroupematiereenseignee=:idgroupematiereenseignee;";

        $query3 = $conn->prepare($sql3);
        $query3->bindParam(":idgroupematiereenseignee", $idgroupematiereenseignee, PDO::PARAM_INT);
        $query3->execute();

        $conn->commit();
        return json_encode(["status" => "success", "message" => "ECUE supprimé avec succès"]);
    } catch (PDOException $e) {
        $conn->rollBack();
        return json_encode(["status" => "error","message" => "Erreur : " . $e->getMessage()]);
    }
}

/**
 * Récupère les blocs de compétences d'un module.
 *
 * @param int $id L'identifiant du module.
 * @return array Les blocs de compétences du module.
 */
function getBlocCompetences($id)
{
    $sql = "
        SELECT 
            mbca.idmodulebloccompetenceannee AS bloc_id,
            bc.code AS bloc_code,
            mbca.actif AS bloc_actif,
            bc.libelle AS bloc_libelle,
            mca.idmodulecompetenceannee AS comp_id,
            ec.code AS etat_code,
            c.code AS comp_code,
            c.actionobservable AS comp_libelle
        FROM 
            syllabus.modulebloccompetence_annee mbca
        INNER JOIN 
            syllabus.bloccompetence bc 
            ON mbca.idbloccompetence = bc.idbloccompetence
        LEFT JOIN 
            syllabus.modulecompetence_annee mca 
            ON mca.idmodulebloccompetenceannee = mbca.idmodulebloccompetenceannee
        LEFT JOIN 
            syllabus.etatcompetence ec 
            ON ec.idetatcompetence = mca.idetatcompetence
        LEFT JOIN 
            syllabus.competence c 
            ON mca.idcompetence = c.idcompetence
        WHERE 
            mbca.idmoduleannee = :id
        ORDER BY 
            bc.code, c.code
    ";

    $query = dbConnect()->prepare($sql);
    $query->bindParam(":id", $id, PDO::PARAM_INT);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_ASSOC);
    $query->closeCursor();

    // Restructure the data into the desired format
    $bloccompetences = [];
    foreach ($results as $row) {
        $blocId = $row['bloc_id'];

        // If the bloccompetence is not already added, initialize it
        if (!isset($bloccompetences[$blocId])) {
            $bloccompetences[$blocId] = [
                "id" => $row["bloc_id"],
                "code" => $row["bloc_code"],
                "actif" => $row["bloc_actif"],
                "libelle" => $row["bloc_libelle"],
                "competences" => []
            ];
        }

        // If competence data exists, add it to the bloccompetence
        if (!empty($row["comp_id"])) {
            $bloccompetences[$blocId]["competences"][] = [
                "id" => $row["comp_id"],
                "etat" => $row["etat_code"],
                "code" => $row["comp_code"],
                "libelle" => $row["comp_libelle"]
            ];
        }
    }

    // Re-index the array to be sequential
    return array_values($bloccompetences);
}
