<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php'; // Inclure la connexion à la base de données

$isConnected = isset($_SESSION['user_id']);
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'administrateur';
$isSuperAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'super_administrateur';

// Vérifiez que l'utilisateur est connecté et que l'ID utilisateur est défini
if ($isConnected) {
    $user_id = $_SESSION['user_id'];

    // Récupération des informations de l'utilisateur
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = :id");
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch();

    // Vérifiez que la requête a retourné un résultat
    if ($user) {
        $userName = htmlspecialchars($user['nom']);
    } else {
        $userName = 'Utilisateur';
    }
} else {
    $userName = 'Utilisateur';
}

// Récupérer les produits depuis la base de données
$produits = $pdo->query("SELECT * FROM produits LIMIT 15")->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les recettes depuis la base de données
$recettes = $pdo->query("SELECT * FROM recettes LIMIT 15")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>

    <!-- Polices Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles principaux -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <!-- Corriger le chemin du CSS -->
    <link rel="stylesheet" href="../css/style.css">

    <!-- Scripts -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="../js/app.js" defer></script>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <main class="container mt-5">
        <!-- Titre principal -->
        <section class="hero is-primary is-medium has-text-centered fade-in">
            <div class="hero-body">
                <?php if (!$isConnected): ?>
                    <section class="section welcome-section">
                        <h1 class="title gradient-text">Bienvenue sur notre site</h1>
                        <p class="content hero-content">Découvrez des programmes de musculation personnalisés et des recettes adaptées à vos besoins !</p>
                        <div class="buttons is-centered">
                            <a href="inscription.php" class="button is-primary is-rounded">Inscription</a>
                            <a href="connexion.php" class="button is-link is-rounded">Connexion</a>
                        </div>
                    </section>
                <?php else: ?>
                    <section class="section">
                        <h1 class="title">Bienvenue <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?> !</h1>
                        <a href="programmes_personnalises.php" class="button is-primary">Programmes personnalisés</a>
                        <a href="recettes.php" class="button is-link">Recettes</a>
                        <a href="avis.php" class="button is-info">Avis</a>
                    </section>
                    <?php if ($isAdmin || $isSuperAdmin): ?>
                        <section class="section">
                            <h2 class="title is-4">Espace Administrateur</h2>
                            <a href="../admin/gestion_admin.php" class="button is-danger">Gestion Admin</a>
                        </section>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- Section Carousel Produits -->
        <section class="section products-section fade-in">
            <h2 class="title is-4 has-text-centered">Nos meilleurs produits</h2>
            <div class="swiper-container produits-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($produits as $produit): ?>
                        <div class="swiper-slide product-card">
                            <a href="<?= $isConnected ? 'detail_produit.php?id=' . $produit['id_produit'] : 'connexion.php'; ?>">
                                <div class="card-image-wrapper">
                                    <img src="../../<?= htmlspecialchars($produit['image']); ?>" alt="<?= htmlspecialchars($produit['nom_produit']); ?>">
                                </div>
                                <p class="product-title"><?= htmlspecialchars($produit['nom_produit']); ?></p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination produits-pagination"></div>
                <!-- Add Navigation -->
                <div class="swiper-button-next produits-button-next"></div>
                <div class="swiper-button-prev produits-button-prev"></div>
            </div>
        </section>

        <!-- Section Carousel Recettes -->
        <section class="section recipes-section fade-in">
            <h2 class="title is-4 has-text-centered">Nos meilleures recettes</h2>
            <div class="swiper-container recettes-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($recettes as $recette): ?>
                        <div class="swiper-slide recipe-card">
                            <a href="<?= $isConnected ? 'detail_recette.php?id=' . $recette['id_recette'] : 'connexion.php'; ?>">
                                <div class="card-image-wrapper">
                                    <img src="../../<?= htmlspecialchars($recette['image']); ?>" alt="<?= htmlspecialchars($recette['titre']); ?>">
                                </div>
                                <p class="recipe-title"><?= htmlspecialchars($recette['titre']); ?></p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination recettes-pagination"></div>
            </div>
        </section>

        <!-- Bouton pour déclencher la popup -->
        <div class="has-text-centered">
            <button class="button is-primary popup-trigger">Afficher la popup</button>
        </div>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>