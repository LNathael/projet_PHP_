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
    <title>Projet Achat</title>

    <!-- Bulma & FontAwesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles principaux -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header class="site-header">
    <nav class="navbar is-primary" role="navigation" aria-label="main navigation">
        <div class="container">
            <div class="navbar-brand">
                <a class="navbar-item" href="../pages/accueil.php">
                    <img src="../img/logo.png" alt="Logo" class="brand-logo">
                </a>

                <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navMenu">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>

            <div id="navMenu" class="navbar-menu">
                <div class="navbar-start">
                    <a href="../pages/accueil.php" class="navbar-item">Accueil</a>
                    <a href="../pages/magasin.php" class="navbar-item">Magasin</a>
                    <a href="../pages/calculateur_calories.php" class="navbar-item">Calculateur de calories</a>
                    <a href="../pages/blog.php" class="navbar-item">Blog</a>

                    <div class="navbar-item has-dropdown is-hoverable">
                        <a class="navbar-link">Programmes</a>
                        <div class="navbar-dropdown">
                            <a href="../pages/programmes_masse.php" class="navbar-item">Prise de masse</a>
                            <a href="../pages/programmes_perte.php" class="navbar-item">Perte de poids</a>
                            <a href="../pages/programmes_debutants.php" class="navbar-item">Débutants</a>
                        </div>
                    </div>
                </div>

                <div class="navbar-end">
                    <div class="navbar-item">
                        <form action="../pages/recherche.php" method="GET">
                            <div class="field has-addons">
                                <div class="control">
                                    <input class="input" type="text" name="query" placeholder="Rechercher...">
                                </div>
                                <div class="control">
                                    <button class="button is-info">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <a href="../pages/panier.php" class="navbar-item">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="tag is-danger is-rounded">
                            <?= isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : 0 ?>
                        </span>
                    </a>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="navbar-item has-dropdown is-hoverable">
                            <a class="navbar-link">Mon Compte</a>
                            <div class="navbar-dropdown">
                                <a href="../pages/compte.php" class="navbar-item">Profil</a>
                                <a href="../pages/deconnexion.php" class="navbar-item">Déconnexion</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="navbar-item">
                            <div class="buttons">
                                <a href="../pages/inscription.php" class="button is-primary">S'inscrire</a>
                                <a href="../pages/connexion.php" class="button is-light">Se connecter</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>
