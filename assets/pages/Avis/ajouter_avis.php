<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

include '../../config/db.php';

$type_contenu = $_GET['type_contenu'] ?? '';
$contenu_id = (int)($_GET['contenu_id'] ?? 0);

if (!in_array($type_contenu, ['recette', 'programme']) || !$contenu_id) {
    die("Type de contenu ou ID invalide.");
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $note = (int)($_POST['note'] ?? 0);
    $commentaire = trim($_POST['commentaire'] ?? '');

    if ($note >= 1 && $note <= 5 && $commentaire) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO avis (id_utilisateur, type_contenu, contenu_id, commentaire, note, date_avis) 
                VALUES (:id_utilisateur, :type_contenu, :contenu_id, :commentaire, :note, NOW())
            ");
            $stmt->execute([
                ':id_utilisateur' => $_SESSION['user_id'],
                ':type_contenu' => $type_contenu,
                ':contenu_id' => $contenu_id,
                ':commentaire' => $commentaire,
                ':note' => $note
            ]);
            $message = "Votre avis a été ajouté avec succès.";
        } catch (PDOException $e) {
            $message = "Erreur : " . $e->getMessage();
        }
    } else {
        $message = "Veuillez remplir tous les champs correctement.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Avis</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<main class="container">
    <section class="section">
        <h1 class="title">Ajouter un Avis</h1>

        <?php if ($message): ?>
            <div class="notification is-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="field">
                <label class="label">Note</label>
                <div class="control">
                    <input type="number" name="note" class="input" min="1" max="5" required>
                </div>
            </div>

            <div class="field">
                <label class="label">Commentaire</label>
                <div class="control">
                    <textarea name="commentaire" class="textarea" required></textarea>
                </div>
            </div>

            <div class="control">
                <button type="submit" class="button is-primary">Ajouter l'avis</button>
            </div>
        </form>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>
