<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$programme = null;

// Initialiser les variables pour éviter les avertissements
$objectif = '';
$frequence = 0;
$niveau = '';
$preference = '';

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=projet_php;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire avec une vérification par défaut
    $objectif = $_POST['objectif'] ?? '';
    $frequence = isset($_POST['frequence']) ? (int) $_POST['frequence'] : 0;
    $niveau = $_POST['niveau'] ?? '';
    $preference = $_POST['preference'] ?? '';

    $programme = [];

    // Vérification si l'objectif, la fréquence et le niveau sont définis avant de continuer
    if (empty($objectif) || empty($niveau)) {
        echo "Veuillez remplir tous les champs requis.";
        exit;
    }


// Lister les programmes selon l'objectif, la fréquence et le niveau
// Ajoutez ici votre logique de génération du programme...
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
    
    
        } else {
            $programme = ["Programme générique adapté à vos besoins."];
        }

   // Ajustement selon la fréquence
    if ($frequence > 0 && $frequence < count($programme)) {
        $programme = array_slice($programme, 0, $frequence);
    }     
   // Enregistrement du programme dans la base de données
   if ($programme) {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO user_programs (user_id, objectif, frequence, niveau, programme)
            VALUES (:user_id, :objectif, :frequence, :niveau, :programme)
        ");
        $stmt->execute([
            'user_id' => $_SESSION['user_id'],
            'objectif' => $objectif,
            'frequence' => $frequence,
            'niveau' => $niveau,
            'programme' => implode("\n", $programme), // Convertir le tableau en chaîne
        ]);
    } catch (PDOException $e) {
        echo "Erreur lors de l'enregistrement du programme : " . $e->getMessage();
        exit;
    }
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programmes personnalisés</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main class="container">
        <section class="section">
            <h1 class="title">Vos programmes personnalisés</h1>
            <p class="content">Remplissez le formulaire pour obtenir un programme adapté :</p>

            <!-- Formulaire enrichi -->
            <form method="POST" action="">
                <div class="field">
                    <label class="label">Votre objectif</label>
                    <div class="control">
                        <select name="objectif" class="input" required>
                            <option value="">-- Sélectionnez --</option>
                            <option value="perte de poids" <?= $objectif == 'perte de poids' ? 'selected' : '' ?>>Perte de poids</option>
                            <option value="gain de masse" <?= $objectif == 'gain de masse' ? 'selected' : '' ?>>Gain de masse</option>
                            <option value="tonification" <?= $objectif == 'tonification' ? 'selected' : '' ?>>Tonification</option>
                            <option value="amélioration cardio" <?= $objectif == 'amélioration cardio' ? 'selected' : '' ?>>Amélioration cardio</option>
                            <option value="amélioration cardio" <?= $objectif == 'powerlifting' ? 'selected' : '' ?>>powerlifting</option>
                        </select>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Fréquence d'entraînement (jours/semaine)</label>
                    <div class="control">
                        <input type="number" name="frequence" class="input" min="1" max="7" placeholder="1 à 7" value="<?= $frequence ?>" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Votre niveau</label>
                    <div class="control">
                        <select name="niveau" class="input" required>
                            <option value="">-- Sélectionnez --</option>
                            <option value="débutant" <?= $niveau == 'débutant' ? 'selected' : '' ?>>Débutant</option>
                            <option value="intermédiaire" <?= $niveau == 'intermédiaire' ? 'selected' : '' ?>>Intermédiaire</option>
                            <option value="avancé" <?= $niveau == 'avancé' ? 'selected' : '' ?>>Avancé</option>
                        </select>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Préférences d'exercices</label>
                    <div class="control">
                        <select name="preference" class="input">
                            <option value="">-- Pas de préférence --</option>
                            <option value="exercices au poids du corps" <?= $preference == 'exercices au poids du corps' ? 'selected' : '' ?>>Exercices au poids du corps</option>
                            <option value="entraînement avec machines" <?= $preference == 'entraînement avec machines' ? 'selected' : '' ?>>Entraînement avec machines</option>
                            <option value="exercices en extérieur" <?= $preference == 'exercices en extérieur' ? 'selected' : '' ?>>Exercices en extérieur</option>
                        </select>
                    </div>
                </div>

                <div class="control">
                    <button type="submit" class="button is-primary">Obtenir mon programme</button>
                </div>
            </form>
        </section>

        <?php if ($programme): ?>
            <section class="section">
                <h2 class="title is-4">Votre programme personnalisé</h2>
                <ul>
                    <?php foreach ($programme as $item): ?>
                        <li><?= htmlspecialchars($item) ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>