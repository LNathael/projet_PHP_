<?php 
session_start();

include '../includes/header.php'; // Remonte d'un niveau depuis "pages/"
include '../config/db.php'; // Remonte d'un niveau puis va dans "config/"


// Traitement du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $mot_de_passe = trim($_POST['mot_de_passe']);
    $confirmer_mot_de_passe = trim($_POST['confirmer_mot_de_passe']);
    $erreurs = [];

    // Validation des champs
    if (empty($email) || empty($mot_de_passe) || empty($confirmer_mot_de_passe)) {
        $erreurs[] = "Tous les champs sont requis.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "Adresse e-mail invalide.";
    }

    if ($mot_de_passe !== $confirmer_mot_de_passe) {
        $erreurs[] = "Les mots de passe ne correspondent pas.";
    }

    if (strlen($mot_de_passe) < 6) {
        $erreurs[] = "Le mot de passe doit contenir au moins 6 caractères.";
    }

    // Si pas d'erreurs, traitement de l'inscription
    if (empty($erreurs)) {
        try {
            // Vérification si l'email existe déjà
            $stmt_verif = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = :email");
            $stmt_verif->execute([':email' => $email]);

            if ($stmt_verif->fetchColumn() > 0) {
                $erreurs[] = "Cet e-mail est déjà utilisé.";
            } else {
                // Insertion dans la base de données
                $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("INSERT INTO utilisateurs (email, mot_de_passe, role) VALUES (:email, :mot_de_passe, 'utilisateur')");
                $stmt->execute([':email' => $email, ':mot_de_passe' => $mot_de_passe_hash]);

                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['email'] = $email;

                header("Location: Acceuil.php");
                exit();
            }
        } catch (PDOException $e) {
            $erreurs[] = "Erreur lors de l'inscription : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/styleAcceuil.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
    <section class="hero is-light">
        <div class="hero-body">
            <h1 class="title">Inscription</h1>
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
                        <input class="input" type="email" name="email" value="<?= htmlspecialchars($email ?? ''); ?>" required>
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
                <div class="field">
                    <label class="label">Confirmer le mot de passe</label>
                    <div class="control has-icons-left">
                        <input class="input" type="password" name="confirmer_mot_de_passe" required>
                        <span class="icon is-small is-left"><i class="fas fa-lock"></i></span>
                    </div>
                </div>
                <div class="control">
                    <button class="button is-link is-fullwidth" type="submit">S'inscrire</button>
                </div>
            </form>
            <div class="has-text-centered" style="margin-top: 20px;">
                <p>Déjà inscrit ? <a href="connexion.php">Connectez-vous</a></p>
            </div>
        </div>
    </div>
</body>
</html>
<?php include '../includes/footer.php'; ?>