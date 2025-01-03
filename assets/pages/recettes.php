<?php
session_start();
include '../includes/header.php'; // Inclure le header
include '../config/db.php'; // Inclure la connexion à la base de données

// Récupérer les recettes depuis la base de données
try {
    $stmt = $pdo->prepare("SELECT r.*, u.nom, u.prenom 
                           FROM recettes r
                           JOIN utilisateurs u ON r.id_utilisateur = u.id_utilisateur
                           ORDER BY r.date_creation DESC");
    $stmt->execute();
    $recettes = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erreur lors de la récupération des recettes : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recettes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
<main class="container">
    <section class="section">
        <div class="is-flex is-justify-content-space-between is-align-items-center">
            <h1 class="title">Toutes les Recettes</h1>
            <!-- Bouton pour ajouter une recette -->
            <a href="ajouter_recette.php" class="button is-primary">Ajouter une recette</a>
        </div>

        <?php if (!empty($recettes)): ?>
            <div class="columns is-multiline">
                <?php foreach ($recettes as $recette): ?>
                    <div class="column is-one-third">
                        <div class="box">
                            <h2 class="title is-4"><?= htmlspecialchars($recette['titre']); ?></h2>
                            <p><strong>Catégorie :</strong> <?= htmlspecialchars($recette['categorie']); ?></p>
                            <p><strong>Auteur :</strong> <?= htmlspecialchars($recette['prenom'] . ' ' . $recette['nom']); ?></p>
                            <p><strong>Date :</strong> <?= htmlspecialchars($recette['date_creation']); ?></p>
                            <?php if (!empty($recette['image'])): ?>
                                <img src="../../../<?= htmlspecialchars($recette['image']); ?>" alt="<?= htmlspecialchars($recette['titre']); ?>" style="max-width: 100%; height: auto; margin-top: 10px;">
                            <?php endif; ?>
                            <p><?= htmlspecialchars(substr($recette['description'], 0, 100)) . '...'; ?></p>
                            <a href="detail_recette.php?id=<?= $recette['id_recette']; ?>" class="button is-link">Voir la recette</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucune recette trouvée.</p>
        <?php endif; ?>
    </section>
</main>

<?php include '../includes/footer.php'; // Inclure le footer ?>
</body>
</html>
