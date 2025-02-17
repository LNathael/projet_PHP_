<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    die('Vous devez être connecté pour envoyer un message.');
}

$id_utilisateur = $_SESSION['user_id'];
$id_salon = $_POST['id_salon'] ?? null;
$contenu = trim($_POST['contenu'] ?? '');
$id_recette = $_POST['id_recette'] ?? null;
$id_produit = $_POST['id_produit'] ?? null;

// Vérifier que le message et le salon existent
if (!$id_salon || empty($contenu)) {
    die('Message vide ou salon introuvable.');
}

// Insérer le message dans la base de données
$stmt = $pdo->prepare("INSERT INTO messages (id_utilisateur, id_salon, contenu, id_recette, id_produit) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$id_utilisateur, $id_salon, $contenu, $id_recette, $id_produit]);

// Rediriger vers le chat
header("Location: chat.php?salon=" . $id_salon);
exit;
?>