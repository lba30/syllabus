<?php

require_once './models/connection.php';

/**
 * Récupère la liste de tous les cycles d'enseignement.
 *
 * Cette fonction exécute une requête SQL pour sélectionner tous les cycles d'enseignement
 * dans la base de données, triés par ordre décroissant de l'ID.
 *
 * @return array La liste des cycles d'enseignement sous forme de tableau associatif.
 */
function getCyclesenseignement()
{
    $query = dbConnect()->prepare("SELECT * FROM syllabus.cycleenseignement order by idcycleenseignement desc;");
    $query->execute();

    $res = $query->fetchAll(PDO::FETCH_ASSOC);

    return $res;
}

/**
 * Ajoute un nouveau cycle d'enseignement dans la base de données.
 *
 * Cette fonction insère un nouveau cycle d'enseignement avec les informations passées dans le tableau `$data`.
 *
 * @param array $data Les données du cycle d'enseignement à ajouter, avec les clés 'libelle' et 'actif'.

 * @return string La réponse JSON indiquant le succès ou l'échec de l'opération.
 */
function ajouterCycleenseignement($data)
{
    try {
        $sql = "INSERT INTO syllabus.cycleenseignement(libelle,actif) VALUES(:libelle,:actif);";

        $query = dbConnect()->prepare($sql);

        $query->bindParam(':libelle', $data['libelle'], PDO::PARAM_STR);
        $query->bindParam(':actif', $data['actif'], PDO::PARAM_INT);

        $query->execute();
        return json_encode(["status" => "success", "message" => "Le cycle d'enseignement a été ajouté avec succès."]);
    } catch (PDOException $e) {
        return json_encode(["status" => "error","message" => $e->getMessage()]);
    }
}

/**
 * Modifie un cycle d'enseignement dans la base de données.
 *
 * Cette fonction met à jour un cycle d'enseignement avec les informations passées dans le tableau `$data`.
 *
 * @param array $data Les données du cycle d'enseignement à modifier, avec les clés 'libelle', 'actif' et 'idcycleenseignement'.

 * @return string La réponse JSON indiquant le succès ou l'échec de l'opération.
 */
function modifierCycleenseignement($data)
{
    try {
        $sql = "UPDATE syllabus.cycleenseignement SET libelle=:libelle ,actif=:actif WHERE idcycleenseignement=:id";

        $query = dbConnect()->prepare($sql);

        $query->bindParam(':libelle', $data['libelle'], PDO::PARAM_STR);
        $query->bindParam(':actif', $data['actif'], PDO::PARAM_INT);
        $query->bindParam(':id', $data['idcycleenseignement'], PDO::PARAM_INT);

        $query->execute();
        return json_encode(["status" => "success", "message" => "Le cycle d'enseignement a été modifié avec succès."]);
    } catch (PDOException $e) {
        return json_encode(["status" => "error","message" => $e->getMessage()]);
    }
}
