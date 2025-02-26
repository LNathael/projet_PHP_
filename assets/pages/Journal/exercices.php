<?php
session_start();
require_once '../config/db.php'; // Inclure la connexion à la base de données

if (!isset($_SESSION['user_id'])) {
    header('Location: ../Connexion/connexion.php');
    exit;
}

$exercices = $pdo->query("SELECT * FROM exercices")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Exercices</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <section class="section">
        <div class="container">
            <h1 class="title">Liste des Exercices</h1>
            <div class="columns is-multiline">
                <?php foreach ($exercices as $exercice): ?>
                    <div class="column is-one-third">
                        <div class="card">
                            <?php if (!empty($exercice['image_path'])): ?>
                                <div class="card-image">
                                    <figure class="image is-4by3">
                                        <img src="<?= htmlspecialchars($exercice['image_path']); ?>" alt="<?= htmlspecialchars($exercice['nom']); ?>">
                                    </figure>
                                </div>
                            <?php endif; ?>
                            <div class="card-content">
                                <div class="media">
                                    <div class="media-content">
                                        <p class="title is-4"><?= htmlspecialchars($exercice['nom']); ?></p>
                                    </div>
                                </div>
                                <div class="content">
                                    <p><?= htmlspecialchars($exercice['description']); ?></p>
                                    <?php if (!empty($exercice['video_path'])): ?>
                                        <div class="video-container">
                                            <video width="100%" controls>
                                                <source src="<?= htmlspecialchars($exercice['video_path']); ?>" type="video/mp4">
                                                Votre navigateur ne supporte pas la lecture de vidéos.
                                            </video>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php include '../includes/footer.php'; ?>
</body>
</html>