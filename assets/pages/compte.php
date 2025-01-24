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
    <main class="container mt-5">
        <section class="section">
            <div class="box">
                <h1 class="title is-4">Bienvenue, <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?> !</h1>
                <p><strong>Email :</strong> <?= htmlspecialchars($user['email']); ?></p>
                <p><strong>Date de création du compte :</strong> <?= htmlspecialchars($user['date_creation']); ?></p>
            </div>
        </section>

        <!-- Mise à jour du mot de passe -->
        <section class="section">
            <h2 class="title is-5">Changer votre mot de passe</h2>
            <?php if (isset($message)): ?>
                <div class="notification is-success">
                    <?= htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            <form method="POST" class="box">
                <div class="field">
                    <label class="label">Nouveau mot de passe</label>
                    <div class="control">
                        <input class="input" type="password" name="nouveau_mot_de_passe" placeholder="Nouveau mot de passe" required>
                    </div>
                </div>
                <div class="field">
                    <div class="control">
                        <button type="submit" class="button is-primary">Mettre à jour</button>
                    </div>
                </div>
            </form>
        </section>

        <!-- Bouton de déconnexion -->
        <section class="section">
            <a href="deconnexion.php" class="button is-danger">Se déconnecter</a>
        </section>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
