<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php';

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupération des informations de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();

// Mise à jour du profil si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bio = $_POST['bio'] ?? '';
    $objectifs_fitness = $_POST['objectifs_fitness'] ?? '';
    $objectif = $_POST['objectif'] ?? '';
    $photo_profil = $_FILES['photo_profil']['name'] ? 'uploads/profils/' . basename($_FILES['photo_profil']['name']) : $user['photo_profil'];

    if ($_FILES['photo_profil']['name']) {
        // Vérifier et créer le répertoire si nécessaire
        $upload_dir = '../../uploads/profils';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        move_uploaded_file($_FILES['photo_profil']['tmp_name'], $upload_dir . '/' . basename($_FILES['photo_profil']['name']));
    }

    $update = $pdo->prepare("UPDATE utilisateurs SET bio = :bio, objectifs_fitness = :objectifs_fitness, objectif = :objectif, photo_profil = :photo_profil WHERE id_utilisateur = :id");
    $update->execute([
        'bio' => $bio,
        'objectifs_fitness' => $objectifs_fitness,
        'objectif' => $objectif,
        'photo_profil' => $photo_profil,
        'id' => $user_id
    ]);

    $message = "Profil mis à jour avec succès !";
}

// Récupération des recettes et programmes créés par l'utilisateur
$recettes = $pdo->prepare("SELECT * FROM recettes WHERE id_utilisateur = :id");
$recettes->execute(['id' => $user_id]);
$recettes = $recettes->fetchAll();

$programmes = $pdo->prepare("SELECT * FROM programmes WHERE id_utilisateur = :id");
$programmes->execute(['id' => $user_id]);
$programmes = $programmes->fetchAll();
?>
<?php include '../includes/header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
    <main class="container mt-5">
        <section class="section">
            <div class="box">
                <h1 class="title is-4">Bienvenue, <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?> !</h1>
                <p><strong>Email :</strong> <?= htmlspecialchars($user['email']); ?></p>
                <p><strong>Date de création du compte :</strong> <?= htmlspecialchars($user['date_creation']); ?></p>
                <?php if ($user['photo_profil']): ?>
                    <img src="../../<?= htmlspecialchars($user['photo_profil']); ?>" alt="Photo de profil" style="max-width: 150px; max-height: 150px;">
                <?php endif; ?>
                <p><strong>Bio :</strong> <?= nl2br(htmlspecialchars($user['bio'] ?? '')); ?></p>
                <p><strong>Objectifs Fitness :</strong> <?= nl2br(htmlspecialchars($user['objectifs_fitness'] ?? '')); ?></p>
                <p><strong>Objectif :</strong> <?= htmlspecialchars($user['objectif'] ?? ''); ?></p>
            </div>
        </section>

        <!-- Mise à jour du profil -->
        <section class="section">
            <h2 class="title is-5">Mettre à jour votre profil</h2>
            <?php if (isset($message)): ?>
                <div class="notification is-success">
                    <?= htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data" class="box">
                <div class="field">
                    <label class="label">Photo de profil</label>
                    <div class="control">
                        <input class="input" type="file" name="photo_profil">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Bio</label>
                    <div class="control">
                        <textarea class="textarea" name="bio" placeholder="Parlez de vous..."><?= htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Objectifs Fitness</label>
                    <div class="control">
                        <textarea class="textarea" name="objectifs_fitness" placeholder="Quels sont vos objectifs fitness ?"><?= htmlspecialchars($user['objectifs_fitness'] ?? ''); ?></textarea>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Objectif</label>
                    <div class="control">
                        <input class="input" type="text" name="objectif" value="<?= htmlspecialchars($user['objectif'] ?? ''); ?>" placeholder="Votre objectif (ex: Prise de masse)">
                    </div>
                </div>
                <div class="field">
                    <div class="control">
                        <button type="submit" class="button is-primary">Mettre à jour</button>
                    </div>
                </div>
            </form>
        </section>

        <!-- Affichage des recettes créées par l'utilisateur -->
        <section class="section">
            <h2 class="title is-5">Vos Recettes</h2>
            <?php if (!empty($recettes)): ?>
                <div class="columns is-multiline">
                    <?php foreach ($recettes as $recette): ?>
                        <div class="column is-one-third">
                            <div class="box">
                                <h2 class="title is-4"><?= htmlspecialchars($recette['titre']); ?></h2>
                                <p><strong>Catégorie :</strong> <?= htmlspecialchars($recette['categorie']); ?></p>
                                <p><strong>Date :</strong> <?= htmlspecialchars($recette['date_creation']); ?></p>
                                <?php if (!empty($recette['image'])): ?>
                                    <img src="../../../<?= htmlspecialchars($recette['image']); ?>" alt="<?= htmlspecialchars($recette['titre']); ?>" style="max-width: 100%; height: auto; margin-top: 10px;">
                                <?php endif; ?>
                                <p><?= htmlspecialchars(substr($recette['description'], 0, 200)) . '...'; ?></p>
                                <a href="../recette/detail_recette.php?id=<?= $recette['id_recette']; ?>" class="button is-link">Voir la recette</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Vous n'avez créé aucune recette.</p>
            <?php endif; ?>
        </section>

        <!-- Affichage des programmes créés par l'utilisateur -->
        <section class="section">
            <h2 class="title is-5">Vos Programmes</h2>
            <?php if (!empty($programmes)): ?>
                <div class="columns is-multiline">
                    <?php foreach ($programmes as $programme): ?>
                        <div class="column is-one-third">
                            <div class="box">
                                <h2 class="title is-4"><?= htmlspecialchars($programme['titre']); ?></h2>
                                <p><strong>Date :</strong> <?= htmlspecialchars($programme['date_creation']); ?></p>
                                <?php if (!empty($programme['image'])): ?>
                                    <img src="../../../<?= htmlspecialchars($programme['image']); ?>" alt="<?= htmlspecialchars($programme['titre']); ?>" style="max-width: 100%; height: auto; margin-top: 10px;">
                                <?php endif; ?>
                                <p><?= htmlspecialchars(substr($programme['description'], 0, 200)) . '...'; ?></p>
                                <a href="../programme/detail_programme.php?id=<?= $programme['id_programme']; ?>" class="button is-link">Voir le programme</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Vous n'avez créé aucun programme.</p>
            <?php endif; ?>
        </section>

        <!-- Bouton de déconnexion -->
        <section class="section">
            <a href="../Connexion/deconnexion.php" class="button is-danger">Se déconnecter</a>
        </section>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>