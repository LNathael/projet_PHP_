<?php
session_start();
require_once '../config/db.php';

$id_salon = $_GET['salon'] ?? null;

if (!$id_salon) {
    die('Salon introuvable.');
}

// Récupérer les messages
$stmt = $pdo->prepare("
    SELECT m.*, u.nom, u.prenom 
    FROM messages m
    JOIN utilisateurs u ON m.id_utilisateur = u.id_utilisateur
    WHERE m.id_salon = ?
    ORDER BY m.date_message ASC
");
$stmt->execute([$id_salon]);
$messages = $stmt->fetchAll();

foreach ($messages as $message): ?>
    <div class="message is-info">
        <div class="message-header">
            <p><strong><?= htmlspecialchars($message['prenom'] . ' ' . $message['nom']); ?></strong></p>
            <span class="is-size-7"><?= htmlspecialchars($message['date_message']); ?></span>
        </div>
        <div class="message-body">
            <?= nl2br(htmlspecialchars($message['contenu'])); ?>
        </div>
    </div>
<?php endforeach; ?>
