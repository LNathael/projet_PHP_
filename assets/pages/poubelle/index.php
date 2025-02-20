<?php
require_once '../config/db.php';
require '../includes/header.php';
$salons = $pdo->query("SELECT * FROM salons")->fetchAll();
?>
<section class="section">
    <div class="container">
        <h1 class="title">💬 MuscleTalk - Salons de discussion</h1>
        <div class="columns is-multiline">
            <?php foreach ($salons as $salon): ?>
                <div class="column is-one-third">
                    <div class="box">
                        <h2 class="title is-5"><?= htmlspecialchars($salon['nom_salon']); ?></h2>
                        <p><?= htmlspecialchars($salon['description']); ?></p>
                        <a href="salon.php?id=<?= $salon['id_salon']; ?>" class="button is-link">Rejoindre</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php require '../includes/footer.php'; ?>