<?php

require_once './models/userManager.php';
require_once './models/ueManager.php';
require_once './models/configManager.php';
// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Récupérer l'ID de l'utilisateur depuis la session
$userId = $_SESSION['user_id'] ?? null;
// Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
if (!$userId) {
    header("Location: index.php?page=login");
    exit();
}

// Récupérer les données de l'utilisateur par ID
$userData = getUserById($userId);
// Afficher une erreur et rediriger si les données de l'utilisateur ne peuvent pas être récupérées
if (!$userData) {
    $_SESSION['error'] = "Impossible de récupérer les détails de l'utilisateur.";
    header("Location: index.php");
    exit();
}

// Ne récupérer le délai d'expiration de la session que pour les administrateurs
if ($userData['role'] === 'administrateur') {
    $currentTimeout = getSessionTimeout();
    // Traiter la soumission du formulaire pour mettre à jour le délai d'expiration de la session
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newTimeout = (int)$_POST['timeout'];
        if ($newTimeout > 0) {
            updateSessionTimeout($newTimeout);
            $_SESSION['success'] = "Délai d'expiration de la session mis à jour avec succès.";
            header("Location: index.php?page=profile");
            exit();
        } else {
            $_SESSION['error'] = "Valeur de délai d'expiration invalide.";
        }
    }
}

// Récupérer les UEs de l'utilisateur
$userUEs = getUEsByUser($userId);
// Définir le template de la page de profil
$template = './views/pages/profile.php';
