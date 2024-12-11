<?php
require_once '../config/db.php';

$stmt = $pdo->query("SELECT * FROM produits");
$produits = $stmt->fetchAll();
?>

<?php include '../includes/header.php';?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magasin</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
<h1>Magasin</h1>
<div class="produits">
    <?php foreach ($produits as $produit): ?>
        <div class="produit">
            <h2><?= htmlspecialchars($produit['nom_produit']); ?></h2>
            <p><?= htmlspecialchars($produit['description']); ?></p>
            <p>Prix : <?= htmlspecialchars($produit['prix']); ?> €</p>
            <form method="POST" action="panier.php">
                <input type="hidden" name="id_produit" value="<?= $produit['id_produit']; ?>">
                <input type="number" name="quantite" value="1" min="1">
                <button type="submit">Ajouter au panier</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>
</body>
<?php include '../includes/footer.php';?>