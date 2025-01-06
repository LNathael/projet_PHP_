<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

include '../config/db.php';

$userId = $_SESSION['user_id'];
$programme = null;
$message = '';

// Récupérer tous les programmes créés par l'utilisateur
try {
    $stmt = $pdo->prepare("SELECT * FROM user_programs WHERE user_id = :user_id ORDER BY created_at DESC");
    $stmt->execute(['user_id' => $userId]);
    $programmes = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erreur lors de la récupération des programmes : " . $e->getMessage());
}

// Création de programme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $objectif = $_POST['objectif'] ?? '';
    $frequence = isset($_POST['frequence']) ? (int)$_POST['frequence'] : 0;
    $niveau = $_POST['niveau'] ?? '';
    $preference = $_POST['preference'] ?? '';

    if (empty($objectif) || empty($niveau) || $frequence <= 0) {
        $message = "Veuillez remplir tous les champs requis.";
    } else {
        // Génération du programme personnalisé
        $programme = [];
        if ($objectif === 'perte de poids') {
            if ($niveau === 'débutant') {
                $programme = [
                    "Lundi : Cardio léger (marche rapide, vélo 30 min)",
                    "Mercredi : Renforcement musculaire avec poids légers",
                    "Vendredi : Yoga ou stretching",
                ];
            } elseif ($niveau === 'intermédiaire') {
                $programme = [
                    "Lundi : Cardio modéré (course légère, vélo 45 min)",
                    "Mardi : Renforcement musculaire avec poids moyens",
                    "Jeudi : HIIT 20 min",
                    "Samedi : Étirements et relaxation",
                ];
            } elseif ($niveau === 'avancé') {
                $programme = [
                    "Lundi : Cardio intense (course rapide, intervalles)",
                    "Mardi : Entraînement complet corps (charges modérées)",
                    "Jeudi : HIIT avancé 30 min",
                    "Vendredi : Renforcement musculaire ciblé",
                    "Dimanche : Yoga dynamique",
                ];
            }
        } elseif ($objectif === 'gain de masse') {
            if ($niveau === 'débutant') {
                $programme = [
                    "Lundi : Entraînement basique haut du corps",
                    "Mercredi : Bas du corps (squats, fentes)",
                    "Vendredi : Exercices combinés avec poids légers",
                ];
            } elseif ($niveau === 'intermédiaire') {
                $programme = [
                    "Lundi : Haut du corps (pectoraux, biceps)",
                    "Mercredi : Bas du corps (squats, fentes, mollets)",
                    "Vendredi : Circuit complet avec charges lourdes",
                    "Samedi : Focus sur la nutrition riche en protéines",
                ];
            } elseif ($niveau === 'avancé') {
                $programme = [
                    "Lundi : Pectoraux, triceps, abdominaux",
                    "Mardi : Jambes et mollets",
                    "Jeudi : Dos et biceps",
                    "Vendredi : Épaules et abdominaux",
                    "Samedi : Entraînement complet (charges maximales)",
                ];
            }
        } elseif ($objectif === 'tonification') {
            if ($preference === 'exercices au poids du corps') {
                $programme = [
                    "Lundi : Circuit HIIT 20 min",
                    "Mardi : Renforcement abdos-gainage",
                    "Jeudi : Cardio modéré (course, corde à sauter)",
                    "Samedi : Étirements et relaxation",
                ];
            } elseif ($preference === 'entraînement avec machines') {
                $programme = [
                    "Lundi : Circuit machines (haut du corps)",
                    "Mercredi : Circuit machines (bas du corps)",
                    "Vendredi : HIIT sur tapis ou vélo",
                ];
            } elseif ($preference === 'exercices en extérieur') {
                $programme = [
                    "Lundi : Course en extérieur 5 km",
                    "Mercredi : Exercices fonctionnels (sprint, burpees)",
                    "Samedi : Randonnée ou vélo",
                ];
            } else {
                $programme = [
                    "Programme général de tonification, mixant exercices au poids du corps et machines."
                ];
            }
        } elseif ($objectif === 'amélioration cardio') {
            $programme = [
                "Lundi : Course modérée 5 km",
                "Mardi : Vélo ou natation 45 min",
                "Jeudi : Entraînement intervalles (HIIT 20-30 min)",
                "Samedi : Cardio léger (marche rapide, yoga dynamique)",
            ];
        } elseif ($objectif === 'powerlifting') {
            if ($niveau === 'débutant') {
                $programme = [
                    "Jour 1 : Squat 3x5, Leg Press 3x10, Crunchs 3x12",
                    "Jour 2 : Développé couché 3x5, Pompes 3x10, Planche 3x30s",
                    "Jour 3 : Soulevé de terre 3x5, Tractions 3x5, Extensions lombaires 3x10",
                ];
            } elseif ($niveau === 'intermédiaire') {
                $programme = [
                    "Jour 1 : Squat 4x4, Front squat 3x6, Fentes avec haltères 3x8",
                    "Jour 2 : Développé couché 4x4, Développé incliné 3x6, Dips 3x6",
                    "Jour 3 : Soulevé de terre 4x4, Deadlift jambes tendues 3x6, Rowing 3x8",
                    "Jour 4 : Deadlift partiel (rack pulls) 3x4, Squat pause 3x5",
                ];
            } elseif ($niveau === 'avancé') {
                $programme = [
                    "Jour 1 : Squat 5x3, Pause Squat 4x5, Hack Squat 3x8",
                    "Jour 2 : Développé couché 5x3, Développé incliné 4x5, Pompes lestées 3x6",
                    "Jour 3 : Soulevé de terre 5x3, Soulevé de terre roumain 3x6, Deadlift partiel 3x4",
                    "Jour 4 : Front squat 4x5, Box squat 4x5, Développé militaire 3x5",
                ];
            }
        }

        // Insertion du programme en base
        try {
            $stmt = $pdo->prepare("
                INSERT INTO user_programs (user_id, objectif, frequence, niveau, programme)
                VALUES (:user_id, :objectif, :frequence, :niveau, :programme)
            ");
            $stmt->execute([
                'user_id' => $userId,
                'objectif' => $objectif,
                'frequence' => $frequence,
                'niveau' => $niveau,
                'programme' => implode("\n", $programme), // Conversion du tableau en chaîne
            ]);

            // Message de succès
            $message = "Votre programme a été enregistré avec succès !";
        } catch (PDOException $e) {
            $message = "Erreur lors de l'enregistrement du programme : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programmes personnalisés</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main class="container">
        <section class="section">
            <h1 class="title">Créer votre Programme Personnalisé</h1>
            <p class="subtitle">Complétez le formulaire pour recevoir un programme adapté à vos besoins.</p>

            <?php if (!empty($message)): ?>
                <div class="notification <?= strpos($message, 'Erreur') === false ? 'is-success' : 'is-danger'; ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="field">
                    <label class="label">Votre objectif</label>
                    <div class="control">
                        <select name="objectif" class="input" required>
                            <option value="">-- Sélectionnez --</option>
                            <option value="perte de poids">Perte de poids</option>
                            <option value="gain de masse">Gain de masse</option>
                            <option value="tonification">Tonification</option>
                            <option value="amélioration cardio">Amélioration cardio</option>
                            <option value="powerlifting">Powerlifting</option>
                        </select>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Fréquence (jours/semaine)</label>
                    <div class="control">
                        <input type="number" name="frequence" class="input" min="1" max="7" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Niveau</label>
                    <div class="control">
                        <select name="niveau" class="input" required>
                            <option value="">-- Sélectionnez --</option>
                            <option value="débutant">Débutant</option>
                            <option value="intermédiaire">Intermédiaire</option>
                            <option value="avancé">Avancé</option>
                        </select>
                    </div>
                </div>

                <div class="control">
                    <button type="submit" class="button is-primary">Obtenir mon programme</button>
                </div>
            </form>
        </section>

        <section class="section">
            <h1 class="title">Vos Programmes</h1>
            <?php if ($programmes): ?>
                <?php foreach ($programmes as $programme): ?>
                    <div class="box">
                        <h2 class="title is-4"><?= htmlspecialchars($programme['objectif']); ?> - <?= htmlspecialchars($programme['niveau']); ?></h2>
                        <p><strong>Fréquence :</strong> <?= htmlspecialchars($programme['frequence']); ?> jours/semaine</p>
                        <pre><?= htmlspecialchars($programme['programme']); ?></pre>
                        <a href="detail_programme.php?id=<?= $programme['id_programme']; ?>" class="button is-link">Voir / Modifier</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Vous n'avez pas encore de programme enregistré.</p>
            <?php endif; ?>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
