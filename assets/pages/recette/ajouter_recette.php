<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    die("Erreur : Vous devez être connecté pour ajouter une recette.");
}

include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $categorie = trim($_POST['categorie'] ?? '');
    $ingredients = trim($_POST['ingredients'] ?? '');
    $etapes = trim($_POST['etapes'] ?? '');
    $image = $_FILES['image'] ?? null;

    // Valider les champs
    if (empty($titre) || empty($description) || empty($categorie) || empty($ingredients) || empty($etapes) || !$image) {
        echo 'Tous les champs sont requis.';
        exit;
    }

    // Valider et traiter l'image
    $uploadDir = '../../uploads/recettes/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $imageName = uniqid('recette_') . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
    $imagePath = $uploadDir . $imageName;

    if (move_uploaded_file($image['tmp_name'], $imagePath)) {
        $imagePathDb = 'uploads/recettes/' . $imageName; // Chemin pour la base de données
    } else {
        echo 'Erreur lors du téléchargement de l\'image.';
        exit;
    }

    try {
        // Insérer la recette dans la base de données
        $stmt = $pdo->prepare("INSERT INTO recettes (titre, description, categorie, ingredients, etapes, image, id_utilisateur) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$titre, $description, $categorie, $ingredients, $etapes, $imagePathDb, $_SESSION['user_id']]);

        header('Location: recettes.php');
        exit;
    } catch (PDOException $e) {
        echo 'Erreur lors de l\'ajout : ' . $e->getMessage();
    }
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
            <form method="POST" action="" enctype="multipart/form-data">
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

                <div class="field">
                    <label class="label">Ingrédients</label>
                    <div class="control">
                        <textarea class="textarea" name="ingredients" required></textarea>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Étapes</label>
                    <div class="control">
                        <textarea class="textarea" name="etapes" required></textarea>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Image de la recette</label>
                    <div class="control">
                        <input class="input" type="file" name="image" accept="image/*" required>
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
