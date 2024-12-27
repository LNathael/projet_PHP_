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
    <title>Programmes personnalisés</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main class="container">
        <section class="section">
            <h1 class="title">Vos programmes personnalisés</h1>
            <p class="content">Voici les programmes basés sur vos objectifs :</p>
            <!-- Exemple d'affichage -->
            <ul>
                <li><strong>Programme 1 :</strong> Renforcement musculaire 3x/semaine</li>
                <li><strong>Programme 2 :</strong> Gain de masse avec nutrition ciblée</li>
            </ul>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
