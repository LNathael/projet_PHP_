<?php
session_start();
require_once '../config/db.php'; // Connexion à la base de données

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$isAdmin = $_SESSION['role'] === 'administrateur';
$isSuperAdmin = $_SESSION['role'] === 'super_administrateur';

$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();
$userName = $user ? htmlspecialchars($user['nom']) : 'Utilisateur';

// Récupération sécurisée des produits et recettes
$produits = $pdo->query("SELECT * FROM produits LIMIT 15")->fetchAll(PDO::FETCH_ASSOC);
$recettes = $pdo->query("SELECT * FROM recettes LIMIT 15")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?? '' ?>">
    
    <!-- Polices et Styles -->
    <link rel="stylesheet" href="../../css/custom.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script src="../../js/app.js" defer></script>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <main class="container mt-5">
        <section class="hero is-primary has-text-centered">
            <div class="hero-body">
                <?php include '../includes/popup_last_message.php'; ?>
                <h1 class="title">Bienvenue <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?> !</h1>
                <a href="../Programme/programmes_personnalises.php" class="button is-primary">Programmes personnalisés</a>
                <a href="../recette/recettes.php" class="button is-link">Recettes</a>
                <a href="../Avis/avis.php" class="button is-info">Avis</a>
                <a href="../Salon/salons.php" class="button is-info">💬 Chat Communautaire</a>
                <a href="../Journal/journal_entrainement.php" class="button is-success">Journal d'Entraînement</a>

                <?php if ($isAdmin || $isSuperAdmin): ?>
                    <section class="section">
                        <h2 class="title is-4">Espace Administrateur</h2>
                        <a href="../admin/gestion_admin.php" class="button is-danger">Gestion Admin</a>
                    </section>
                <?php endif; ?>
            </div>
        </section>

        <!-- Section Produits -->
        <section class="section products-section">
            <h2 class="title is-4 has-text-centered">Nos meilleurs produits</h2>
            <div class="swiper produits-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($produits as $produit): ?>
                        <div class="swiper-slide product-card">
                            <a href="../Produit/detail_produit.php?id=<?= $produit['id_produit']; ?>">
                                <div class="card-image-wrapper">
                                    <img src="../../../<?= htmlspecialchars($produit['image']); ?>" alt="<?= htmlspecialchars($produit['nom_produit']); ?>">
                                </div>
                                <p class="product-title"><?= htmlspecialchars($produit['nom_produit']); ?></p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination produits-pagination"></div>
                <div class="swiper-button-next produits-button-next"></div>
                <div class="swiper-button-prev produits-button-prev"></div>
            </div>
        </section>

        <!-- Section Recettes -->
        <section class="section recipes-section">
            <h2 class="title is-4 has-text-centered">Nos meilleures recettes</h2>
            <div class="swiper recettes-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($recettes as $recette): ?>
                        <div class="swiper-slide recipe-card">
                            <a href="../recette/detail_recette.php?id=<?= $recette['id_recette']; ?>">
                                <div class="card-image-wrapper">
                                    <img src="../../../<?= htmlspecialchars($recette['image']); ?>" alt="<?= htmlspecialchars($recette['titre']); ?>">
                                </div>
                                <p class="recipe-title"><?= htmlspecialchars($recette['titre']); ?></p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination recettes-pagination"></div>
                <div class="swiper-button-next recettes-button-next"></div>
                <div class="swiper-button-prev recettes-button-prev"></div>
            </div>
        </section>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
