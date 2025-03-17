<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php'; // Inclure la connexion à la base de données

// Récupération des produits depuis la base de données
$stmt = $pdo->query("SELECT * FROM produits");
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include '../includes/header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magasin</title>
    
    <!-- Polices Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles principaux -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <main class="container">
        <section class="store-header">
            <h1 class="store-title">Notre Magasin</h1>
        </section>
        
        <div class="columns is-multiline is-variable is-3">
            <?php if (!empty($produits)): ?>
                <?php foreach ($produits as $produit): ?>
                    <div class="column is-one-third-desktop is-half-tablet">
                        <div class="product-card">
                            <div class="product-image-wrapper">
                                <?php if (!empty($produit['image'])): ?>
                                    <a href="../Produit/detail_produit.php? <?= $produit['id_produit']; ?>">
                                        <img src="../../../?= htmlspecialchars($produit['image']); ?>" 
                                             alt="<?= htmlspecialchars($produit['nom_produit']); ?>" 
                                             class="product-image">
                                    </a>
                                <?php endif; ?>
                            </div>
                            <div class="product-content">
                                <div>
                                    <a href="detail_produit.php?id=<?= $produit['id_produit']; ?>">
                                        <h2 class="product-title"><?= htmlspecialchars($produit['nom_produit']); ?></h2>
                                    </a>
                                    <p class="product-description"><?= nl2br(htmlspecialchars($produit['description'])); ?></p>
                                    <p class="product-price"><?= number_format($produit['prix'], 2, ',', ' '); ?> €</p>
                                </div>
                                
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <?php if ($produit['quantite_disponible'] > 0): ?>
                                        <form method="POST" action="../Panier/panier.php" class="add-to-cart-form">
                                            <input type="hidden" name="id_produit" value="<?= $produit['id_produit']; ?>">
                                            <div class="field has-addons">
                                                <div class="control">
                                                    <input class="input quantity-input" type="number" name="quantite" 
                                                           value="1" min="1" max="<?= $produit['quantite_disponible']; ?>">
                                                </div>
                                                <div class="control">
                                                    <button type="submit" name="action" value="ajouter" 
                                                            class="button is-primary">
                                                        <span class="icon">
                                                            <i class="fas fa-cart-plus"></i>
                                                        </span>
                                                        <span>Ajouter</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    <?php else: ?>
                                        <p class="out-of-stock">
                                            <i class="fas fa-exclamation-circle"></i>
                                            Rupture de stock
                                        </p>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <p class="login-message">
                                        <i class="fas fa-lock"></i>
                                        Veuillez <a href="connexion.php?redirect=magasin.php">vous connecter</a> 
                                        pour ajouter des produits au panier
                                    </p>                            
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="column is-full">
                    <p class="has-text-centered is-size-4">Aucun produit disponible pour le moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>