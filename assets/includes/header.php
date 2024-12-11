
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
    <title>Projet Achat</title>
</head>
<body>
<header>
    <nav class="navbar is-dark" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a class="navbar-item" href="accueil.php">
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
                <a class="navbar-item" href="../pages/contact.php">Contact</a>

                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link">Plus</a>
                    <div class="navbar-dropdown">
                        <a class="navbar-item" href="../pages/mentions_legales.php">Mentions légales</a>
                        <a class="navbar-item" href="../pages/a_propos.php">À propos</a>
                    </div>
                </div>
            </div>

            <div class="navbar-end">
                <div class="navbar-item">
                    <div class="buttons">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <!-- Menu pour utilisateur connecté -->
                            <a class="button is-light" href="../pages/compte.php">
                                Mon compte
                            </a>
                            <a class="button is-danger" href="../pages/deconnexion.php">
                                Déconnexion
                            </a>
                        <?php else: ?>
                            <!-- Menu pour utilisateur non connecté -->
                            <a class="button is-primary" href="../pages/inscription.php">
                                <strong>S'inscrire</strong>
                            </a>
                            <a class="button is-light" href="../pages/connexion.php">
                                Se connecter
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
</body>
</html>
