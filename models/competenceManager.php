<?php

require_once './models/connection.php';

/**
 * Fonction pour obtenir tous les blocs de compétences
 *
 * @return array
 */
function getBlocCompetences()
{
    $query = dbConnect()->prepare("SELECT idbloccompetence,code,libelle FROM syllabus.bloccompetence ORDER BY idbloccompetence ASC ");
    $query->execute();

    $res = $query->fetchAll(PDO::FETCH_ASSOC);

    return $res;
}

/**
 * Fonction pour obtenir un bloc de compétences spécifique par ID
 *
 * @param int $id bloc de competences
 *
 * @return array
 */
function getBlocCompetence($id)
{
    $query = dbConnect()->prepare("SELECT * FROM syllabus.bloccompetence WHERE idbloccompetence=:id ");
    $query->bindParam(":id", $id, PDO::PARAM_INT);
    $query->execute();

    $res = $query->fetch(PDO::FETCH_ASSOC);

    $query1 = dbConnect()->prepare("SELECT * FROM syllabus.competence WHERE idbloccompetence=:id order by code");
    $query1->bindParam(":id", $id, PDO::PARAM_INT);
    $query1->execute();

    $res['competences'] = $query1->fetchAll(PDO::FETCH_ASSOC);

    return $res;
}

/**
 * Fonction pour supprimer un bloc de compétences par ID
 *
 * @param int $id bloc de competences
 *
 * @return string JSON
 */
function removeBlocCompetence($id)
{
    $conn = dbConnect();

    try {
        $conn->beginTransaction();

        // Sélectionner les compétences liées au bloc
        $sql1 = "SELECT idcompetence FROM syllabus.competence where idbloccompetence =:id; ";
        $query1 = $conn->prepare($sql1);
        $query1->bindParam(":id", $id, PDO::PARAM_INT);

        $query1->execute();

        $ids = $query1->fetchAll(PDO::FETCH_ASSOC);

        // Supprimer les compétences liées au bloc
        foreach ($ids as $idc) {
            removeCompetence($idc['idcompetence']);
        }

        // Supprimer le bloc
        $sql2 = "DELETE FROM syllabus.modulebloccompetence_annee WHERE idbloccompetence=:id;";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(":id", $id, PDO::PARAM_INT);
        $query2->execute();

        $sql3 = "DELETE FROM syllabus.bloccompetence WHERE idbloccompetence =:id;";

        $query3 = $conn->prepare($sql3);
        $query3->bindParam(":id", $id, PDO::PARAM_INT);

        $query3->execute();

        $conn->commit();
        return json_encode(['status' => 'success','message' => 'Bloc de compétences supprimé avec succès']);
    } catch (PDOException $e) {
        $conn->rollBack();
        return json_encode(['status' => 'error','message' => 'Erreur lors de la suppression du bloc de compétences: ' . $e->getMessage()]);
    }
}

/**
 * Fonction pour ajouter un nouveau bloc de compétences
 *
 * @param array $data Les données du formulaire.
 *
 * @return string JSON
 */
function addBlocCompetence($data)
{
    $conn = dbConnect();

    try {
        $conn->beginTransaction();
        $sql = "INSERT INTO syllabus.bloccompetence(code, activitesexercees, modalitesevaluation, criteresevaluation, libelle)
	    VALUES (:code, :activitesexercees, :modalitesevaluation, :criteresevaluation, :libelle);";

        $query = $conn->prepare($sql);

        $query->bindParam(":code", $data['code'], PDO::PARAM_STR);
        $query->bindParam(":activitesexercees", $data['activitesexercees'], PDO::PARAM_STR);
        $query->bindParam(":modalitesevaluation", $data['modalitesevaluation'], PDO::PARAM_STR);
        $query->bindParam(":criteresevaluation", $data['criteresevaluation'], PDO::PARAM_STR);
        $query->bindParam(":libelle", $data['libelle'], PDO::PARAM_STR);

        $query->execute();

        $idbc = $conn->lastInsertId();

        $conn->commit();
        return json_encode(["status" => "success", "message" => "Bloc de compétences ajouté avec succès", "idbc" => $idbc]);
    } catch (PDOException $e) {
        $conn->rollBack();
        return json_encode(["status" => "error","message" => 'Erreur lors de l\'ajout du bloc de compétences: ' . $e->getMessage()]);
    }
}

/**
 * Fonction pour ajouter une nouvelle compétence
 *
 * @param array $data Les données du formulaire.
 *
 * @return string JSON
 */
function addCompetence($data)
{
    try {
        $sql = "INSERT INTO syllabus.competence(idbloccompetence, actionobservable, ressourcesmobilisees, finalitesatteignables, code)
            VALUES (:idbloccompetence, :actionobservable, :ressourcesmobilisees, :finalitesatteignables, :code);";

        $query = dbConnect()->prepare($sql);

        $query->bindParam(":idbloccompetence", $data['idbloccompetence'], PDO::PARAM_INT);
        $query->bindParam(":actionobservable", $data['actionobservable'], PDO::PARAM_STR);
        $query->bindParam(":ressourcesmobilisees", $data['ressourcesmobilisees'], PDO::PARAM_STR);
        $query->bindParam(":finalitesatteignables", $data['finalitesatteignables'], PDO::PARAM_STR);
        $query->bindParam(":code", $data['code'], PDO::PARAM_STR);

        $query->execute();

        return json_encode(["status" => "success", "message" => "Compétence ajoutée avec succès"]);
    } catch (PDOException $e) {
        return json_encode(["status" => "error","message" => 'Erreur lors de l\'ajout de la compétence: ' . $e->getMessage()]);
    }
}

/**
 * Fonction pour modifier une compétence existante
 *
 * @param array $data Les données du formulaire.
 *
 * @return string JSON
 */
function modifierCompetence($data)
{
    try {
        $sql = "UPDATE syllabus.competence 
        SET  actionobservable=:actionobservable, ressourcesmobilisees=:ressourcesmobilisees, finalitesatteignables=:finalitesatteignables, code=:code
	    WHERE idcompetence=:idcompetence;";

        $query = dbConnect()->prepare($sql);

        $query->bindParam(":idcompetence", $data['idcompetence'], PDO::PARAM_INT);
        $query->bindParam(":actionobservable", $data['actionobservable'], PDO::PARAM_STR);
        $query->bindParam(":ressourcesmobilisees", $data['ressourcesmobilisees'], PDO::PARAM_STR);
        $query->bindParam(":finalitesatteignables", $data['finalitesatteignables'], PDO::PARAM_STR);
        $query->bindParam(":code", $data['code'], PDO::PARAM_STR);

        $query->execute();

        return json_encode(["status" => "success", "message" => "Compétence mise à jour avec succès"]);
    } catch (PDOException $e) {
        return json_encode(["status" => "error","message" => 'Erreur lors de la mise à jour de la compétence: ' . $e->getMessage()]);
    }
}

/**
 * Fonction pour modifier un bloc de compétences existant
 *
 * @param array $data Les données du formulaire.
 *
 * @return string JSON
 */
function modifierBlocCompetence($data)
{
    try {
        $sql = "UPDATE syllabus.bloccompetence
	SET  code=:code, activitesexercees=:activitesexercees, modalitesevaluation=:modalitesevaluation, criteresevaluation=:criteresevaluation, libelle=:libelle
	WHERE idbloccompetence=:idbloccompetence;";

        $query = dbConnect()->prepare($sql);

        $query->bindParam(":idbloccompetence", $data['idbloccompetence'], PDO::PARAM_INT);
        $query->bindParam(":code", $data['code'], PDO::PARAM_STR);
        $query->bindParam(":activitesexercees", $data['activitesexercees'], PDO::PARAM_STR);
        $query->bindParam(":modalitesevaluation", $data['modalitesevaluation'], PDO::PARAM_STR);
        $query->bindParam(":criteresevaluation", $data['criteresevaluation'], PDO::PARAM_STR);
        $query->bindParam(":libelle", $data['libelle'], PDO::PARAM_STR);

        $query->execute();

        return json_encode(["status" => "success", "message" => "Bloc de compétences mis à jour avec succès"]);
    } catch (PDOException $e) {
        return json_encode(["status" => "error","message" => 'Erreur lors de la mise à jour du bloc de compétences: ' . $e->getMessage()]);
    }
}

/**
 * Fonction pour supprimer une compétence par ID
 *
 * @param int $id competence
 *
 * @return string JSON
 */
function removeCompetence($id)
{
    $conn = dbConnect();
    try {
        $conn->beginTransaction();

        $sql1 = "DELETE FROM syllabus.modulecompetence_annee WHERE idcompetence=:id;";
        $query1 = $conn->prepare($sql1);
        $query1->bindParam(":id", $id, PDO::PARAM_INT);
        $query1->execute();

        $sql2 = "DELETE FROM syllabus.competence WHERE idcompetence = :id;";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(":id", $id, PDO::PARAM_INT);

        $query2->execute();

        $conn->commit();
        return json_encode(["status" => "success", "message" => "Compétence supprimée avec succès"]);
    } catch (PDOException $e) {
        $conn->rollBack();
        return json_encode(["status" => "error","message" => 'Erreur lors de la suppression de la compétence: ' . $e->getMessage()]);
    }
}
