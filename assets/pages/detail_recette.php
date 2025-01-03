<?php
session_start();
include '../includes/header.php'; // Inclure le header
include '../config/db.php'; // Inclure la connexion à la base de données

// Récupérer l'ID de la recette
$id_recette = $_GET['id'] ?? null;

if (!$id_recette) {
    die('Recette introuvable.');
}

// Récupérer les détails de la recette
try {
    $stmt = $pdo->prepare("SELECT r.*, u.nom, u.prenom 
                           FROM recettes r
                           JOIN utilisateurs u ON r.id_utilisateur = u.id_utilisateur
                           WHERE r.id_recette = ?");
    $stmt->execute([$id_recette]);
    $recette = $stmt->fetch();

    if (!$recette) {
        die('Recette introuvable.');
    }
} catch (PDOException $e) {
    die("Erreur lors de la récupération de la recette : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($recette['titre']); ?></title>
</head>
<body>
<main class="container">
    <section class="section">
        <h1 class="title"><?= htmlspecialchars($recette['titre']); ?></h1>
        <p><strong>Catégorie :</strong> <?= htmlspecialchars($recette['categorie']); ?></p>
        <p><strong>Auteur :</strong> <?= htmlspecialchars($recette['prenom'] . ' ' . $recette['nom']); ?></p>
        <p><strong>Date :</strong> <?= htmlspecialchars($recette['date_creation']); ?></p>
        <hr>
        <h2 class="title is-5">Description</h2>
        <p><?= htmlspecialchars($recette['description']); ?></p>
        <h2 class="title is-5">Ingrédients</h2>
        <p><?= nl2br(htmlspecialchars($recette['ingredients'])); ?></p>
        <h2 class="title is-5">Étapes</h2>
        <p><?= nl2br(htmlspecialchars($recette['etapes'])); ?></p>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>
