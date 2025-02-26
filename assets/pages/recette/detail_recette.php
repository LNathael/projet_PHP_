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

    // Récupérer les avis pour une recette spécifique
    $stmt_avis = $pdo->prepare("
        SELECT a.*, u.nom, u.prenom 
        FROM avis a
        JOIN utilisateurs u ON a.id_utilisateur = u.id_utilisateur
        WHERE a.type_contenu = 'recette' AND a.contenu_id = :id
        ORDER BY a.date_avis DESC
    ");
    $stmt_avis->execute(['id' => $id_recette]);
    $avis = $stmt_avis->fetchAll(PDO::FETCH_ASSOC);

    // Calculer la moyenne des avis
    $stmt_moyenne = $pdo->prepare("
        SELECT AVG(note) as moyenne 
        FROM avis 
        WHERE type_contenu = 'recette' AND contenu_id = :id
    ");
    $stmt_moyenne->execute(['id' => $id_recette]);
    $moyenne = $stmt_moyenne->fetchColumn();

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        function toggleAvis() {
            var avisElements = document.querySelectorAll('.avis-item');
            for (var i = 2; i < avisElements.length; i++) {
                avisElements[i].classList.toggle('is-hidden');
            }
            var button = document.getElementById('toggleAvisButton');
            var icon = button.querySelector('i');
            if (icon.classList.contains('fa-chevron-down')) {
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        }
    </script>
</head>
<body>
<main class="container">
    <section class="section">
        <h1 class="title"><?= htmlspecialchars($recette['titre']); ?></h1>
        <p><strong>Catégorie :</strong> <?= htmlspecialchars($recette['categorie']); ?></p>
        <p><strong>Auteur :</strong> <?= htmlspecialchars($recette['prenom'] . ' ' . $recette['nom']); ?></p>
        <p><strong>Date :</strong> <?= htmlspecialchars($recette['date_creation']); ?></p>
        <hr>
        <h2 class="title is-5">Image</h2>
        <?php if (!empty($recette['image'])): ?>
            <img src="../../../<?= htmlspecialchars($recette['image']); ?>" alt="<?= htmlspecialchars($recette['titre']); ?>" style="max-width: 100%; height: auto; margin-top: 10px;">
        <?php endif; ?>
        <br></br>
        <h2 class="title is-5">Description</h2>
        <p><?= htmlspecialchars($recette['description']); ?></p>
        <br></br>
        <h2 class="title is-5">Ingrédients</h2>
        <p><?= nl2br(htmlspecialchars($recette['ingredients'])); ?></p>
        <br></br>
        <h2 class="title is-5">Étapes</h2>
        <p><?= nl2br(htmlspecialchars($recette['etapes'])); ?></p>
    </section>

    <section class="section">
        <h2 class="title is-4">Avis des utilisateurs</h2>
        <p><strong>Moyenne des avis :</strong> <?= $moyenne ? number_format($moyenne, 2) : 'Aucun avis'; ?>/5</p>
        <?php if ($avis): ?>
            <?php foreach ($avis as $index => $avis_item): ?>
                <div class="box avis-item <?= $index >= 2 ? 'is-hidden' : '' ?>">
                    <p><strong>Utilisateur :</strong> <?= htmlspecialchars($avis_item['prenom'] . ' ' . $avis_item['nom']) ?></p>
                    <p><strong>Note :</strong> <?= htmlspecialchars($avis_item['note']) ?>/5</p>
                    <p><?= nl2br(htmlspecialchars($avis_item['commentaire'])) ?></p>
                    <p><small><em>Publié le <?= htmlspecialchars($avis_item['date_avis']) ?></em></small></p>
                </div>
            <?php endforeach; ?>
            <?php if (count($avis) > 2): ?>
                <button id="toggleAvisButton" class="button is-link" onclick="toggleAvis()">
                    <i class="fas fa-chevron-down"></i>
                </button>
            <?php endif; ?>
        <?php else: ?>
            <p>Aucun avis pour cette recette.</p>
        <?php endif; ?>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>