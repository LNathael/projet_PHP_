<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/session_start.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MuscleTalk</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bulma CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">

    <!-- Bulma-carousel CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma-carousel@4.0.4/dist/css/bulma-carousel.min.css">

    <!-- Bulma-carousel JS -->
    <script src="https://cdn.jsdelivr.net/npm/bulma-carousel@4.0.4/dist/js/bulma-carousel.min.js"></script>

    <!-- Styles principaux -->
    <link rel="stylesheet" href="../../css/custom.css">

    <!-- Favicon et Manifest -->
    <link rel="shortcut icon" type="image/png" href="../../img/logo.png" />
    <link rel="manifest" href="../../json/manifest.json">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

    <!-- Scripts -->
    <script src="../../js/theme-toggle.js"></script>
    <!-- Custom JS -->
    <script src="../../js/app.js" defer></script>
    
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js')
                .then(function(registration) {
                    console.log('Service Worker registered with scope:', registration.scope);
                })
                .catch(function(error) {
                    console.log('Service Worker registration failed:', error);
                });
        }
    </script>
</head>
<body>
<header class="site-header">
    <nav class="navbar is-primary" role="navigation" aria-label="main navigation">
        <div class="container">
            <div class="navbar-brand">
                <a class="navbar-item" href="../Acceuil/accueil.php">
                    <img src="../../img/logo.png" alt="Logo" class="brand-logo">
                </a>

                <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navMenu">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>

            <div id="navMenu" class="navbar-menu">
                <div class="navbar-start">
                    <a href="../Acceuil/index.php" class="navbar-item">Accueil</a>
                    <a href="../Magasin/magasin.php" class="navbar-item">Magasin</a>
                    <a href="../Calorie/calculateur_calories.php" class="navbar-item">Calculateur de calories</a>
                    <a href="../blog/blog.php" class="navbar-item">Blog</a>

                    <div class="navbar-item has-dropdown is-hoverable">
                        <a class="navbar-link">Programmes</a>
                        <div class="navbar-dropdown">
                            <a href="../Programme/programmes_masse.php" class="navbar-item">Prise de masse</a>
                            <a href="../Programme/programmes_perte.php" class="navbar-item">Perte de poids</a>
                            <a href="../Programme/programmes_debutants.php" class="navbar-item">Débutants</a>
                        </div>
                    </div>
                </div>

                <!-- Barre de recherche visible uniquement sur les écrans larges -->
                <div class="navbar-item is-hidden-touch">
                    <form action="../Recherche/recherche.php" method="GET">
                        <div class="field has-addons">
                            <div class="control">
                                <input class="input" type="text" name="query" placeholder="Rechercher...">
                            </div>
                            <div class="control">
                                <button class="button is-info" type="submit">
                                    <span class="icon">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="navbar-end">
                    <a href="../Panier/panier.php" class="navbar-item">
                        Panier
                        <span class="tag is-danger is-rounded">
                            <?= isset($_SESSION['cart_quantity']) ? $_SESSION['cart_quantity'] : 0 ?>
                        </span>
                    </a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="navbar-item has-dropdown is-hoverable">
                            <a class="navbar-link" id="user-name"><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></a>
                            <div class="navbar-dropdown">
                                <a href="../Connexion/compte.php" class="navbar-item">Profil</a>
                                <a href="../Connexion/deconnexion.php" class="navbar-item">Déconnexion</a>
                                <button id="theme-toggle" class="button is-light ml-auto">Toggle Theme</button>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="navbar-item has-dropdown is-hoverable">
                            <a href="../Acceuil/index.php" class="navbar-link">Compte</a>
                            <div class="navbar-dropdown">
                                <a href="../Connexion/inscription.php" class="navbar-item">S'inscrire</a>
                                <a href="../Connexion/connexion.php" class="navbar-item">Se Connecter</a>
                                <hr class="navbar-divider">
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userNameElement = document.getElementById('user-name');
        const originalText = userNameElement ? userNameElement.textContent : '';

        if (userNameElement) {
            userNameElement.addEventListener('mouseover', function () {
                userNameElement.textContent = 'Mon Compte';
            });

            userNameElement.addEventListener('mouseout', function () {
                userNameElement.textContent = originalText;
            });
        }

        // Fermer les menus déroulants après un clic
        const dropdowns = document.querySelectorAll('.navbar-item.has-dropdown');
        dropdowns.forEach(dropdown => {
            dropdown.addEventListener('click', function () {
                dropdown.classList.remove('is-hoverable');
            });
        });
    });
</script>
</body>
</html>