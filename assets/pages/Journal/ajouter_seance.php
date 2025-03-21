<?php
session_start();
require_once '../config/db.php'; // Inclure la connexion à la base de données

if (!isset($_SESSION['user_id'])) {
    header('Location: ../Connexion/connexion.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$erreurs = [];

// Récupérer les exercices disponibles
$exercices = $pdo->query("SELECT * FROM exercices")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = trim($_POST['date'] ?? '');
    $exercice_ids = $_POST['exercice_ids'] ?? [];
    $poids = $_POST['poids'] ?? [];
    $repetitions = $_POST['repetitions'] ?? [];
    $ressenti = $_POST['ressenti'] ?? [];

    if (empty($date) || empty($exercice_ids) || empty($poids) || empty($repetitions)) {
        $erreurs[] = "Tous les champs obligatoires doivent être remplis.";
    }

    if (empty($erreurs)) {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO entrainements (id_utilisateur, date) VALUES (:id_utilisateur, :date)");
            $stmt->execute([
                ':id_utilisateur' => $user_id,
                ':date' => $date
            ]);

            $id_entrainement = $pdo->lastInsertId();

            foreach ($exercice_ids as $index => $id_exercice) {
                $stmt = $pdo->prepare("INSERT INTO seance_exercice (id_entrainement, id_exercice, poids, repetitions, ressenti) VALUES (:id_entrainement, :id_exercice, :poids, :repetitions, :ressenti)");
                $stmt->execute([
                    ':id_entrainement' => $id_entrainement,
                    ':id_exercice' => $id_exercice,
                    ':poids' => $poids[$index],
                    ':repetitions' => $repetitions[$index],
                    ':ressenti' => $ressenti[$index] ?? ''
                ]);
            }

            $pdo->commit();
            header('Location: journal_entrainement.php');
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            $erreurs[] = "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Séance</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <section class="section">
        <div class="container">
            <h1 class="title">Ajouter une Séance</h1>

            <?php if (!empty($erreurs)): ?>
                <div class="notification is-danger">
                    <?php foreach ($erreurs as $erreur): ?>
                        <p><?= htmlspecialchars($erreur); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" id="seance-form">
                <div class="field">
                    <label class="label">Date</label>
                    <div class="control">
                        <input class="input" type="date" name="date" value="<?= htmlspecialchars($date ?? ''); ?>" required>
                    </div>
                </div>

                <div id="exercises-container">
                    <div class="box exercise-box">
                        <div class="field">
                            <label class="label">Exercice</label>
                            <div class="control">
                                <div class="select">
                                    <select name="exercice_ids[]">
                                        <?php foreach ($exercices as $exercice): ?>
                                            <option value="<?= $exercice['id_exercice']; ?>"><?= htmlspecialchars($exercice['nom']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Poids (kg)</label>
                            <div class="control">
                                <input class="input" type="number" step="0.01" name="poids[]" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Répétitions</label>
                            <div class="control">
                                <input class="input" type="number" name="repetitions[]" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Ressenti</label>
                            <div class="control">
                                <input class="input" type="text" name="ressenti[]">
                            </div>
                        </div>
                        <button type="button" class="button is-danger remove-exercise">Supprimer</button>
                    </div>
                </div>

                <div class="control">
                    <button type="button" class="button is-info" id="add-exercise">Ajouter un exercice</button>
                </div>

                <div class="control">
                    <button class="button is-link is-fullwidth" type="submit">Enregistrer</button>
                </div>
            </form>

            <section class="section">
                <h2 class="title">Récapitulatif de la Séance</h2>
                <div id="recap-container"></div>
            </section>
        </div>
    </section>
    <?php include '../includes/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('exercises-container');
            const recapContainer = document.getElementById('recap-container');

            document.getElementById('add-exercise').addEventListener('click', function () {
                const exerciseTemplate = `
                    <div class="box exercise-box">
                        <div class="field">
                            <label class="label">Exercice</label>
                            <div class="control">
                                <div class="select">
                                    <select name="exercice_ids[]">
                                        <?php foreach ($exercices as $exercice): ?>
                                            <option value="<?= $exercice['id_exercice']; ?>"><?= htmlspecialchars($exercice['nom']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Poids (kg)</label>
                            <div class="control">
                                <input class="input" type="number" step="0.01" name="poids[]" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Répétitions</label>
                            <div class="control">
                                <input class="input" type="number" name="repetitions[]" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Ressenti</label>
                            <div class="control">
                                <input class="input" type="text" name="ressenti[]">
                            </div>
                        </div>
                        <button type="button" class="button is-danger remove-exercise">Supprimer</button>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', exerciseTemplate);
            });

            container.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-exercise')) {
                    e.target.closest('.exercise-box').remove();
                }
            });

            document.getElementById('seance-form').addEventListener('submit', function (e) {
                e.preventDefault();
                recapContainer.innerHTML = '';

                const exercises = document.querySelectorAll('.exercise-box');
                exercises.forEach((exercise, index) => {
                    const exerciceName = exercise.querySelector('select').selectedOptions[0].text;
                    const poids = exercise.querySelector('input[name="poids[]"]').value;
                    const repetitions = exercise.querySelector('input[name="repetitions[]"]').value;
                    const ressenti = exercise.querySelector('input[name="ressenti[]"]').value;

                    const recapTemplate = `
                        <div class="box">
                            <h3 class="title is-5">Exercice ${index + 1}</h3>
                            <p><strong>Nom :</strong> ${exerciceName}</p>
                            <p><strong>Poids :</strong> ${poids} kg</p>
                            <p><strong>Répétitions :</strong> ${repetitions}</p>
                            <p><strong>Ressenti :</strong> ${ressenti}</p>
                        </div>
                    `;
                    recapContainer.insertAdjacentHTML('beforeend', recapTemplate);
                });

                // Soumettre le formulaire après l'affichage du récapitulatif
                this.submit();
            });
        });
    </script>
</body>
</html>