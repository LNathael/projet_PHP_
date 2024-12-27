<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isConnected = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>




    <main class="container mt-5">
    <!-- Titre principal -->
    <section class="hero is-primary is-medium has-text-centered">
        <div class="hero-body">
        <?php if (!$isConnected): ?>
            <section class="section">
                <h1 class="title">Bienvenue sur notre site</h1>
                <p class="content">Découvrez des programmes de musculation personnalisés et des recettes adaptées à vos besoins !</p>
                <a href="inscription.php" class="button is-primary">Inscription</a>
                <a href="connexion.php" class="button is-link">Connexion</a>
            </section>
        <?php else: ?>
            <section class="section">
                <h1 class="title">Bienvenue <?= $_SESSION['nom'] ?? 'Utilisateur'; ?> !</h1>
                <div class="buttons">
                    <a href="programmes_personnalises.php" class="button is-primary">Programmes personnalisés</a>
                    <a href="recettes.php" class="button is-link">Recettes</a>
                    <a href="avis.php" class="button is-info">Avis</a>
                </div>
            </section>
        <?php endif; ?>
    </section>

    <!-- Section Carousel -->
    <section class="section">
        <h2 class="title is-4 has-text-centered">Nos meilleurs produits</h2>
        <div class="carousel">
            <div class="slide active">
                <img src="../assets/img/image1.jpg" alt="Image 1">
            </div>
            <div class="slide">
                <img src="../assets/img/image2.jpg" alt="Image 2">
            </div>
            <div class="slide">
                <img src="../assets/img/image3.jpg" alt="Image 3">
            </div>
        </div>
    </section>

    <!-- Bouton pour déclencher la popup -->
    <div class="has-text-centered">
        <button class="button is-primary popup-trigger">Afficher la popup</button>
    </div>

    <!-- Section Popup -->
    <div class="popup is-hidden">
        <div class="popup-content box">
            <button class="delete popup-close"></button>
            <h2 class="title is-4">Titre de la popup</h2>
            <p>Contenu de la popup ici.</p>
        </div>
    </div>
       
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
