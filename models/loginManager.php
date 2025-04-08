<?php

require_once './models/connection.php';
require_once './config/ldap.php';

/**
 * Fonction pour authentifier un utilisateur via LDAP
 * Paramètres :
 * - $username (string) : le nom d'utilisateur
 * - $password (string) : le mot de passe
 * Retourne :
 * - array : tableau contenant 'authenticated' et 'email' si authentifié
 * false : si l'authentification échoue
*/
function ldapAuthenticate($username, $password)
{
    $ldap_host = LDAP_CONFIG['host'];
    $ldapPort = LDAP_CONFIG['ldapPort'];
    $base_dn = LDAP_CONFIG['baseDn'];
    $ldapVersionProtocole = LDAP_CONFIG['ldapVersionProtocole'];

    $ldap = ldap_connect($ldap_host, $ldapPort);

    if (!$ldap) {
        return false;
    }

    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, $ldapVersionProtocole);

    $searchFilter = "(uid=$username)";
    $searchResult = ldap_search($ldap, $base_dn, $searchFilter);

    if ($searchResult) {
        $entries = ldap_get_entries($ldap, $searchResult);

        if ($entries["count"] > 0) {
            $userDn = $entries[0]["dn"];

            $bind = @ldap_bind($ldap, $userDn, $password);

            if ($bind) {
                $email = $entries[0]["mail"][0] ?? null;
                return ['authenticated' => true, 'email' => $email];
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/** Fonction pour authentifier un utilisateur via la base de données
* Paramètres :
* - $username (string) : le nom d'utilisateur
* - $password (string) : le mot de passe
* Retourne :
* - true : si l'authentification réussit
* - false : si l'authentification échoue
*/
function databaseLogin($username, $password)
{
    $stmt = dbConnect()->prepare("SELECT u.idutilisateur, u.username, u.password, r.label AS role
                            FROM syllabus.utilisateur u
                            JOIN syllabus.role r ON u.idrole = r.idrole
                            WHERE u.username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['idutilisateur'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $username;
        $_SESSION['LAST_ACTIVITY'] = time();
        return true;
    }
    return false;
}

/** Fonction pour ajouter un utilisateur
* Paramètres :
* - $username (string) : le nom d'utilisateur
* - $password (string) : le mot de passe
* - $email (string|null) : l'email de l'utilisateur (optionnel)
* Retourne :
* - true : si l'utilisateur est ajouté avec succès
* - false : si l'ajout échoue
*/
function addUtilisateur($username, $password, $email = null)
{
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $stmt = dbConnect()->prepare("SELECT idrole FROM syllabus.role WHERE label = 'interne'");
    $stmt->execute();
    $interneId = $stmt->fetch()['idrole'];


    $stmt = dbConnect()->prepare("INSERT INTO syllabus.utilisateur (username, password,email ,idrole) VALUES (?, ?,?, ?) RETURNING idutilisateur ");
    $stmt->execute(params: [$username, $hashedPassword,$email, $interneId]);

    $userId = $stmt->fetchColumn();
    if ($userId) {
        $_SESSION['user_id'] = $userId;
        $_SESSION['role'] = 'interne';
        $_SESSION['username'] = $username;
        $_SESSION['LAST_ACTIVITY'] = time();
    } else {
        return false;
    }

    return true;
}
