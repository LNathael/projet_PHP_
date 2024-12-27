<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recettes personnalisées</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main class="container">
        <section class="section">
            <h1 class="title">Recettes adaptées à vos objectifs</h1>
            <p class="content">Découvrez des idées de recettes adaptées :</p>
            <div class="buttons">
                <a href="ajouter_recette.php" class="button is-primary">Ajouter une recette</a>
            </div>
            <!-- Exemple d'affichage -->
            <ul>
                <li>
                    <h2 class="subtitle">Recette 1 : Poulet et riz</h2>
                    <p class="content">Catégorie : Prise de masse</p>
                </li>
                <li>
                    <h2 class="subtitle">Recette 2 : Omelette aux légumes</h2>
                    <p class="content">Catégorie : Maintien</p>
                </li>
            </ul>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
