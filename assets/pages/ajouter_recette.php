<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Traitement du formulaire pour ajouter une recette
    $titre = $_POST['titre'] ?? '';
    $description = $_POST['description'] ?? '';
    $categorie = $_POST['categorie'] ?? '';

    // Connexion à la base de données
    include '../config/database.php';

    $stmt = $pdo->prepare("INSERT INTO recettes (titre, description, categorie, user_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$titre, $description, $categorie, $_SESSION['user_id']]);

    header('Location: recettes.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une recette</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main class="container">
        <section class="section">
            <h1 class="title">Ajouter une nouvelle recette</h1>
            <form method="POST" action="">
                <div class="field">
                    <label class="label">Titre</label>
                    <div class="control">
                        <input class="input" type="text" name="titre" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Description</label>
                    <div class="control">
                        <textarea class="textarea" name="description" required></textarea>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Catégorie</label>
                    <div class="control">
                        <div class="select">
                            <select name="categorie" required>
                                <option value="Prise de masse">Prise de masse</option>
                                <option value="Maintien">Maintien</option>
                                <option value="Sèche">Sèche</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="control">
                    <button class="button is-primary" type="submit">Ajouter</button>
                </div>
            </form>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
