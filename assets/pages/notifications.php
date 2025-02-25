<?php
session_start();
require_once '../config/db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$id_utilisateur = $_SESSION['user_id'];

// Récupérer les notifications de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE id_utilisateur = ? ORDER BY date_notification DESC");
$stmt->execute([$id_utilisateur]);
$notifications = $stmt->fetchAll();
?>

<?php include '../includes/header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
    <main class="container mt-5">
        <section class="section">
            <h1 class="title is-4">Notifications</h1>
            <?php if (!empty($notifications)): ?>
                <div class="box">
                    <?php foreach ($notifications as $notification): ?>
                        <div class="notification is-info">
                            <?= htmlspecialchars($notification['message']); ?>
                            <br>
                            <small><?= htmlspecialchars($notification['date_notification']); ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Vous n'avez aucune notification.</p>
            <?php endif; ?>
        </section>
        <section class="section">
            <a href="javascript:history.back()" class="button is-light">Retour</a>
        </section>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>