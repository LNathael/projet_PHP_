<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/style.css">
   
    <title>Projet Achat</title>
</head>
<body>
<header class="site-header">
    <nav class="nav-main">
        <div class="nav-brand">
            <a href="../pages/accueil.php" class="brand-link">
                <img src="../img/logo.png" alt="Logo" class="brand-logo">
            </a>
        </div>

        <div class="nav-links">
            <a href="../pages/accueil.php" class="nav-link">Accueil</a>
            <a href="../pages/magasin.php" class="nav-link">Magasin</a>
            <a href="../pages/calculateur_calories.php" class="nav-link">Calculateur de calories</a>
            <a href="../pages/blog.php" class="nav-link">Blog</a>

            <div class="nav-dropdown">
                <a class="nav-link dropdown-trigger">Programmes</a>
                <div class="dropdown-content">
                    <a href="../pages/programmes_masse.php" class="dropdown-item">Prise de masse</a>
                    <a href="../pages/programmes_perte.php" class="dropdown-item">Perte de poids</a>
                    <a href="../pages/programmes_debutants.php" class="dropdown-item">Débutants</a>
                </div>
            </div>
        </div>

        <div class="nav-actions">
            <form action="../pages/recherche.php" method="GET" class="search-form">
                <input type="text" name="query" class="search-input" placeholder="Rechercher...">
            </form>

            <a href="../pages/panier.php" class="cart-link">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count"><?= isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : 0 ?></span>
            </a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="user-menu">
                    <a href="../pages/compte.php" class="nav-button">Mon compte</a>
                    <a href="../pages/deconnexion.php" class="nav-button nav-button-logout">Déconnexion</a>
                </div>
            <?php else: ?>
                <div class="auth-buttons">
                    <a href="../pages/inscription.php" class="nav-button nav-button-primary">S'inscrire</a>
                    <a href="../pages/connexion.php" class="nav-button">Se connecter</a>
                </div>
            <?php endif; ?>
        </div>
    </nav>
</header>
</body>
</html>
