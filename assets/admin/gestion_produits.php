
<?php
// mettre un lien de cette page dans la page gestion administrateur ( cela crée un produit)

session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrateur') {
    header('Location: ../connexion.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom_produit']);
    $description = htmlspecialchars($_POST['description']);
    $prix = (float)$_POST['prix'];
    $quantite = (int)$_POST['quantite'];
    $libelle = htmlspecialchars($_POST['libelle']);

    $stmt = $pdo->prepare("INSERT INTO produits (nom_produit, description, prix, quantite_disponible, libelle) VALUES (:nom, :description, :prix, :quantite, :libelle)");
    $stmt->execute([
        'nom' => $nom,
        'description' => $description,
        'prix' => $prix,
        'quantite' => $quantite,
        'libelle' => $libelle
    ]);

    echo "Produit ajouté avec succès.";
}

$produits = $pdo->query("SELECT * FROM produits")->fetchAll();
?>

<h1>Gestion des Produits</h1>
<form method="POST">
    <input type="text" name="nom_produit" placeholder="Nom du produit" required>
    <textarea name="description" placeholder="Description" required></textarea>
    <input type="number" name="prix" step="0.01" placeholder="Prix" required>
    <input type="number" name="quantite" placeholder="Quantité disponible" required>
    <input type="text" name="libelle" placeholder="Catégorie" required>
    <button type="submit">Ajouter</button>
</form>

<h2>Produits existants</h2>
<ul>
    <?php foreach ($produits as $produit): ?>
        <li><?= htmlspecialchars($produit['nom_produit']); ?> - <?= htmlspecialchars($produit['prix']); ?> €</li>
    <?php endforeach; ?>
</ul>
