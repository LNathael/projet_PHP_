<!DOCTYPE html>
<html lang="fr">
<?php include '../includes/header.php'; ?>
<body>
<main class="container">
    <h1 class="title has-text-centered">Calculateur de Calories</h1>
    <form id="calorie-form" class="box" onsubmit="calculateCalories(event)">
        <div class="field">
            <label class="label">Âge (années) <span class="has-text-danger">*</span></label>
            <div class="control">
                <input class="input" type="number" id="age" name="age" placeholder="Ex : 25" required>
            </div>
        </div>

        <div class="field">
            <label class="label">Sexe <span class="has-text-danger">*</span></label>
            <div class="control">
                <div class="select">
                    <select id="gender" name="gender" required>
                        <option value="" disabled selected>- Sélectionner -</option>
                        <option value="male">Homme</option>
                        <option value="female">Femme</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="field">
            <label class="label">Poids (kg) <span class="has-text-danger">*</span></label>
            <div class="control">
                <input class="input" type="number" id="weight" name="weight" placeholder="Ex : 70" required>
            </div>
        </div>

        <div class="field">
            <label class="label">Taille (cm) <span class="has-text-danger">*</span></label>
            <div class="control">
                <input class="input" type="number" id="height" name="height" placeholder="Ex : 175" required>
            </div>
        </div>

        <div class="field">
            <label class="label">Niveau d'activité <span class="has-text-danger">*</span></label>
            <div class="control">
                <div class="select">
                    <select id="activity" name="activity" required>
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

        <div class="field">
            <label class="label">Objectif <span class="has-text-danger">*</span></label>
            <div class="control">
                <div class="select">
                    <select id="goal" name="goal" required>
                        <option value="" disabled selected>- Sélectionner -</option>
                        <option value="-500">Perte de poids</option>
                        <option value="0">Maintien du poids</option>
                        <option value="500">Prise de masse</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="field has-text-centered">
            <button type="submit" class="button is-primary">Calculer</button>
        </div>
    </form>

    <div id="result" class="notification is-info is-hidden">
        <p id="calorie-result"></p>
    </div>
</main>

<?php include '../includes/footer.php'; ?>

<script>
    // Fonction pour calculer les calories
    function calculateCalories(event) {
        event.preventDefault(); // Empêche l'envoi du formulaire

        // Récupérer les valeurs des champs
        const age = parseInt(document.getElementById('age').value);
        const gender = document.getElementById('gender').value;
        const weight = parseFloat(document.getElementById('weight').value);
        const height = parseFloat(document.getElementById('height').value);
        const activity = parseFloat(document.getElementById('activity').value);
        const goal = parseInt(document.getElementById('goal').value);

        // Vérification des valeurs
        if (!age || !gender || !weight || !height || !activity || goal === null) {
            alert("Veuillez remplir tous les champs correctement.");
            return;
        }

        // Calcul des besoins caloriques de base (TMB - Taux Métabolique Basal)
        let bmr;
        if (gender === "male") {
            // Formule Harris-Benedict pour les hommes
            bmr = 88.362 + (13.397 * weight) + (4.799 * height) - (5.677 * age);
        } else if (gender === "female") {
            // Formule Harris-Benedict pour les femmes
            bmr = 447.593 + (9.247 * weight) + (3.098 * height) - (4.330 * age);
        }

        // Ajuster en fonction du niveau d'activité
        const maintenanceCalories = bmr * activity;

        // Appliquer l'objectif
        const targetCalories = maintenanceCalories + goal;

        // Afficher le résultat
        const resultDiv = document.getElementById('result');
        const resultText = document.getElementById('calorie-result');

        resultText.innerHTML = `
            Vos besoins caloriques journaliers sont estimés à <strong>${Math.round(targetCalories)}</strong> calories.
        `;

        resultDiv.classList.remove('is-hidden');
        return false; // Empêche également toute navigation

    }
</script>
</body>
</html>
