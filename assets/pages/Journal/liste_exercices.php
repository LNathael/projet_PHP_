<?php
session_start();
require_once '../config/db.php'; // Inclure la connexion à la base de données

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Connexion/connexion.php');
    exit;
}

// Récupérer tous les exercices
$exercices = $pdo->query("SELECT * FROM exercices")->fetchAll(PDO::FETCH_ASSOC);

// Récupérer l'exercice sélectionné
$selected_exercice_id = $_GET['id_exercice'] ?? null;
$selected_exercice = null;

if ($selected_exercice_id) {
    $stmt = $pdo->prepare("SELECT * FROM exercices WHERE id_exercice = :id_exercice");
    $stmt->execute(['id_exercice' => $selected_exercice_id]);
    $selected_exercice = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Exercices</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <section class="section">
        <div class="container">
             <!-- Bouton retour -->
             <a href="journal_entrainement.php" class="button is-light mb-4">
                <span class="icon">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span>Retour au journal d'entraînement</span>
            </a>
            <h1 class="title">Liste des Exercices</h1>

            <!-- Menu déroulant -->
            <form method="GET" action="">
                <div class="field">
                    <label class="label">Choisissez un exercice</label>
                    <div class="control">
                        <div class="select">
                            <select name="id_exercice" onchange="this.form.submit()">
                                <option value="">-- Sélectionnez un exercice --</option>
                                <?php foreach ($exercices as $exercice): ?>
                                    <option value="<?= $exercice['id_exercice']; ?>" <?= $selected_exercice_id == $exercice['id_exercice'] ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($exercice['nom']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Affichage des informations de l'exercice sélectionné -->
            <?php if ($selected_exercice): ?>
                <div class="box">
                    <h2 class="title is-4"><?= htmlspecialchars($selected_exercice['nom']); ?></h2>
                    <p><?= nl2br(htmlspecialchars($selected_exercice['description'])); ?></p>

                    <?php if (!empty($selected_exercice['video_path'])): ?>
                        <video controls width="100%">
                            <source src="<?= htmlspecialchars($selected_exercice['video_path']); ?>" type="video/mp4">
                            Votre navigateur ne supporte pas la lecture de vidéos.
                        </video>
                    <?php elseif (!empty($selected_exercice['image_path'])): ?>
                        <img src="<?= htmlspecialchars($selected_exercice['image_path']); ?>" alt="Image de l'exercice" style="max-width: 100%; height: auto;">
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php include '../includes/footer.php'; ?>
</body>
</html>