<?php
session_start();
require_once '../config/db.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';

// Gestion du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type_contenu = $_POST['type_contenu'] ?? '';
    $contenu_id = (int)$_POST['contenu_id'];
    $commentaire = trim(htmlspecialchars($_POST['commentaire']));
    $note = (int)$_POST['note'];

    // Validation des champs
    if (empty($type_contenu) || empty($contenu_id) || empty($commentaire) || $note < 1 || $note > 5) {
        $message = "Veuillez remplir tous les champs correctement.";
    } else {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO avis (type_contenu, contenu_id, id_utilisateur, commentaire, note) 
                VALUES (:type_contenu, :contenu_id, :id_utilisateur, :commentaire, :note)
            ");
            $stmt->execute([
                'type_contenu' => $type_contenu,
                'contenu_id' => $contenu_id,
                'id_utilisateur' => $user_id,
                'commentaire' => $commentaire,
                'note' => $note
            ]);
            $message = "Avis ajouté avec succès.";
        } catch (PDOException $e) {
            $message = "Erreur lors de l'ajout de l'avis : " . $e->getMessage();
        }
    }
}

// Récupérer les produits et les recettes
$produits = $pdo->query("SELECT id_produit AS id, nom_produit AS nom FROM produits")->fetchAll();
$recettes = $pdo->query("SELECT id_recette AS id, titre AS nom FROM recettes")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <title>Laisser un avis</title>
</head>
<body>
<main class="container">
    <section class="section">
        <h1 class="title">Laisser un avis</h1>
        <p class="subtitle">Choisissez un produit ou une recette pour donner votre avis.</p>

        <!-- Message -->
        <?php if (!empty($message)): ?>
            <div class="notification is-info"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <!-- Formulaire -->
        <form method="POST" class="box">
            <div class="field">
                <label class="label">Type de contenu</label>
                <div class="control">
                    <div class="select is-fullwidth">
                        <select name="type_contenu" id="type_contenu" required>
                            <option value="" disabled selected>Choisissez le type</option>
                            <option value="produit">Produit</option>
                            <option value="recette">Recette</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="field">
                <label class="label">Sélectionnez le contenu</label>
                <div class="control">
                    <div class="select is-fullwidth">
                        <select name="contenu_id" id="contenu_id" required>
                            <option value="" disabled selected>Choisissez un élément</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="field">
                <label class="label">Commentaire</label>
                <div class="control">
                    <textarea class="textarea" name="commentaire" placeholder="Votre commentaire" required></textarea>
                </div>
            </div>

            <div class="field">
                <label class="label">Note</label>
                <div class="control">
                    <input class="input" type="number" name="note" min="1" max="5" placeholder="Note (1-5)" required>
                </div>
            </div>

            <div class="control">
                <button type="submit" class="button is-primary">Soumettre</button>
            </div>
        </form>
    </section>
</main>

<script>
    // Dynamique : produits ou recettes
    const typeContenu = document.getElementById('type_contenu');
    const contenuId = document.getElementById('contenu_id');

    const produits = <?= json_encode($produits); ?>;
    const recettes = <?= json_encode($recettes); ?>;

    typeContenu.addEventListener('change', () => {
        const type = typeContenu.value;
        let options = '';

        if (type === 'produit') {
            produits.forEach(item => {
                options += `<option value="${item.id}">${item.nom}</option>`;
            });
        } else if (type === 'recette') {
            recettes.forEach(item => {
                options += `<option value="${item.id}">${item.nom}</option>`;
            });
        }

        contenuId.innerHTML = `<option value="" disabled selected>Choisissez un élément</option>` + options;
    });
</script>

<?php include '../includes/footer.php'; ?>
</body>
</html>
