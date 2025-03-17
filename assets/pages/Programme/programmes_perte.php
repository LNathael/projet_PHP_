<?php 
if (file_exists('../includes/header.php')) {
    include '../includes/header.php';
} else {
    echo '<p>Erreur : Fichier header.php introuvable.</p>';
}
?>
<main class="container mt-5">
    <h1 class="title is-3">Programme de Perte de Poids</h1>
    <p>Brûlez les graisses efficacement avec ce programme :</p>
    <ul class="content">
        <li><strong>Jour 1 : Cardio & Abdos</strong></li>
        <ul>
            <li>Course à pied : 30 minutes</li>
            <li>Planche abdominale : 4 séries de 30 secondes</li>
            <li>Crunches : 3 séries de 15 répétitions</li>
        </ul>
        <li><strong>Jour 2 : Entraînement en circuit</strong></li>
        <ul>
            <li>Burpees : 3 séries de 15 répétitions</li>
            <li>Sauts à la corde : 3 séries de 2 minutes</li>
            <li>Squats poids de corps : 4 séries de 10 répétitions</li>
        </ul>
        <li><strong>Jour 3 : Repos</strong></li>
        <li><strong>Jour 4 : HIIT (Entraînement par intervalles)</strong></li>
        <ul>
            <li>Sprints : 20 secondes à fond, 40 secondes de récupération (8 cycles)</li>
            <li>Mountain climbers : 3 séries de 20 répétitions</li>
            <li>Pompes : 3 séries jusqu'à l'échec</li>
        </ul>
    </ul>
</main>
<?php 
if (file_exists('../includes/footer.php')) {
    include '../includes/footer.php';
} else {
    echo '<p>Erreur : Fichier footer.php introuvable.</p>';
}
?>
