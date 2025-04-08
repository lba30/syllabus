<?php

require_once './models/connection.php';

/**
 * Récupère tous les types de périodes de formation.
 *
 * @return array Les types de périodes de formation.
 */
function getTypePeriodeFormation()
{
    $query = dbConnect()->prepare("SELECT * FROM syllabus.typeperiodeformation;");
    $query->execute();
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    return $res;
}

/**
 * Ajoute un type de période de formation.
 *
 * @param array $data Les données du type de période de formation.
 *
 * @return string Message de succès ou d'erreur.
 */
function ajoutertypeperiodedeformation($data)
{
    try {
        $sql = "INSERT INTO syllabus.typeperiodeformation(libelle,code,actif) VALUES(:libelle,:code,:actif);";
        $query = dbConnect()->prepare($sql);
        $query->bindParam(":libelle", $data['libelle'], PDO::PARAM_STR);
        $query->bindParam(":code", $data['code'], PDO::PARAM_STR);
        $query->bindParam(":actif", $data['actif'], PDO::PARAM_INT);
        $query->execute();
        return json_encode(['status' => 'success','message' => 'Le type de période de formation a été ajouté avec succès.']);
    } catch (PDOException $e) {
        return json_encode(['status' => 'error','message' => $e->getMessage()]);
    }
}

/**
 * Modifie un type de période de formation.
 *
 * @param array $data Les données du type de période de formation.
 *
 * @return string Message de succès ou d'erreur.
 */
function modifiertypeperiodedeformation($data)
{
    try {
        $sql = "UPDATE syllabus.typeperiodeformation SET libelle=:libelle,code=:code,actif=:actif WHERE idtypeperiodeformation=:idtypeperiodeformation ;";
        $query = dbConnect()->prepare($sql);
        $query->bindParam(":libelle", $data['libelle'], PDO::PARAM_STR);
        $query->bindParam(":code", $data['code'], PDO::PARAM_STR);
        $query->bindParam(":actif", $data['actif'], PDO::PARAM_INT);
        $query->bindParam(":idtypeperiodeformation", $data['idtypeperiodeformation'], PDO::PARAM_INT);
        $query->execute();
        return json_encode(['status' => 'success','message' => 'Le type de période de formation a été modifié avec succès.']);
    } catch (PDOException $e) {
        return json_encode(['status' => 'error','message' => $e->getMessage()]);
    }
}

/**
 * Récupère toutes les périodes de formation.
 *
 * @return array Les périodes de formation.
 */
function getPeriodeFormation()
{
    $query = dbConnect()->prepare("SELECT pf.*,tpf.libelle as typepf FROM syllabus.periodeformation pf, syllabus.typeperiodeformation tpf where pf.idtypeperiodeformation = tpf.idtypeperiodeformation;");
    $query->execute();
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    return $res;
}

/**
 * Ajoute une période de formation.
 *
 * @param array $data Les données de la période de formation.
 *
 * @return string Message de succès ou d'erreur.
 */
function ajouterperiodedeformation($data)
{
    try {
        $sql = "INSERT INTO syllabus.periodeformation(libelle,actif,idtypeperiodeformation) VALUES(:libelle,:actif,:idtypeperiodeformation);";
        $query = dbConnect()->prepare($sql);
        $query->bindParam(":libelle", $data['libelle'], PDO::PARAM_STR);
        $query->bindParam(":actif", $data['actif'], PDO::PARAM_INT);
        $query->bindParam(":idtypeperiodeformation", $data['idtypeperiodeformation'], PDO::PARAM_INT);
        $query->execute();
        return json_encode(['status' => 'success','message' => 'La période de formation a été ajoutée avec succès.']);
    } catch (PDOException $e) {
        return json_encode(['status' => 'error','message' => $e->getMessage()]);
    }
}

/**
 * Modifie une période de formation.
 *
 * @param array $data Les données de la période de formation.
 *
 * @return string Message de succès ou d'erreur.
 */
function modifierperiodedeformation($data)
{
    try {
        $sql = "UPDATE syllabus.periodeformation SET libelle=:libelle,actif=:actif,idtypeperiodeformation=:idtypeperiodeformation WHERE idperiodeformation=:idperiodeformation ;";
        $query = dbConnect()->prepare($sql);
        $query->bindParam(":libelle", $data['libelle'], PDO::PARAM_STR);
        $query->bindParam(":actif", $data['actif'], PDO::PARAM_INT);
        $query->bindParam(":idtypeperiodeformation", $data['idtypeperiodeformation'], PDO::PARAM_INT);
        $query->bindParam(":idperiodeformation", $data['idperiodeformation'], PDO::PARAM_INT);
        $query->execute();
        return json_encode(['status' => 'success','message' => 'La période de formation a été modifiée avec succès.']);
    } catch (PDOException $e) {
        return json_encode(['status' => 'error','message' => $e->getMessage()]);
    }
}
