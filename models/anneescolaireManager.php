<?php

require_once './models/connection.php';

/**
 * Fonction pour récupérer toutes les années scolaires
 *
 * @return array contient les années scolaire
 */
function getAnneesscolaires()
{
    $query = dbConnect()->prepare("SELECT * FROM syllabus.anneescolaire;");
    $query->execute();
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    $query->closeCursor();
    return $res;
}

/**
 * Fonction pour ajouter une nouvelle année scolaire
 *
 * @param array $data Les données du formulaire.
 *
 * @return string JSON
 */
function ajouterAnneescolaire($data)
{
    try {
        $sql = "INSERT INTO syllabus.anneescolaire(libelle, datedebut, datefin, actif) VALUES (:libelle, :datedebut, :datefin, :actif);";
        $query = dbConnect()->prepare($sql);
        $query->bindParam(":libelle", $data['libelle'], PDO::PARAM_STR);
        $query->bindParam(":datedebut", $data['datedebut'], PDO::PARAM_STR);
        $query->bindParam(":datefin", $data['datefin'], PDO::PARAM_STR);
        $query->bindParam(":actif", $data['actif'], PDO::PARAM_INT);
        $query->execute();
        return json_encode(["status" => "success", "message" => "Données insérées avec succès"]);
    } catch (PDOException $e) {
        return json_encode(["status" => "error","message" => "Erreur : " . $e->getMessage()]);
    }
}

/**
 * Fonction pour modifier une année scolaire existante
 *
 * @param array $data Les données du formulaire.
 *
 * @return string JSON
 */
function modifierAnneescolaire($data)
{
    try {
        $sql = "UPDATE syllabus.anneescolaire SET libelle=:libelle, datedebut=:datedebut, datefin=:datefin, actif=:actif WHERE idanneescolaire=:idanneescolaire";
        $query = dbConnect()->prepare($sql);
        $query->bindParam(":libelle", $data['libelle'], PDO::PARAM_STR);
        $query->bindParam(":datedebut", $data['datedebut'], PDO::PARAM_STR);
        $query->bindParam(":datefin", $data['datefin'], PDO::PARAM_STR);
        $query->bindParam(":actif", $data['actif'], PDO::PARAM_INT);
        $query->bindParam(":idanneescolaire", $data['idanneescolaire'], PDO::PARAM_INT);
        $query->execute();
        return json_encode(["status" => "success", "message" => "Données modifiées avec succès"]);
    } catch (PDOException $e) {
        return json_encode(["status" => "error","message" => "Erreur : " . $e->getMessage()]);
    }
}

/**
 * Fonction pour supprimer une année scolaire
 *
 * @param int $id de l'année scolaire
 *
 * @return string JSON
 */
function supprimerAnneescolaire($id)
{
    try {
        $sql = "DELETE FROM syllabus.anneescolaire WHERE idanneescolaire =:id;";
        $query = dbConnect()->prepare($sql);
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        $query->execute();
        return json_encode(["status" => "success", "message" => "Données supprimées avec succès"]);
    } catch (PDOException $e) {
        return json_encode(["status" => "error","message" => "Erreur : " . $e->getMessage()]);
    }
}
