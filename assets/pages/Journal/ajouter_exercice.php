<?php
session_start();
require_once '../config/db.php'; // Inclure la connexion à la base de données

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Connexion/connexion.php');
    exit;
}

// Vérifier si l'utilisateur est administrateur ou super administrateur
$isAdmin = isset($_SESSION['role']) && ($_SESSION['role'] === 'administrateur' || $_SESSION['role'] === 'super_administrateur');
if (!$isAdmin) {
    header('Location: ../Connexion/connexion.php');
    exit;
}

$erreurs = [];
$nom = '';
$description = '';
$image_path = '';
$video_path = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $description = trim($_POST['description'] ?? '');

    // Validation du nom
    if (empty($nom)) {
        $erreurs[] = "Le nom de l'exercice est obligatoire.";
    }

    // Gérer l'upload de l'image
    if (!empty($_FILES['image']['name'])) {
        $image_path = '../../uploads/' . basename($_FILES['image']['name']);
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            $erreurs[] = "Erreur lors de l'upload de l'image.";
        }
    }

    // Gérer l'upload de la vidéo
    if (!empty($_FILES['video']['name'])) {
        $video_path = '../../uploads/' . basename($_FILES['video']['name']);
        if (!move_uploaded_file($_FILES['video']['tmp_name'], $video_path)) {
            $erreurs[] = "Erreur lors de l'upload de la vidéo.";
        }
    }

    // Enregistrement dans la base de données si aucune erreur
    if (empty($erreurs)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO exercices (nom, description, image_path, video_path) VALUES (:nom, :description, :image_path, :video_path)");
            $stmt->execute([
                ':nom' => $nom,
                ':description' => $description,
                ':image_path' => $image_path,
                ':video_path' => $video_path
            ]);
        } catch (PDOException $e) {
            $erreurs[] = "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Exercice</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <section class="section">
        <div class="container">
            <h1 class="title">Ajouter un Exercice</h1>

            <!-- Affichage des erreurs -->
            <?php if (!empty($erreurs)): ?>
                <div class="notification is-danger">
                    <?php foreach ($erreurs as $erreur): ?>
                        <p><?= htmlspecialchars($erreur); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Formulaire d'ajout d'exercice -->
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="field">
                    <label class="label">Nom</label>
                    <div class="control">
                        <input class="input" type="text" name="nom" value="<?= htmlspecialchars($nom ?? ''); ?>" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Description</label>
                    <div class="control">
                        <textarea class="textarea" name="description"><?= htmlspecialchars($description ?? ''); ?></textarea>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Image</label>
                    <div class="control">
                        <input class="input" type="file" name="image">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Vidéo</label>
                    <div class="control">
                        <input class="input" type="file" name="video">
                    </div>
                </div>
                <div class="control">
                    <button class="button is-link is-fullwidth" type="submit">Ajouter</button>
                </div>
            </form>

            <!-- Aperçu de l'exercice ajouté -->
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($erreurs)): ?>
                <div class="box mt-5">
                    <h2 class="title is-4">Aperçu de l'exercice ajouté</h2>
                    <p><strong>Nom :</strong> <?= htmlspecialchars($nom); ?></p>
                    <p><strong>Description :</strong> <?= nl2br(htmlspecialchars($description)); ?></p>
                    <?php if (!empty($video_path)): ?>
                        <video controls width="100%">
                            <source src="<?= htmlspecialchars($video_path); ?>" type="video/mp4">
                            Votre navigateur ne supporte pas la lecture de vidéos.
                        </video>
                    <?php elseif (!empty($image_path)): ?>
                        <img src="<?= htmlspecialchars($image_path); ?>" alt="Image de l'exercice" style="max-width: 100%; height: auto;">
                    <?php else: ?>
                        <p>Aucune vidéo ou image disponible pour cet exercice.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php include '../includes/footer.php'; ?>
</body>
</html>