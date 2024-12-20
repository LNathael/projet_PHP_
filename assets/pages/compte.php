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

// Mise à jour du mot de passe si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nouveau_mot_de_passe'])) {
    $nouveau_mot_de_passe = password_hash($_POST['nouveau_mot_de_passe'], PASSWORD_DEFAULT);

    $update = $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = :mot_de_passe WHERE id_utilisateur = :id");
    $update->execute([
        'mot_de_passe' => $nouveau_mot_de_passe,
        'id' => $user_id
    ]);

    $message = "Mot de passe mis à jour avec succès !";
}
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
    <section class="hero is-link">
        <div class="hero-body">
            <p class="title">Mon Compte</p>
            <p class="subtitle">Bienvenue, <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?> !</p>
        </div>
    </section>

    <div class="container" style="margin-top: 30px;">
        <?php if (!empty($message)): ?>
            <div class="notification is-success">
                <?= htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="box">
            <h2 class="title is-4">Modifier votre mot de passe</h2>
            <form method="POST">
                <div class="field">
                    <label class="label">Nouveau mot de passe</label>
                    <div class="control">
                        <input class="input" type="password" name="nouveau_mot_de_passe" placeholder="Entrez un nouveau mot de passe" required>
                    </div>
                </div>
                <div class="control">
                    <button class="button is-primary" type="submit">Mettre à jour le mot de passe</button>
                </div>
            </form>
        </div>

        <div class="has-text-centered" style="margin-top: 20px;">
            <a class="button is-danger" href="deconnexion.php">Se déconnecter</a>
        </div>
    </div>
</body>
</html>
<?php include '../includes/footer.php'; ?>
