<?php
session_start();
include '../config/db.php'; // Remonte d'un niveau puis va dans "config/"

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $mot_de_passe = trim($_POST['mot_de_passe']);
    $erreurs = [];

    // Validation des champs
    if (empty($email) || empty($mot_de_passe)) {
        $erreurs[] = "Tous les champs sont requis.";
    }

    // Traitement de la connexion
    if (empty($erreurs)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
                $_SESSION['user_id'] = $utilisateur['id_utilisateur'];
                $_SESSION['email'] = $utilisateur['email'];
                $_SESSION['role'] = $utilisateur['role']; // Gestion des rôles

                header("Location: ../Acceuil/accueil.php");
                exit();
            } else {
                $erreurs[] = "Email ou mot de passe incorrect.";
            }
        } catch (PDOException $e) {
            $erreurs[] = "Erreur lors de la connexion : " . $e->getMessage();
        }
    }
}
?>
<?php include '../includes/header.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
    <section class="hero is-light">
        <div class="hero-body">
            <h1 class="title">Connexion</h1>
        </div>
    </section>
    <div class="container">
        <div class="box">
            <?php if (!empty($erreurs)): ?>
                <div class="notification is-danger">
                    <?php foreach ($erreurs as $erreur): ?>
                        <p><?= htmlspecialchars($erreur); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="field">
                    <label class="label">Email</label>
                    <div class="control has-icons-left">
                        <input class="input" type="email" name="email" required>
                        <span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Mot de passe</label>
                    <div class="control has-icons-left">
                        <input class="input" type="password" name="mot_de_passe" required>
                        <span class="icon is-small is-left"><i class="fas fa-lock"></i></span>
                    </div>
                </div>
                <div class="control">
                    <button class="button is-link is-fullwidth" type="submit">Se connecter</button>
                </div>
            </form>
            <div class="has-text-centered" style="margin-top: 20px;">
                <p>Pas encore inscrit ? <a href="inscription.php">Inscrivez-vous</a></p>
            </div>
        </div>
    </div>
</body>
</html>
<?php include '../includes/footer.php'; ?>