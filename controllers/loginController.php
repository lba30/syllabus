<?php

require_once './models/loginManager.php';
// Démarre la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirige l'utilisateur connecté vers la page d'accueil
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Traite la requête POST pour la connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = strtolower($_POST['username']);
    $password = $_POST['password'];
    try {
        // Vérifie les informations de connexion dans la base de données
        if (databaseLogin($username, password: $password)) {
            header("Location: index.php");
            exit();
        }

        // Authentification via LDAP
        $ldapResponse = ldapAuthenticate($username, $password);
        if ($ldapResponse['authenticated']) {
            if (addUtilisateur($username, $password, $ldapResponse['email'])) {
                header(header: "Location: index.php");
                exit();
            }
        }

        // Si les informations sont incorrectes, affiche un message d'erreur
        $_SESSION['error'] = "Nom d'utilisateur ou mot de passe incorrect.";
        header("Location: index.php?page=login");
        exit();
    } catch (Exception $err) {
        // En cas d'erreur, affiche un message d'erreur
        $_SESSION['error'] = "La connexion a échoué, veuillez réessayer.";
        header("Location: index.php?page=login");
        exit();
    }
}

// Définit le template de la page de connexion
$template = './views/pages/login.php';
