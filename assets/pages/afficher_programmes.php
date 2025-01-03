<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

require '../includes/db.php';

$userId = $_SESSION['user_id'];

// Récupération des programmes
$sql = "SELECT * FROM user_programs WHERE user_id = :user_id ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':user_id' => $userId]);
$programmes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vos Programmes Personnalisés</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main class="container">
        <h1>Vos Programmes Personnalisés</h1>
        <?php if ($programmes): ?>
            <ul>
                <?php foreach ($programmes as $programme): ?>
                    <li>
                        <h3><?= htmlspecialchars($programme['objectif']) ?> - <?= htmlspecialchars($programme['niveau']) ?></h3>
                        <p>Fréquence : <?= htmlspecialchars($programme['frequence']) ?> fois/semaine</p>
                        <p><?= nl2br(htmlspecialchars($programme['programme'])) ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucun programme enregistré.</p>
        <?php endif; ?>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
