<?php
session_start();
require_once '../config/db.php';
include '../includes/header.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_produit = $_POST['id_produit'];
    $commentaire = htmlspecialchars($_POST['commentaire']);
    $note = (int)$_POST['note'];

    $stmt = $pdo->prepare("INSERT INTO avis (id_produit, id_utilisateur, commentaire, note) VALUES (:id_produit, :id_utilisateur, :commentaire, :note)");
    $stmt->execute([
        'id_produit' => $id_produit,
        'id_utilisateur' => $user_id,
        'commentaire' => $commentaire,
        'note' => $note
    ]);

    echo "Avis ajouté avec succès.";
}

$produits = $pdo->query("SELECT * FROM produits")->fetchAll();
?>

<h1>Laisser un avis</h1>
<form method="POST">
    <select name="id_produit" required>
        <option value="" disabled selected>Choisissez un produit</option>
        <?php foreach ($produits as $produit): ?>
            <option value="<?= $produit['id_produit']; ?>"><?= htmlspecialchars($produit['nom_produit']); ?></option>
        <?php endforeach; ?>
    </select>
    <textarea name="commentaire" placeholder="Votre commentaire" required></textarea>
    <input type="number" name="note" min="1" max="5" placeholder="Note (1-5)" required>
    <button type="submit">Soumettre</button>
</form>

<?php include '../includes/footer.php';?>
