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
    <title>Projet Achat</title>
</head>
<body>
<header>
    <nav class="navbar is-dark" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a class="navbar-item" href="../pages/accueil.php">
                <img src="../img/logo.png" alt="Logo" width="40" height="40">
            </a>

            <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasic">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div id="navbarBasic" class="navbar-menu">
            <div class="navbar-start">
                <a class="navbar-item" href="../pages/accueil.php">Accueil</a>
                <a class="navbar-item" href="../pages/magasin.php">Magasin</a>
                <a class="navbar-item" href="../pages/calculateur_calories.php">Calculateur de calories</a>

                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link">Programmes</a>
                    <div class="navbar-dropdown">
                        <a class="navbar-item" href="../pages/programmes_masse.php">Prise de masse</a>
                        <a class="navbar-item" href="../pages/programmes_perte.php">Perte de poids</a>
                        <a class="navbar-item" href="../pages/programmes_debutants.php">Débutants</a>
                    </div>
                </div>

                <a class="navbar-item" href="../pages/blog.php">Blog</a>
            </div>

            <div class="navbar-end">
                <div class="navbar-item">
                    <form action="../pages/recherche.php" method="GET">
                        <input class="input is-rounded" type="text" name="query" placeholder="Rechercher...">
                </div>
                <div class="navbar-item">
                    <a href="../pages/panier.php" class="button is-warning">
                        <span>Panier</span>
                        <span class="tag is-light"><?= isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : 0; ?></span>
                    </a>
                </div>
                <div class="navbar-item">
                    <div class="buttons">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a class="button is-light" href="../pages/compte.php">Mon compte</a>
                            <a class="button is-danger" href="../pages/deconnexion.php">Déconnexion</a>
                        <?php else: ?>
                            <a class="button is-primary" href="../pages/inscription.php"><strong>S'inscrire</strong></a>
                            <a class="button is-light" href="../pages/connexion.php">Se connecter</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
