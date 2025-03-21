<?php
session_start();
require_once '../config/db.php'; // Inclure la connexion à la base de données

if (!isset($_SESSION['user_id'])) {
    header('Location: ../Connexion/connexion.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$erreurs = [];

// Récupérer les données pour les graphiques
$stmt = $pdo->prepare("
    SELECT seance_exercice.date AS date_seance, 
           AVG(seance_exercice.poids) AS poids_moyen, 
           AVG(seance_exercice.repetitions) AS repetitions_moyennes 
    FROM seance_exercice 
    JOIN entrainements ON seance_exercice.id_entrainement = entrainements.id_entrainement 
    WHERE entrainements.id_utilisateur = :id_utilisateur 
    GROUP BY seance_exercice.date 
    ORDER BY seance_exercice.date
");
$stmt->execute([':id_utilisateur' => $user_id]);
$performances = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les séances pour le calendrier
$stmt = $pdo->prepare("
    SELECT date 
    FROM entrainements 
    WHERE id_utilisateur = :id_utilisateur 
    ORDER BY date
");
$stmt->execute([':id_utilisateur' => $user_id]);
$seances = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journal d'Entraînement</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.js"></script>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <section class="section">
        <div class="container">
            <h1 class="title">Journal d'Entraînement</h1>

            <div class="buttons">
                <a href="ajouter_exercice.php" class="button is-primary">Ajouter un Exercice</a>
                <a href="ajouter_seance.php" class="button is-info">Ajouter une Séance</a>
            </div>

            <section class="section">
                <h2 class="title">Statistiques</h2>
                <canvas id="performanceChart"></canvas>
            </section>

            <section class="section">
                <h2 class="title">Calendrier des Séances</h2>
                <div id="calendar"></div>
            </section>
        </div>
    </section>
    <?php include '../includes/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialiser le graphique
            const ctx = document.getElementById('performanceChart').getContext('2d');
            const performances = <?= json_encode($performances); ?>;
            const labels = performances.map(performance => performance.date);
            const data = {
                labels: labels,
                datasets: [{
                    label: 'Poids Moyen (kg)',
                    data: performances.map(performance => performance.poids_moyen),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    yAxisID: 'y',
                }, {
                    label: 'Répétitions Moyennes',
                    data: performances.map(performance => performance.repetitions_moyennes),
                    borderColor: 'rgba(153, 102, 255, 1)',
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    yAxisID: 'y1',
                }]
            };
            const config = {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    stacked: false,
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false,
                            },
                        },
                    }
                },
            };
            new Chart(ctx, config);

            // Initialiser le calendrier
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: <?= json_encode(array_map(function($seance) {
                    return [
                        'title' => 'Séance',
                        'start' => $seance['date']
                    ];
                }, $seances)); ?>
            });
            calendar.render();
        });
    </script>
</body>
</html>