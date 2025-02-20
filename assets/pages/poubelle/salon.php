<?php
require_once '../config/db.php';
session_start();
$id_salon = $_GET['id'] ?? null;

// Vérifier que l'ID du salon est valide
if (!$id_salon) {
    die('Salon introuvable.');
}

// Récupérer les messages du salon
$stmt = $pdo->prepare("SELECT m.*, u.nom, u.prenom, u.avatar 
                       FROM messages m
                       JOIN utilisateurs u ON m.id_utilisateur = u.id_utilisateur
                       WHERE id_salon = ?
                       ORDER BY date_message ASC");
$stmt->execute([$id_salon]);
$messages = $stmt->fetchAll();
?>

<?php include '../../includes/header.php';?>
<section class="section">
    <div class="container">
        <h1 class="title">💬 Discussion</h1>
        <div id="chat-box" class="box" style="height: 400px; overflow-y: scroll;">
            <?php foreach ($messages as $message): ?>
                <div class="message">
                    <img src="avatars/<?= $message['avatar']; ?>" class="is-rounded" width="40">
                    <strong><?= htmlspecialchars($message['prenom'] . ' ' . $message['nom']); ?>:</strong>
                    <p><?= htmlspecialchars($message['contenu']); ?></p>
                    <small><?= $message['date_message']; ?></small>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (isset($_SESSION['user_id'])): ?>
            <form id="chat-form" method="POST" action="send_message.php">
                <input type="hidden" name="id_salon" value="<?= $id_salon; ?>">
                <textarea class="textarea" name="message" placeholder="Écrivez votre message..." required></textarea>
                <button type="submit" class="button is-primary">Envoyer</button>
            </form>
        <?php else: ?>
            <p class="has-text-danger">Veuillez <a href="connexion.php">vous connecter</a> pour participer.</p>
        <?php endif; ?>
    </div>
</section>

<?php include '../../includes/footer.php';?>