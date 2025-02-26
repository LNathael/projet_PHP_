<?php
session_start();
require_once '../config/db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

// Récupérer les salons
$stmt = $pdo->query("SELECT * FROM salons");
$salons = $stmt->fetchAll();
?>
<?php include '../includes/header.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>💬 Salons de discussion</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="container mt-5">
    <h1 class="title has-text-centered">💬 Choisissez un Salon MuscleTalk</h1>

    <div class="columns is-multiline">
        <?php foreach ($salons as $salon): ?>
            <div class="column is-half">
                <a href="chat.php?salon=<?= $salon['id_salon']; ?>" class="box has-text-centered">
                    <h2 class="title is-5">
                        <i class="fas fa-comments"></i> <?= htmlspecialchars($salon['nom_salon']); ?>
                    </h2>
                    <p><?= nl2br(htmlspecialchars($salon['description'])); ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
<?php include '../includes/footer.php'; ?>
