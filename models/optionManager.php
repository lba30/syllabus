<?php

require_once './models/connection.php';

// Fonction pour récupérer toutes les options
// @return array Liste des options
function getOptions()
{

    $query = dbConnect()->prepare("SELECT * FROM syllabus.option ;");
    $query->execute();
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    return $res;
}

// Fonction pour ajouter une nouvelle option
// @param array $data Données de l'option à ajouter
// @return string Résultat de l'opération en format JSON
function ajouterOption($data)
{

    try {
        $sql = "INSERT INTO syllabus.option(libelle,actif) VALUES(:libelle,:actif);";
        $query = dbConnect()->prepare($sql);
        $query->bindParam(":libelle", $data['libelle'], PDO::PARAM_STR);
        $query->bindParam(":actif", $data['actif'], PDO::PARAM_INT);
        $query->execute();
        return json_encode(['status' => 'success','message' => 'L\'option a été ajouté avec succès.']);
    } catch (PDOException $e) {
        return json_encode(['status' => 'error','message' => $e->getMessage()]);
    }
}

// Fonction pour ajouter une nouvelle option pour une année spécifique
// @param array $data Données de l'option à ajouter pour une année spécifique
// @return string Résultat de l'opération en format JSON
function ajouterOptionannee($data)
{

    try {
        $sql = "INSERT INTO syllabus.option_annee(idanneescolaire,idoption,libelle,code) VALUES(:idanneescolaire,:idoption,:libelle,:code);";
        $query = dbConnect()->prepare($sql);
        $query->bindParam(":idanneescolaire", $data['idanneescolaire'], PDO::PARAM_INT);
        $query->bindParam(":idoption", $data['idoption'], PDO::PARAM_INT);
        $query->bindParam(":libelle", $data['libelle'], PDO::PARAM_STR);
        $query->bindParam(":code", $data['code'], PDO::PARAM_STR);
        $query->execute();
        return json_encode(['status' => 'success','message' => 'L\'option a été ajouté avec succès.']);
    } catch (PDOException $e) {
        return json_encode(['status' => 'error','message' => $e->getMessage()]);
    }
}

// Fonction pour modifier une option existante
// @param array $data Données de l'option à modifier
// @return string Résultat de l'opération en format JSON
function modifierOption($data)
{

    try {
        $sql = "UPDATE syllabus.option SET libelle=:libelle , actif=:actif WHERE idoption=:idoption;";
        $query = dbConnect()->prepare($sql);
        $query->bindParam(":libelle", $data['libelle'], PDO::PARAM_STR);
        $query->bindParam(":actif", $data['actif'], PDO::PARAM_INT);
        $query->bindParam(":idoption", $data['idoption'], PDO::PARAM_INT);
        $query->execute();
        return json_encode(['status' => 'success','message' => 'L\'option a été modifié avec succès.']);
    } catch (PDOException $e) {
        return json_encode(['status' => 'error','message' => $e->getMessage()]);
    }
}

// Fonction pour modifier une option pour une année spécifique
// @param array $data Données de l'option à modifier pour une année spécifique
// @return string Résultat de l'opération en format JSON
function modifierOptionannee($data)
{

    try {
        $sql = "UPDATE syllabus.option_annee SET idanneescolaire=:idanneescolaire,idoption=:idoption,libelle=:libelle , code=:code WHERE idoptionannee=:idoptionannee;";
        $query = dbConnect()->prepare($sql);
        $query->bindParam(":idanneescolaire", $data['idanneescolaire'], PDO::PARAM_INT);
        $query->bindParam(":idoption", $data['idoption'], PDO::PARAM_INT);
        $query->bindParam(":libelle", $data['libelle'], PDO::PARAM_STR);
        $query->bindParam(":code", $data['code'], PDO::PARAM_STR);
        $query->bindParam(":idoptionannee", $data['idoptionannee'], PDO::PARAM_INT);
        $query->execute();
        return json_encode(['status' => 'success','message' => 'L\'option a été modifié avec succès.']);
    } catch (PDOException $e) {
        return json_encode(['status' => 'error','message' => $e->getMessage()]);
    }
}

// Fonction pour récupérer les années distinctes
// @return array Liste des années distinctes
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

// Fonction pour récupérer les options par année
// @param int $yearId Identifiant de l'année scolaire
// @return array Liste des options pour l'année spécifiée
function getOptionByYear($yearId)
{

    $sql = "SELECT * FROM syllabus.option_annee where idanneescolaire = :yearId ";
    $query = dbConnect()->prepare($sql);
    $query->bindParam(":yearId", $yearId, PDO::PARAM_INT);
    $query->execute();
    $options = $query->fetchAll(PDO::FETCH_ASSOC);
    $query->closeCursor();
    return $options;
}

// Fonction pour supprimer une option pour une année spécifique
// @param int $id Identifiant de l'option à supprimer
// @return string Résultat de l'opération en format JSON
function removeOptionannee($id)
{

    $conn = dbConnect();
    try {
        $conn->beginTransaction();
        $sql1 = "DELETE FROM syllabus.moduleoption_annee WHERE idoptionannee = :id";
        $query1 = $conn->prepare($sql1);
        $query1->bindParam(":id", $id, PDO::PARAM_INT);
        $query1->execute();
        $sql2 = "DELETE FROM syllabus.option_annee WHERE idoptionannee = :id";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(":id", $id, PDO::PARAM_INT);
        $query2->execute();
        $conn->commit();
        return json_encode(['status' => 'success','message' => 'L\'option a été supprimé avec succès.']);
    } catch (PDOException $e) {
        $conn->rollBack();
        return json_encode(['status' => 'error','message' => $e->getMessage()]);
    }
}
