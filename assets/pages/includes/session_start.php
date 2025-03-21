<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Optionnel : Configuration globale (exemple : fuseau horaire)
date_default_timezone_set('Europe/Paris');

require_once '../config/db.php'; // Inclure la connexion à la base de données

$isConnected = isset($_SESSION['user_id']);
$user = null;

if ($isConnected) {
    $user_id = $_SESSION['user_id'];

    // Récupération des informations de l'utilisateur
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = :id");
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch();
}
?>