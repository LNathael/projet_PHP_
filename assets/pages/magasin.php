<?php
require_once '../config/db.php';

// Récupération des produits depuis la base de données
$stmt = $pdo->query("SELECT * FROM produits");
$produits = $stmt->fetchAll();
?>
<?php include '../includes/header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magasin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <main class="container">
        <h1 class="title has-text-centered mt-5">Magasin</h1>
        <div class="columns is-multiline">
            <?php foreach ($produits as $produit): ?>
                <div class="column is-one-third">
                    <div class="card">
                        <div class="card-content">
                            <h2 class="title is-5"><?= htmlspecialchars($produit['nom_produit']); ?></h2>
                            <p><?= nl2br(htmlspecialchars($produit['description'])); ?></p>
                            <p class="is-size-6 has-text-weight-bold">Prix : <?= number_format($produit['prix'], 2, ',', ' '); ?> €</p>
                            <form method="POST" action="panier.php" class="mt-3">
                                <input type="hidden" name="id_produit" value="<?= $produit['id_produit']; ?>">
                                <div class="field has-addons">
                                    <div class="control">
                                        <input class="input" type="number" name="quantite" value="1" min="1">
                                    </div>
                                    <div class="control">
                                        <button type="submit" name="action" value="ajouter" class="button is-primary">Ajouter au panier</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
