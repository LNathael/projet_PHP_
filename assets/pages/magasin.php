<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php'; // Inclure la connexion à la base de données

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
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <main class="container">
        <h1 class="title has-text-centered mt-5">Magasin</h1>
        <div class="columns is-multiline">
            <?php foreach ($produits as $produit): ?>
                <div class="column is-one-third">
                    <div class="card">
                    <?php if (!empty($produit['image'])): ?>
                        <div class="card-image">
                        <a href="detail_produit.php?id=<?= $produit['id_produit']; ?>">
                            <figure class="image is-4by3">
                                <img src="../../<?= htmlspecialchars($produit['image']); ?>" alt="<?= htmlspecialchars($produit['nom_produit']); ?>">
                            </figure>
                        </a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-content">
                            <a href="detail_produit.php?id=<?= $produit['id_produit']; ?>">
                                <h2 class="title is-5"><?= htmlspecialchars($produit['nom_produit']); ?></h2>
                            </a>
                            <p><?= nl2br(htmlspecialchars($produit['description'])); ?></p>
                            <p class="is-size-6 has-text-weight-bold">Prix : <?= number_format($produit['prix'], 2, ',', ' '); ?> €</p>
                            <?php if (isset($_SESSION['id_utilisateur'])): ?>
                            <---!revoir la connexion dans magasin et paniers!>
                                <?php if ($produit['quantite_disponible'] > 0): ?>
                                    <form method="POST" action="panier.php" class="mt-3">
                                        <input type="hidden" name="id_produit" value="<?= $produit['id_produit']; ?>">
                                        <div class="field has-addons">
                                            <div class="control">
                                                <input class="input" type="number" 
                                                       name="quantite" value="1" 
                                                       min="1" max="<?= $produit['quantite_disponible']; ?>">
                                            </div>
                                            <div class="control">
                                                <button type="submit" name="action" value="ajouter" class="button is-primary">
                                                    Ajouter au panier
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                <?php else: ?>
                                    <p class="has-text-danger">Produit en rupture de stock.</p>
                                <?php endif; ?>
                            <?php else: ?>
                                <p class="has-text-danger">
                                    Veuillez <a href="connexion.php?redirect=magasin.php">vous connecter</a> pour ajouter des produits au panier.
                                </p>                            
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
