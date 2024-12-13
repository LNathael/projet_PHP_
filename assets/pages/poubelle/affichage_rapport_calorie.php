<?php
// Vérifier si les données sont passées en GET
if (isset($_GET['age'], $_GET['gender'], $_GET['weight'], $_GET['height'], $_GET['activity'], $_GET['goal'])) {
    // Récupérer les valeurs envoyées via GET
    $age = htmlspecialchars($_GET['age']);
    $gender = htmlspecialchars($_GET['gender']);
    $weight = htmlspecialchars($_GET['weight']);
    $height = htmlspecialchars($_GET['height']);
    $activity = htmlspecialchars($_GET['activity']);
    $goal = htmlspecialchars($_GET['goal']);

    // Vérification des champs vides ou invalides
    if (!empty($age) && !empty($gender) && !empty($weight) && !empty($height) && !empty($activity) && !empty($goal)) {
        // Calcul des besoins caloriques
        $bmr = 0; // Basal Metabolic Rate (Métabolisme de base)
        if ($gender === "male") {
            $bmr = 88.362 + (13.397 * $weight) + (4.799 * $height) - (5.677 * $age);
        } elseif ($gender === "female") {
            $bmr = 447.593 + (9.247 * $weight) + (3.098 * $height) - (4.330 * $age);
        }

        // Besoins caloriques de maintien
        $maintenanceCalories = $bmr * $activity;

        // Besoins caloriques en fonction de l'objectif
        $targetCalories = $maintenanceCalories + $goal;

        // Afficher les résultats
        echo "<h1>Résultats de votre recherche</h1>";
        echo "<p>Âge : <strong>$age ans</strong></p>";
        echo "<p>Sexe : <strong>" . ($gender === "male" ? "Homme" : "Femme") . "</strong></p>";
        echo "<p>Poids : <strong>$weight kg</strong></p>";
        echo "<p>Taille : <strong>$height cm</strong></p>";
        echo "<p>Niveau d'activité : <strong>$activity</strong></p>";
        echo "<p>Objectif : <strong>" . ($goal < 0 ? "Perte de poids" : ($goal > 0 ? "Prise de masse" : "Maintien")) . "</strong></p>";
        echo "<hr>";
        echo "<h2>Résultats :</h2>";
        echo "<p>Vos besoins caloriques journaliers pour maintenir votre poids : <strong>" . round($maintenanceCalories) . " calories</strong>.</p>";
        echo "<p>Pour atteindre votre objectif : <strong>" . round($targetCalories) . " calories</strong>.</p>";
    } else {
        // Message d'erreur si les champs sont vides ou invalides
        echo "<h1>Erreur</h1>";
        echo "<p>Un ou plusieurs champs sont vides ou invalides. Veuillez remplir le formulaire correctement.</p>";
    }
} else {
    // Message d'erreur si aucun paramètre n'est passé
    echo "<h1>Erreur</h1>";
    echo "<p>Aucune donnée n'a été envoyée. Veuillez revenir au formulaire et réessayer.</p>";
}
?>
