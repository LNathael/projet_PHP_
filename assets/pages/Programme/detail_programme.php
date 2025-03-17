<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

include '../config/db.php';

if (!isset($_GET['id'])) {
    die("Programme non spécifié.");
}

$programmeId = (int) $_GET['id'];
$userId = $_SESSION['user_id'];

// Récupérer les détails du programme
try {
    $stmt = $pdo->prepare("SELECT * FROM user_programs WHERE id_programme = :id AND user_id = :user_id");
    $stmt->execute(['id' => $programmeId, 'user_id' => $userId]);
    $programme = $stmt->fetch();
    if (!$programme) {
        die("Programme non trouvé.");
    }
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

// Mise à jour du programme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $objectif = trim($_POST['objectif']);
    $frequence = (int) $_POST['frequence'];
    $niveau = trim($_POST['niveau']);
    $programmeText = trim($_POST['programme']);

    try {
        $stmt = $pdo->prepare("UPDATE user_programs SET objectif = :objectif, frequence = :frequence, niveau = :niveau, programme = :programme WHERE id_programme = :id AND user_id = :user_id");
        $stmt->execute([
            'objectif' => $objectif,
            'frequence' => $frequence,
            'niveau' => $niveau,
            'programme' => $programmeText,
            'id' => $programmeId,
            'user_id' => $userId,
        ]);
        header("Location: afficher_programmes.php");
        exit;
    } catch (PDOException $e) {
        die("Erreur lors de la mise à jour : " . $e->getMessage());
    }
}

// Suppression du programme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM user_programs WHERE id_programme = :id AND user_id = :user_id");
        $stmt->execute(['id' => $programmeId, 'user_id' => $userId]);
        header("Location: afficher_programmes.php");
        exit;
    } catch (PDOException $e) {
        die("Erreur lors de la suppression : " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <title>Détail du Programme</title>
</head>
<body>
<?php include '../includes/header.php'; ?>

<main class="container">
    <section class="section">
        <h1 class="title">Détail du Programme</h1>

        <form method="POST">
            <div class="field">
                <label class="label">Objectif</label>
                <div class="control">
                    <input class="input" type="text" name="objectif" value="<?= htmlspecialchars($programme['objectif']); ?>" required>
                </div>
            </div>

            <div class="field">
                <label class="label">Fréquence</label>
                <div class="control">
                    <input class="input" type="number" name="frequence" min="1" max="7" value="<?= htmlspecialchars($programme['frequence']); ?>" required>
                </div>
            </div>

            <div class="field">
                <label class="label">Niveau</label>
                <div class="control">
                    <input class="input" type="text" name="niveau" value="<?= htmlspecialchars($programme['niveau']); ?>" required>
                </div>
            </div>

            <div class="field">
                <label class="label">Programme</label>
                <div class="control">
                    <textarea class="textarea" name="programme" required><?= htmlspecialchars($programme['programme']); ?></textarea>
                </div>
            </div>

            <div class="field is-grouped">
                <div class="control">
                    <button type="submit" name="update" class="button is-success">Mettre à jour</button>
                </div>
                <div class="control">
                    <button type="submit" name="delete" class="button is-danger">Supprimer</button>
                </div>
            </div>
        </form>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>
