<?php

require_once './models/connection.php';

/**
 * Récupère les informations d'une ECUE par son ID.
 *
 * @param int $id L'ID de l'ECUE.
 *
 * @return array Les informations de l'ECUE.
 */
function getEcue($id)
{

    // Récupérer les informations d'une ECUE par son ID
    $sql = "SELECT * FROM syllabus.matiereenseignee where  idmatiereenseignee=:id;";
    $query = dbConnect()->prepare($sql);
    $query->bindParam(":id", $id, PDO::PARAM_INT);
    $query->execute();
    $ecue = $query->fetch(PDO::FETCH_ASSOC);
    $query->closeCursor();
    return $ecue;
}

/**
 * Modifie les données d'une ECUE.
 *
 * @param array $data Les données de l'ECUE à modifier.
 *
 * @return string Le statut de la mise à jour.
 */
function modifierEcue($data)
{

    $conn = dbConnect();
    try {
        $conn->beginTransaction();
        // Modifier les données dans la table matiereenseignee
        $sql1 = "UPDATE syllabus.matiereenseignee
            SET libelle=:libelle, ordre=:ordre, contexte=:contexte, prerequis=:prerequis, plandecours=:plandecours, ressourcereference=:ressourcereference, nbhcours=:nbhcours, nbhcourstd=:nbhcourstd, nbhtd=:nbhtd, nbhtp=:nbhtp, nbhprojet=:nbhprojet, nbhautre=:nbhautre, nbhcontrole=:nbhcontrole, nbhautonomie=:nbhautonomie, coefficient=:coefficient, objectif=:objectif, activites=:activites, evaluation=:evaluation,idresponsable=:idresponsable
            WHERE idmatiereenseignee=:id;";
        $query = $conn->prepare($sql1);
        $query->bindParam(":id", $data['id'], PDO::PARAM_INT);
        $query->bindParam(":libelle", $data['libelle'], PDO::PARAM_STR);
        $query->bindParam(":ordre", $data['ordre'], PDO::PARAM_INT);
        $query->bindParam(":contexte", $data['contexte'], PDO::PARAM_STR);
        $query->bindParam(":prerequis", $data['prerequis'], PDO::PARAM_STR);
        $query->bindParam(":plandecours", $data['plandecours'], PDO::PARAM_STR);
        $query->bindParam(":ressourcereference", $data['ressourcereference'], PDO::PARAM_STR);
        $query->bindParam(":nbhcours", $data['nbhcours'], PDO::PARAM_STR);
        $query->bindParam(":nbhcourstd", $data['nbhcourstd'], PDO::PARAM_STR);
        $query->bindParam(":nbhtd", $data['nbhtd'], PDO::PARAM_STR);
        $query->bindParam(":nbhautonomie", $data['nbhautonomie'], PDO::PARAM_STR);
        $query->bindParam(":nbhcontrole", $data['nbhcontrole'], PDO::PARAM_STR);
        $query->bindParam(":nbhautre", $data['nbhautre'], PDO::PARAM_STR);
        $query->bindParam(":nbhprojet", $data['nbhprojet'], PDO::PARAM_STR);
        $query->bindParam(":nbhtp", $data['nbhtp'], PDO::PARAM_STR);
        $query->bindParam(":nbhtd", $data['nbhtd'], PDO::PARAM_STR);
        $query->bindParam(":evaluation", $data['evaluation'], PDO::PARAM_STR);
        $query->bindParam(":activites", $data['activites'], PDO::PARAM_STR);
        $query->bindParam(":objectif", $data['objectif'], PDO::PARAM_STR);
        $query->bindParam(":coefficient", $data['coefficient'], PDO::PARAM_INT);
        $query->bindParam(":idresponsable", $data['responsable'], PDO::PARAM_STR);
        $query->execute();
        $query1 = $conn->prepare("SELECT idgroupematiereenseignee FROM syllabus.matiereenseignee WHERE idmatiereenseignee=:id; ");
        $query1->bindParam(":id", $data['id'], PDO::PARAM_INT);
        $query1->execute();
        $idgroupematiereenseignee = $query1->fetch(PDO::FETCH_ASSOC)['idgroupematiereenseignee'];
        $query2 = $conn->prepare('UPDATE syllabus.groupematiereenseignee SET libelle=:libelle, ordre=:ordre WHERE idgroupematiereenseignee=:id');
        $query2->bindParam(':id', $idgroupematiereenseignee, PDO::PARAM_INT);
        $query2->bindParam(':ordre', $data['ordre'], PDO::PARAM_INT);
        $query2->bindParam(":libelle", $data['libelle'], PDO::PARAM_STR);
        $query2->execute();
        $queryDelete = $conn->prepare('DELETE FROM syllabus.ecue_onu WHERE ecue_id = :ecue_id');
        $queryDelete->bindParam(':ecue_id', $data['id'], PDO::PARAM_INT);
        $queryDelete->execute();
        $query3 = $conn->prepare('INSERT INTO syllabus.ecue_onu(ecue_id, onu_dimension_id) values(:ecue_id,:onu_dimension_id)');
        foreach ($data['socioenvdimension'] as $onuDimId) {
            $query3->bindParam(':ecue_id', $data['id'], PDO::PARAM_INT);
            $query3->bindParam(':onu_dimension_id', $onuDimId, PDO::PARAM_INT);
            $query3->execute();
        }

        $conn->commit();
        return json_encode(["status" => "success", "message" => "Données mises à jour avec succès"]);
    } catch (PDOException $e) {
        $conn->rollBack();
        return json_encode(["status" => "error","message" => "Erreur: " . $e->getMessage()]);
    }
}

/**
 * Ajoute une nouvelle ECUE.
 *
 * @param array $data Les données de la nouvelle ECUE.
 *
 * @return string Le statut de l'insertion.
 */
function ajouterEcue($data)
{

    $conn = dbConnect();
    try {
        $conn->beginTransaction();
        // Sélectionner l'ID de l'année scolaire
        $query1 = $conn->prepare("SELECT idanneescolaire FROM syllabus.module_annee where idmoduleannee=:id;");
        $query1->bindParam(":id", $data['idue'], PDO::PARAM_INT);
        $query1->execute();
        $idanneescolaire = $query1->fetch(PDO::FETCH_ASSOC)['idanneescolaire'];
        // Ajouter un groupematiereenseignee
        $sql2 = "INSERT INTO syllabus.groupematiereenseignee(idmoduleannee,nbheures, libelle, ordre, idanneescolaire)
	        VALUES (:idue, 0,:libelle,:ordre, :idanneescolaire);";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(':idue', $data['idue'], PDO::PARAM_INT);
        $query2->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
        $query2->bindParam(':ordre', $data['ordre'], PDO::PARAM_INT);
        $query2->bindParam(":libelle", $data['libelle'], PDO::PARAM_STR);
        $query2->execute();
        $idgroupematiereenseignee = $conn->lastInsertId();
        // Ajouter une matiereenseignee
        $sql3 = "INSERT INTO syllabus.matiereenseignee(
	            idgroupematiereenseignee,nbheures, libelle, ordre, contexte, prerequis, plandecours, ressourcereference, nbhcours, nbhcourstd, nbhtd, nbhtp, nbhprojet, nbhautre, nbhcontrole, nbhautonomie, coefficient, objectif, activites, evaluation,idresponsable)
	        VALUES (:idgroupematiereenseignee,0, :libelle, :ordre, :contexte, :prerequis, :plandecours, :ressourcereference, :nbhcours, :nbhcourstd, :nbhtd, :nbhtp, :nbhprojet, :nbhautre, :nbhcontrole, :nbhautonomie, :coefficient, :objectif, :activites, :evaluation,:idresponsable);";
        $query3 = $conn->prepare($sql3);
        $query3->bindParam(":idgroupematiereenseignee", $idgroupematiereenseignee, PDO::PARAM_INT);
        $query3->bindParam(":libelle", $data['libelle'], PDO::PARAM_STR);
        $query3->bindParam(":ordre", $data['ordre'], PDO::PARAM_INT);
        $query3->bindParam(":contexte", $data['contexte'], PDO::PARAM_STR);
        $query3->bindParam(":prerequis", $data['prerequis'], PDO::PARAM_STR);
        $query3->bindParam(":plandecours", $data['plandecours'], PDO::PARAM_STR);
        $query3->bindParam(":ressourcereference", $data['ressourcereference'], PDO::PARAM_STR);
        $query3->bindParam(":nbhcours", $data['nbhcours'], PDO::PARAM_STR);
        $query3->bindParam(":nbhcourstd", $data['nbhcourstd'], PDO::PARAM_STR);
        $query3->bindParam(":nbhtd", $data['nbhtd'], PDO::PARAM_STR);
        $query3->bindParam(":nbhautonomie", $data['nbhautonomie'], PDO::PARAM_STR);
        $query3->bindParam(":nbhcontrole", $data['nbhcontrole'], PDO::PARAM_STR);
        $query3->bindParam(":nbhautre", $data['nbhautre'], PDO::PARAM_STR);
        $query3->bindParam(":nbhprojet", $data['nbhprojet'], PDO::PARAM_STR);
        $query3->bindParam(":nbhtp", $data['nbhtp'], PDO::PARAM_STR);
        $query3->bindParam(":nbhtd", $data['nbhtd'], PDO::PARAM_STR);
        $query3->bindParam(":evaluation", $data['evaluation'], PDO::PARAM_STR);
        $query3->bindParam(":activites", $data['activites'], PDO::PARAM_STR);
        $query3->bindParam(":objectif", $data['objectif'], PDO::PARAM_STR);
        $query3->bindParam(":coefficient", $data['coefficient'], PDO::PARAM_INT);
        $query3->bindParam(":idresponsable", $data['responsable'], PDO::PARAM_STR);
        $query3->execute();
        $idmatiereenseignee = $conn->lastInsertId();
        $query3 = $conn->prepare('INSERT INTO syllabus.ecue_onu(ecue_id, onu_dimension_id) values(:ecue_id,:onu_dimension_id)');
        foreach ($data['socioenvdimension'] as $onuDimId) {
            $query3->bindParam(':ecue_id', $idmatiereenseignee, PDO::PARAM_INT);
            $query3->bindParam(':onu_dimension_id', $onuDimId, PDO::PARAM_INT);
            $query3->execute();
        }

        $conn->commit();
        return json_encode(["status" => "success", "message" => "Données insérées avec succès"]);
    } catch (PDOException $e) {
        $conn->rollBack();
        return json_encode(["status" => "error","message" => "Erreur: " . $e->getMessage()]);
    }
}

/**
 * Récupère les options ONU.
 *
 * @return array La liste des options ONU.
 */
function getONUOptions()
{

    // Récupérer les options ONU
    $db = dbConnect();
    $query = $db->query("SELECT id, name FROM syllabus.onu_dimensions ORDER BY id");
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupère les dimensions ONU sélectionnées pour une ECUE.
 *
 * @param int $ecueId L'ID de l'ECUE.
 *
 * @return array La liste des dimensions ONU sélectionnées.
 */
function getSelectedONUForEcue($ecueId)
{

    // Récupérer les dimensions ONU sélectionnées pour une ECUE
    $db = dbConnect();
    $stmt = $db->prepare("SELECT onu_dimension_id FROM syllabus.ecue_onu WHERE ecue_id = :ecue_id");
    $stmt->bindParam(':ecue_id', $ecueId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}
