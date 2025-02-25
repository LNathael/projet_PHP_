<?php
session_start();
require_once '../config/db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$id_utilisateur = $_SESSION['user_id'];
$id_salon = $_POST['id_salon'] ?? null;
$contenu = $_POST['contenu'] ?? '';
$id_recette = $_POST['id_recette'] ?? null;
$id_produit = $_POST['id_produit'] ?? null;
$reply_to = $_POST['reply_to'] ?? null;

// Vérifier que l'ID du salon est valide
if (!$id_salon || empty($contenu)) {
    header('Location: chat.php?salon=' . $id_salon);
    exit;
}

// Si reply_to est vide, le définir à NULL
if (empty($reply_to)) {
    $reply_to = null;
}

// Insérer le message dans la base de données
$stmt = $pdo->prepare("
    INSERT INTO messages (id_salon, id_utilisateur, contenu, id_recette, id_produit, reply_to) 
    VALUES (:id_salon, :id_utilisateur, :contenu, :id_recette, :id_produit, :reply_to)
");
$stmt->execute([
    'id_salon' => $id_salon,
    'id_utilisateur' => $id_utilisateur,
    'contenu' => $contenu,
    'id_recette' => $id_recette,
    'id_produit' => $id_produit,
    'reply_to' => $reply_to
]);

// Envoyer des notifications
if ($reply_to) {
    // Notification pour la réponse
    $stmt = $pdo->prepare("SELECT id_utilisateur FROM messages WHERE id_message = ?");
    $stmt->execute([$reply_to]);
    $reply_user = $stmt->fetch();
    if ($reply_user && $reply_user['id_utilisateur'] != $id_utilisateur) {
        $stmt = $pdo->prepare("
            INSERT INTO notifications (id_utilisateur, message, date_notification) 
            VALUES (:id_utilisateur, :message, NOW())
        ");
        $stmt->execute([
            'id_utilisateur' => $reply_user['id_utilisateur'],
            'message' => 'Vous avez une nouvelle réponse à votre message dans le salon ' . htmlspecialchars($salon['nom_salon'])
        ]);
    }
}

// Notification pour les mentions
preg_match_all('/@(\w+)/', $contenu, $mentions);
foreach ($mentions[1] as $mention) {
    $stmt = $pdo->prepare("SELECT id_utilisateur FROM utilisateurs WHERE CONCAT(prenom, ' ', nom) = ?");
    $stmt->execute([$mention]);
    $mention_user = $stmt->fetch();
    if ($mention_user && $mention_user['id_utilisateur'] != $id_utilisateur) {
        $stmt = $pdo->prepare("
            INSERT INTO notifications (id_utilisateur, message, date_notification) 
            VALUES (:id_utilisateur, :message, NOW())
        ");
        $stmt->execute([
            'id_utilisateur' => $mention_user['id_utilisateur'],
            'message' => 'Vous avez été mentionné dans un message dans le salon ' . htmlspecialchars($salon['nom_salon'])
        ]);
    }
}

header('Location: chat.php?salon=' . $id_salon);
exit;
?>