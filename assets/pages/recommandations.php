<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php';

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupération de l'objectif de l'utilisateur
$stmt = $pdo->prepare("SELECT objectif FROM utilisateurs WHERE id_utilisateur = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();

$objectif = $user['objectif'] ?? '';

// Récupération des produits recommandés en fonction de l'objectif
$stmt = $pdo->prepare("SELECT * FROM produits WHERE categorie = :objectif");
$stmt->execute(['objectif' => $objectif]);
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include '../includes/header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <title>Recommandations de Produits</title>
</head>
<body>
<main class="container mt-5">
    <section class="section">
        <h1 class="title">Recommandations de Produits pour <?= htmlspecialchars($objectif); ?></h1>

        <?php if (!empty($produits)): ?>
            <div class="columns is-multiline">
                <?php foreach ($produits as $produit): ?>
                    <div class="column is-one-third">
                        <div class="box">
                            <h2 class="title is-4"><?= htmlspecialchars($produit['nom_produit']); ?></h2>
                            <p><strong>Prix :</strong> <?= htmlspecialchars($produit['prix']); ?> €</p>
                            <?php if (!empty($produit['image'])): ?>
                                <img src="../../<?= htmlspecialchars($produit['image']); ?>" alt="<?= htmlspecialchars($produit['nom_produit']); ?>" style="max-width: 100%; height: auto; margin-top: 10px;">
                            <?php endif; ?>
                            <p><?= htmlspecialchars(substr($produit['description'], 0, 200)) . '...'; ?></p>
                            <a href="detail_produit.php?id=<?= $produit['id_produit']; ?>" class="button is-link">Voir le produit</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucun produit trouvé pour cet objectif.</p>
        <?php endif; ?>
    </section>
</main>
<?php include '../includes/footer.php'; ?>
</body>
</html>