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

    echo "Mot de passe mis à jour avec succès !";
}
?>
<?php include '../includes/header.php'; ?>
<h1>Mon Compte</h1>
<p>Bienvenue, <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></p>
<form method="POST">
    <input type="password" name="nouveau_mot_de_passe" placeholder="Nouveau mot de passe" required>
    <button type="submit">Mettre à jour le mot de passe</button>
</form>
<a href="deconnexion.php">Se déconnecter</a>
<?php include '../includes/footer.php'; ?>
