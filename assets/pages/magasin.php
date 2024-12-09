<?php
require_once '../config/db.php';

$stmt = $pdo->query("SELECT * FROM produits");
$produits = $stmt->fetchAll();
?>

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
