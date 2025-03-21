<!DOCTYPE html>
<html lang="fr">
<?php include '../includes/header.php'; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculateur</title>
    
    <!-- Polices Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles principaux -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<main class="container mt-5">
    <h1 class="title has-text-centered">Calculateur de Calories</h1>
    <form id="calorie-form" action="calculateur_calories.php" method="POST" class="box">
        <!-- Champ Âge -->
        <div class="field">
            <label class="label">Âge (années) <span class="has-text-danger">*</span></label>
            <div class="control">
                <input class="input" type="number" name="age" placeholder="Ex : 25" required>

                
            </div>
        </div>

        <!-- Champ Sexe -->
        <div class="field">
            <label class="label">Sexe <span class="has-text-danger">*</span></label>
            <div class="control">
                <div class="select">
                    <select name="gender" required>
                        <option value="" disabled selected>- Sélectionner -</option>
                        <option value="male">Homme</option>
                        <option value="female">Femme</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Champ Poids -->
        <div class="field">
            <label class="label">Poids (kg) <span class="has-text-danger">*</span></label>
            <div class="control">
                <input class="input" type="number" name="weight" placeholder="Ex : 70" required>
            </div>
        </div>

        <!-- Champ Taille -->
        <div class="field">
            <label class="label">Taille (cm) <span class="has-text-danger">*</span></label>
            <div class="control">
                <input class="input" type="number" name="height" placeholder="Ex : 175" required>
            </div>
        </div>

        <!-- Champ Niveau d'activité -->
        <div class="field">
            <label class="label">Niveau d'activité <span class="has-text-danger">*</span></label>
            <div class="control">
                <div class="select">
                    <select name="activity" required>
                        <option value="" disabled selected>- Sélectionner -</option>
                        <option value="1.2">Sédentaire (peu ou pas d'exercice)</option>
                        <option value="1.375">Activité légère (1-3 jours/semaine)</option>
                        <option value="1.55">Activité modérée (3-5 jours/semaine)</option>
                        <option value="1.725">Activité intense (6-7 jours/semaine)</option>
                        <option value="1.9">Activité très intense (travail physique ou athlète)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Champ Objectif -->
        <div class="field">
            <label class="label">Objectif <span class="has-text-danger">*</span></label>
            <div class="control">
                <div class="select">
                    <select name="goal" required>
                        <option value="" disabled selected>- Sélectionner -</option>
                        <option value="-500">Perte de poids</option>
                        <option value="0">Maintien du poids</option>
                        <option value="500">Prise de masse</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Bouton Calculer -->
        <div class="field has-text-centered">
            <button type="submit" class="button is-primary">Calculer</button>
        </div>
    </form>

    <!-- Résultats -->
    <?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $age = htmlspecialchars($_POST['age']);
    $gender = htmlspecialchars($_POST['gender']);
    $weight = htmlspecialchars($_POST['weight']);
    $height = htmlspecialchars($_POST['height']);
    $activity = htmlspecialchars($_POST['activity']);
    $goal = htmlspecialchars($_POST['goal']);

    if (!empty($age) && !empty($gender) && !empty($weight) && !empty($height) && !empty($activity) && isset($goal)) {
        // Calcul du BMR
        $bmr = ($gender === "male")
            ? 88.362 + (13.397 * $weight) + (4.799 * $height) - (5.677 * $age)
            : 447.593 + (9.247 * $weight) + (3.098 * $height) - (4.330 * $age);

        // Calcul des besoins caloriques
        $maintenanceCalories = $bmr * $activity;
        $targetCalories = $maintenanceCalories + $goal;

        // Afficher les résultats
        echo "<div class='box mt-5'>";
        echo "<h2 class='title is-4 has-text-centered'>Vos résultats :</h2>";
        echo "<div class='content'>";
        echo "<ul>";
        echo "<li><strong>Âge :</strong> $age ans</li>";
        echo "<li><strong>Sexe :</strong> " . ($gender === "male" ? "Homme" : "Femme") . "</li>";
        echo "<li><strong>Poids :</strong> $weight kg</li>";
        echo "<li><strong>Taille :</strong> $height cm</li>";
        echo "<li><strong>Niveau d'activité :</strong> " . getActivityLabel($activity) . "</li>";
        echo "<li><strong>Objectif :</strong> " . getGoalLabel($goal) . "</li>";
        echo "</ul>";
        echo "</div>";
        echo "<hr>";
        echo "<div class='has-text-centered'>";
        echo "<p class='is-size-5'><strong>Besoins caloriques pour maintenir votre poids :</strong></p>";
        echo "<p class='is-size-4 has-text-success'><strong>" . round($maintenanceCalories) . " calories</strong></p>";
        echo "<p class='is-size-5'><strong>Besoins pour atteindre votre objectif :</strong></p>";
        echo "<p class='is-size-4 has-text-danger'><strong>" . round($targetCalories) . " calories</strong></p>";
        echo "</div>";
        echo "</div>";
    } else {
        echo "<div class='notification is-danger'>Veuillez remplir tous les champs correctement.</div>";
    }
}

// Obtenir un label pour le niveau d'activité
function getActivityLabel($activity) {
    $labels = [
        "1.2" => "Sédentaire (peu ou pas d'exercice)",
        "1.375" => "Activité légère (1-3 jours/semaine)",
        "1.55" => "Activité modérée (3-5 jours/semaine)",
        "1.725" => "Activité intense (6-7 jours/semaine)",
        "1.9" => "Activité très intense (travail physique ou athlète)"
    ];
    return $labels[$activity] ?? "Niveau d'activité inconnu";
}

// Obtenir un label pour l'objectif
function getGoalLabel($goal) {
    if ($goal == -500) return "Perte de poids";
    if ($goal == 0) return "Maintien du poids";
    if ($goal == 500) return "Prise de masse";
    return "Objectif inconnu";
}
?>

</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>
