<?php
session_start();
require_once '../config/db.php';

// Vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrateur') {
    header('Location: connexion.php');
    exit;
}

// Supprimer un utilisateur
if (isset($_POST['delete_user'])) {
    $id_utilisateur = (int)$_POST['id_utilisateur'];
    $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = :id");
    $stmt->execute(['id' => $id_utilisateur]);
}

// Modifier un utilisateur
if (isset($_POST['edit_user'])) {
    $id_utilisateur = (int)$_POST['id_utilisateur'];
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $role = $_POST['role'];

    $stmt = $pdo->prepare("UPDATE utilisateurs SET nom = :nom, prenom = :prenom, email = :email, role = :role WHERE id_utilisateur = :id");
    $stmt->execute([
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'role' => $role,
        'id' => $id_utilisateur,
    ]);
}

// Supprimer un avis
if (isset($_POST['delete_avis'])) {
    $id_avis = (int)$_POST['id_avis'];
    $stmt = $pdo->prepare("DELETE FROM avis WHERE id_avis = :id");
    $stmt->execute(['id' => $id_avis]);
}

// Supprimer une recette
if (isset($_POST['delete_recette'])) {
    $id_recette = (int)$_POST['id_recette'];
    $stmt = $pdo->prepare("DELETE FROM recettes WHERE id_recette = :id");
    $stmt->execute(['id' => $id_recette]);
}

// Récupérer les données
$utilisateurs = $pdo->query("SELECT * FROM utilisateurs ORDER BY date_creation DESC")->fetchAll(PDO::FETCH_ASSOC);
$avis = $pdo->query("SELECT a.*, u.nom AS nom_utilisateur, u.prenom AS prenom_utilisateur 
                     FROM avis a 
                     JOIN utilisateurs u ON a.id_utilisateur = u.id_utilisateur 
                     ORDER BY a.date_avis DESC")->fetchAll(PDO::FETCH_ASSOC);
$recettes = $pdo->query("SELECT r.*, u.nom AS nom_utilisateur, u.prenom AS prenom_utilisateur 
                         FROM recettes r 
                         JOIN utilisateurs u ON r.id_utilisateur = u.id_utilisateur 
                         ORDER BY r.date_creation DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<main class="container">
    <h1 class="title mt-5">Page de Gestion Administrateur</h1>

    <!-- Section Gestion Utilisateurs -->
    <section class="section">
        <h2 class="title is-4">Gestion des utilisateurs</h2>
        <table class="table is-striped is-fullwidth">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id_utilisateur']); ?></td>
                        <td><?= htmlspecialchars($user['nom']); ?></td>
                        <td><?= htmlspecialchars($user['prenom']); ?></td>
                        <td><?= htmlspecialchars($user['email']); ?></td>
                        <td><?= htmlspecialchars($user['role']); ?></td>
                        <td>
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="id_utilisateur" value="<?= $user['id_utilisateur']; ?>">
                                <button type="submit" name="delete_user" class="button is-danger is-small">Supprimer</button>
                            </form>
                            <button class="button is-link is-small" onclick="openEditUser(<?= htmlspecialchars(json_encode($user)); ?>)">Modifier</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <!-- Section Gestion Avis -->
    <section class="section">
        <h2 class="title is-4">Gestion des avis</h2>
        <table class="table is-striped is-fullwidth">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Note</th>
                    <th>Commentaire</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($avis as $avis_item): ?>
                    <tr>
                        <td><?= htmlspecialchars($avis_item['id_avis']); ?></td>
                        <td><?= htmlspecialchars($avis_item['prenom_utilisateur'] . ' ' . $avis_item['nom_utilisateur']); ?></td>
                        <td><?= htmlspecialchars($avis_item['note']); ?>/5</td>
                        <td><?= htmlspecialchars(substr($avis_item['commentaire'], 0, 50)) . '...'; ?></td>
                        <td><?= htmlspecialchars($avis_item['type_contenu']); ?></td>
                        <td>
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="id_avis" value="<?= $avis_item['id_avis']; ?>">
                                <button type="submit" name="delete_avis" class="button is-danger is-small">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <!-- Section Gestion Recettes -->
    <section class="section">
        <h2 class="title is-4">Gestion des recettes</h2>
        <table class="table is-striped is-fullwidth">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Titre</th>
                    <th>Catégorie</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recettes as $recette): ?>
                    <tr>
                        <td><?= htmlspecialchars($recette['id_recette']); ?></td>
                        <td><?= htmlspecialchars($recette['prenom_utilisateur'] . ' ' . $recette['nom_utilisateur']); ?></td>
                        <td><?= htmlspecialchars($recette['titre']); ?></td>
                        <td><?= htmlspecialchars($recette['categorie']); ?></td>
                        <td>
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="id_recette" value="<?= $recette['id_recette']; ?>">
                                <button type="submit" name="delete_recette" class="button is-danger is-small">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<?php include '../includes/footer.php'; ?>

<script>
    function openEditUser(user) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="id_utilisateur" value="${user.id_utilisateur}">
            <input type="text" name="nom" value="${user.nom}" required>
            <input type="text" name="prenom" value="${user.prenom}" required>
            <input type="email" name="email" value="${user.email}" required>
            <select name="role">
                <option value="utilisateur" ${user.role === 'utilisateur' ? 'selected' : ''}>Utilisateur</option>
                <option value="administrateur" ${user.role === 'administrateur' ? 'selected' : ''}>Administrateur</option>
            </select>
            <button type="submit" name="edit_user" class="button is-link is-small">Modifier</button>
        `;
        document.body.appendChild(form);
        form.submit();
    }
</script>
</body>
</html>
