<?php 
if (file_exists('../includes/header.php')) {
    include '../includes/header.php';
} else {
    echo '<p>Erreur : Fichier header.php introuvable.</p>';
}
?>
<main class="container mt-5">
    <h1 class="title is-3">Programme pour Débutants</h1>
    <p>Commencez votre parcours de musculation avec ce programme adapté aux débutants :</p>
    <ul class="content">
        <li><strong>Jour 1 : Full Body</strong></li>
        <ul>
            <li>Pompes : 3 séries de 10 répétitions</li>
            <li>Squats : 3 séries de 10 répétitions</li>
            <li>Planche abdominale : 3 séries de 20 secondes</li>
        </ul>
        <li><strong>Jour 2 : Repos ou Marche Active</strong></li>
        <li><strong>Jour 3 : Full Body</strong></li>
        <ul>
            <li>Fentes : 3 séries de 10 répétitions</li>
            <li>Développé militaire avec haltères légers : 3 séries de 12 répétitions</li>
            <li>Mountain climbers : 3 séries de 20 répétitions</li>
        </ul>
        <li><strong>Jour 4 : Repos</strong></li>
    </ul>
</main>
<?php 
if (file_exists('../includes/footer.php')) {
    include '../includes/footer.php';
} else {
    echo '<p>Erreur : Fichier footer.php introuvable.</p>';
}
?>
