<?php
session_start();
require_once '../config/db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$id_utilisateur = $_GET['id'] ?? null;

// Vérifier que l'ID de l'utilisateur est valide
if (!$id_utilisateur) {
    die('Utilisateur introuvable.');
}

// Récupérer les informations de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = ?");
$stmt->execute([$id_utilisateur]);
$user = $stmt->fetch();

if (!$user) {
    die('Utilisateur introuvable.');
}
?>

<?php include '../includes/header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
    <main class="container mt-5">
        <section class="section">
            <div class="box">
                <h1 class="title is-4">Profil de <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></h1>
                <p><strong>Email :</strong> <?= htmlspecialchars($user['email']); ?></p>
                <p><strong>Date de création du compte :</strong> <?= htmlspecialchars($user['date_creation']); ?></p>
                <?php if ($user['photo_profil']): ?>
                    <img src="../../../htmlspecialchars($user['photo_profil']); ?>" alt="Photo de profil" style="max-width: 150px; max-height: 150px;">
                <?php endif; ?>
                <p><strong>Bio :</strong> <?= nl2br(htmlspecialchars($user['bio'] ?? '')); ?></p>
                <p><strong>Objectifs Fitness :</strong> <?= nl2br(htmlspecialchars($user['objectifs_fitness'] ?? '')); ?></p>
                <p><strong>Objectif :</strong> <?= htmlspecialchars($user['objectif'] ?? ''); ?></p>
            </div>
        </section>

        <section class="section">
            <a href="javascript:history.back()" class="button is-light">Retour</a>
        </section>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>