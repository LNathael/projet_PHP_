<?php 
if (file_exists('../includes/header.php')) {
    include '../includes/header.php';
} else {
    echo '<p>Erreur : Fichier header.php introuvable.</p>';
}
?>
<main class="container mt-5">
    <h1 class="title is-3">Programme de Prise de Masse</h1>
    <p>Optimisez votre prise de masse avec ce programme structuré :</p>
    <ul class="content">
        <li><strong>Jour 1 : Pectoraux & Triceps</strong></li>
        <ul>
            <li>Développé couché : 4 séries de 10 répétitions</li>
            <li>Pompes : 4 séries jusqu'à l'échec</li>
            <li>Extensions triceps : 3 séries de 12 répétitions</li>
        </ul>
        <li><strong>Jour 2 : Dos & Biceps</strong></li>
        <ul>
            <li>Tractions : 4 séries jusqu'à l'échec</li>
            <li>Rowing barre : 4 séries de 10 répétitions</li>
            <li>Curl biceps : 3 séries de 12 répétitions</li>
        </ul>
        <li><strong>Jour 3 : Repos</strong></li>
            <br></br>
        <li><strong>Jour 4 : Jambes & Épaules</strong></li>
        <ul>
            <li>Squats : 4 séries de 10 répétitions</li>
            <li>Fentes : 3 séries de 12 répétitions</li>
            <li>Développé militaire : 4 séries de 10 répétitions</li>
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
