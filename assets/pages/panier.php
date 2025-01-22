<?php
session_start();
require_once '../config/db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: connexion.php');
    exit;
}

// Récupérer l'ID utilisateur depuis la session
$id_utilisateur = $_SESSION['id_utilisateur'];

// Actions sur le panier
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id_produit = $_POST['id_produit'] ?? null;
    $quantite = intval($_POST['quantite'] ?? 1);

    switch ($action) {
        case 'ajouter':
            // Vérifier si le produit existe déjà dans le panier
            $stmt = $pdo->prepare("SELECT * FROM panier WHERE id_utilisateur = ? AND id_produit = ?");
            $stmt->execute([$id_utilisateur, $id_produit]);
            $produit_panier = $stmt->fetch();

            if ($produit_panier) {
                // Si le produit existe déjà, incrémentez sa quantité
                $stmt = $pdo->prepare("UPDATE panier SET quantite = quantite + ? WHERE id_utilisateur = ? AND id_produit = ?");
                $stmt->execute([$quantite, $id_utilisateur, $id_produit]);
            } else {
                // Sinon, ajoutez le produit au panier
                $stmt = $pdo->prepare("INSERT INTO panier (id_utilisateur, id_produit, quantite) VALUES (?, ?, ?)");
                $stmt->execute([$id_utilisateur, $id_produit, $quantite]);
            }
            break;

        case 'augmenter':
            $stmt = $pdo->prepare("UPDATE panier SET quantite = quantite + 1 WHERE id_utilisateur = ? AND id_produit = ?");
            $stmt->execute([$id_utilisateur, $id_produit]);
            break;

        case 'diminuer':
            $stmt = $pdo->prepare("UPDATE panier SET quantite = quantite - 1 WHERE id_utilisateur = ? AND id_produit = ?");
            $stmt->execute([$id_utilisateur, $id_produit]);

            // Supprimer si la quantité atteint zéro
            $stmt = $pdo->prepare("DELETE FROM panier WHERE id_utilisateur = ? AND id_produit = ? AND quantite <= 0");
            $stmt->execute([$id_utilisateur, $id_produit]);
            break;

        case 'supprimer':
            $stmt = $pdo->prepare("DELETE FROM panier WHERE id_utilisateur = ? AND id_produit = ?");
            $stmt->execute([$id_utilisateur, $id_produit]);
            break;

        case 'vider':
            $stmt = $pdo->prepare("DELETE FROM panier WHERE id_utilisateur = ?");
            $stmt->execute([$id_utilisateur]);
            break;
    }
}

// Récupérer les produits du panier
$stmt = $pdo->prepare("
    SELECT p.nom_produit, p.prix, pa.quantite, pa.id_produit
    FROM panier pa
    JOIN produits p ON pa.id_produit = p.id_produit
    WHERE pa.id_utilisateur = ?
");
$stmt->execute([$id_utilisateur]);
$panier = $stmt->fetchAll();
?>


<?php include 'includes/header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre Panier</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="title has-text-centered">Votre Panier</h1>

        <?php if (!empty($panier)): ?>
            <table class="table is-striped is-fullwidth">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix Unitaire</th>
                        <th>Quantité</th>
                        <th>Sous-total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($panier as $item):
                        $sous_total = $item['prix'] * $item['quantite'];
                        $total += $sous_total;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nom_produit']); ?></td>
                            <td><?= number_format($item['prix'], 2); ?> €</td>
                            <td><?= $item['quantite']; ?></td>
                            <td><?= number_format($sous_total, 2); ?> €</td>
                            <td>
                                <form method="POST" class="is-inline-block">
                                    <input type="hidden" name="id_produit" value="<?= $item['id_produit']; ?>">
                                    <button type="submit" name="action" value="augmenter" class="button is-small is-success">+</button>
                                </form>
                                <form method="POST" class="is-inline-block">
                                    <input type="hidden" name="id_produit" value="<?= $item['id_produit']; ?>">
                                    <button type="submit" name="action" value="diminuer" class="button is-small is-warning">-</button>
                                </form>
                                <form method="POST" class="is-inline-block">
                                    <input type="hidden" name="id_produit" value="<?= $item['id_produit']; ?>">
                                    <button type="submit" name="action" value="supprimer" class="button is-small is-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="has-text-right">Total :</th>
                        <th colspan="2"><?= number_format($total, 2); ?> €</th>
                    </tr>
                </tfoot>
            </table>

            <div class="buttons">
                <form method="POST">
                    <button type="submit" name="action" value="vider" class="button is-danger">Vider le panier</button>
                </form>
            </div>
        <?php else: ?>
            <p class="has-text-centered">Votre panier est vide.</p>
        <?php endif; ?>

        <div class="buttons mt-5">
            <a href="magasin.php" class="button is-link">Retour au magasin</a>
        </div>
    </div>
</body>
</html>

<?php include 'includes/footer.php'; ?>