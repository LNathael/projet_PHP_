<?php
session_start();

include '../includes/header.php'; // Remonte d'un niveau depuis "pages/"
include '../config/db.php'; // Remonte d'un niveau puis va dans "config/"

// Initialisation des variables
$nom = $prenom = $email = $mot_de_passe = $confirmer_mot_de_passe = $date_naissance = $sexe = '';
$erreurs = [];

// Traitement du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données soumises dans le formulaire
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mot_de_passe = trim($_POST['mot_de_passe'] ?? '');
    $confirmer_mot_de_passe = trim($_POST['confirmer_mot_de_passe'] ?? '');
    $date_naissance = trim($_POST['date_naissance'] ?? '');
    $sexe = trim($_POST['sexe'] ?? '');

    // Validation des champs obligatoires
    if (empty($nom) || empty($prenom) || empty($email) || empty($mot_de_passe) || empty($confirmer_mot_de_passe)) {
        $erreurs[] = "Tous les champs obligatoires doivent être remplis.";
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

    // Si aucune erreur, on traite l'inscription
    if (empty($erreurs)) {
        try {
            // Vérification si l'email existe déjà dans la base de données
            $stmt_verif = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = :email");
            $stmt_verif->execute([':email' => $email]);

            if ($stmt_verif->fetchColumn() > 0) {
                $erreurs[] = "Cet e-mail est déjà utilisé.";
            } else {
                // Hachage du mot de passe
                $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);

                // Insertion des données dans la table "utilisateurs"
                $stmt = $pdo->prepare(
                    "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, date_naissance, sexe, role) 
                     VALUES (:nom, :prenom, :email, :mot_de_passe, :date_naissance, :sexe, 'utilisateur')"
                );
                $stmt->execute([
                    ':nom' => $nom,
                    ':prenom' => $prenom,
                    ':email' => $email,
                    ':mot_de_passe' => $mot_de_passe_hash,
                    ':date_naissance' => $date_naissance ?: null,
                    ':sexe' => $sexe ?: null,
                ]);

                // Enregistrement dans la session et redirection vers l'accueil
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['email'] = $email;

                header("Location: /pages/accueil.php");
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
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
    <section class="hero is-light">
        <div class="hero-body">
            <h1 class="title">Inscription</h1>
        </div><?php
session_start();

include '../includes/header.php'; // Remonte d'un niveau depuis "pages/"
include '../config/db.php'; // Remonte d'un niveau puis va dans "config/"

// Initialisation des variables
$nom = $prenom = $email = $mot_de_passe = $confirmer_mot_de_passe = $date_naissance = $sexe = '';
$erreurs = [];

// Traitement du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données soumises dans le formulaire
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mot_de_passe = trim($_POST['mot_de_passe'] ?? '');
    $confirmer_mot_de_passe = trim($_POST['confirmer_mot_de_passe'] ?? '');
    $date_naissance = trim($_POST['date_naissance'] ?? '');
    $sexe = trim($_POST['sexe'] ?? '');

    // Validation des champs obligatoires
    if (empty($nom) || empty($prenom) || empty($email) || empty($mot_de_passe) || empty($confirmer_mot_de_passe)) {
        $erreurs[] = "Tous les champs obligatoires doivent être remplis.";
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

    // Si aucune erreur, on traite l'inscription
    if (empty($erreurs)) {
        try {
            // Vérification si l'email existe déjà dans la base de données
            $stmt_verif = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = :email");
            $stmt_verif->execute([':email' => $email]);

            if ($stmt_verif->fetchColumn() > 0) {
                $erreurs[] = "Cet e-mail est déjà utilisé.";
            } else {
                // Hachage du mot de passe
                $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);

                // Insertion des données dans la table "utilisateurs"
                $stmt = $pdo->prepare(
                    "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, date_naissance, sexe, role) 
                     VALUES (:nom, :prenom, :email, :mot_de_passe, :date_naissance, :sexe, 'utilisateur')"
                );
                $stmt->execute([
                    ':nom' => $nom,
                    ':prenom' => $prenom,
                    ':email' => $email,
                    ':mot_de_passe' => $mot_de_passe_hash,
                    ':date_naissance' => $date_naissance ?: null,
                    ':sexe' => $sexe ?: null,
                ]);

                // Enregistrement dans la session et redirection vers l'accueil
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['email'] = $email;

                header("Location: acceuil.php");
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
    <link rel="stylesheet" href="css/style.css">
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
                    <label class="label">Nom</label>
                    <div class="control">
                        <input class="input" type="text" name="nom" value="<?= htmlspecialchars($nom); ?>" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Prénom</label>
                    <div class="control">
                        <input class="input" type="text" name="prenom" value="<?= htmlspecialchars($prenom); ?>" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Email</label>
                    <div class="control has-icons-left">
                        <input class="input" type="email" name="email" value="<?= htmlspecialchars($email); ?>" required>
                        <span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Date de naissance</label>
                    <div class="control">
                        <input class="input" type="date" name="date_naissance" value="<?= htmlspecialchars($date_naissance); ?>">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Sexe</label>
                    <div class="control">
                        <div class="select">
                            <select name="sexe" required>
                                <option value="" <?= empty($sexe) ? 'selected' : ''; ?>>Choisissez...</option>
                                <option value="Homme" <?= $sexe === 'Homme' ? 'selected' : ''; ?>>Homme</option>
                                <option value="Femme" <?= $sexe === 'Femme' ? 'selected' : ''; ?>>Femme</option>
                                <option value="Autre" <?= $sexe === 'Autre' ? 'selected' : ''; ?>>Autre</option>
                            </select>
                        </div>
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
                    <label class="label">Nom</label>
                    <div class="control">
                        <input class="input" type="text" name="nom" value="<?= htmlspecialchars($nom); ?>" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Prénom</label>
                    <div class="control">
                        <input class="input" type="text" name="prenom" value="<?= htmlspecialchars($prenom); ?>" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Email</label>
                    <div class="control has-icons-left">
                        <input class="input" type="email" name="email" value="<?= htmlspecialchars($email); ?>" required>
                        <span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Date de naissance</label>
                    <div class="control">
                        <input class="input" type="date" name="date_naissance" value="<?= htmlspecialchars($date_naissance); ?>">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Sexe</label>
                    <div class="control">
                        <div class="select">
                            <select name="sexe" required>
                                <option value="" <?= empty($sexe) ? 'selected' : ''; ?>>Choisissez...</option>
                                <option value="Homme" <?= $sexe === 'Homme' ? 'selected' : ''; ?>>Homme</option>
                                <option value="Femme" <?= $sexe === 'Femme' ? 'selected' : ''; ?>>Femme</option>
                                <option value="Autre" <?= $sexe === 'Autre' ? 'selected' : ''; ?>>Autre</option>
                            </select>
                        </div>
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
