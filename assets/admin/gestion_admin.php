<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

if ($_SESSION['role'] !== 'administrateur' && $_SESSION['role'] !== 'super_administrateur') {
    header('Location: erreur_403.php'); // Page interdite
    exit;
}

$isSuperAdmin = ($_SESSION['role'] === 'super_administrateur');

// Supprimer un utilisateur
if ($isSuperAdmin && isset($_POST['delete_user'])) {
    $id_utilisateur = (int)$_POST['id_utilisateur'];
    $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = :id");
    $stmt->execute(['id' => $id_utilisateur]);
}

// Modifier un utilisateur
if ($isSuperAdmin && isset($_POST['edit_user'])) {
    $id_utilisateur = (int)$_POST['id_utilisateur'];
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $role = $_POST['role'];

    if ($role !== 'super_administrateur' || $isSuperAdmin) { // Empêche la modification du rôle super administrateur
        $stmt = $pdo->prepare("UPDATE utilisateurs SET nom = :nom, prenom = :prenom, email = :email, role = :role WHERE id_utilisateur = :id");
        $stmt->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'role' => $role,
            'id' => $id_utilisateur,
        ]);
    }
}


// Création d'un administrateur par le super administrateur
if ($isSuperAdmin && isset($_POST['create_admin'])) {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $role = 'administrateur';

    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES (:nom, :prenom, :email, :mot_de_passe, :role)");
    $stmt->execute([
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'mot_de_passe' => $mot_de_passe,
        'role' => $role
    ]);
}

// Supprimer un avis
if (isset($_POST['delete_avis'])) {
    $id_avis = (int)$_POST['id_avis'];
    $stmt = $pdo->prepare("DELETE FROM avis WHERE id_avis = :id");
    $stmt->execute(['id' => $id_avis]);
}

// Modifier un avis
if (isset($_POST['edit_avis'])) {
    $id_avis = (int)$_POST['id_avis'];
    $commentaire = htmlspecialchars($_POST['commentaire']);
    $note = (int)$_POST['note'];

    $stmt = $pdo->prepare("UPDATE avis SET commentaire = :commentaire, note = :note WHERE id_avis = :id");
    $stmt->execute([
        'commentaire' => $commentaire,
        'note' => $note,
        'id' => $id_avis,
    ]);
}

// Supprimer une recette
if (isset($_POST['delete_recette'])) {
    $id_recette = (int)$_POST['id_recette'];
    $stmt = $pdo->prepare("DELETE FROM recettes WHERE id_recette = :id");
    $stmt->execute(['id' => $id_recette]);
}

// Modifier une recette
if (isset($_POST['edit_recette'])) {
    $id_recette = (int)$_POST['id_recette'];
    $titre = htmlspecialchars($_POST['titre']);
    $description = htmlspecialchars($_POST['description']);
    $categorie = $_POST['categorie'];

    $stmt = $pdo->prepare("UPDATE recettes SET titre = :titre, description = :description, categorie = :categorie WHERE id_recette = :id");
    $stmt->execute([
        'titre' => $titre,
        'description' => $description,
        'categorie' => $categorie,
        'id' => $id_recette,
    ]);
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
    <?php if ($isSuperAdmin): ?>
        <section class="section">
            <h2 class="title is-4">Créer un administrateur</h2>
            <form method="POST">
                <div class="field">
                    <label class="label">Nom</label>
                    <input class="input" type="text" name="nom" placeholder="Nom" required>
                </div>
                <div class="field">
                    <label class="label">Prénom</label>
                    <input class="input" type="text" name="prenom" placeholder="Prénom" required>
                </div>
                <div class="field">
                    <label class="label">Email</label>
                    <input class="input" type="email" name="email" placeholder="Email" required>
                </div>
                <div class="field">
                    <label class="label">Mot de passe</label>
                    <input class="input" type="password" name="mot_de_passe" placeholder="Mot de passe" required>
                </div>
                <div class="control">
                    <button type="submit" name="create_admin" class="button is-primary">Créer un administrateur</button>
                </div>
            </form>
        </section>
    <?php endif; ?>
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
                            <button class="button is-link is-small" onclick="openEditModalUser(<?= htmlspecialchars(json_encode($user)); ?>)">Modifier</button>
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
                            <button class="button is-link is-small" onclick="openEditModalAvis(<?= htmlspecialchars(json_encode($avis_item)); ?>)">Modifier</button>
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
                            <button class="button is-link is-small" onclick="openEditModalRecette(<?= htmlspecialchars(json_encode($recette)); ?>)">Modifier</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<?php include '../includes/footer.php'; ?>

<script>
    // Fonction pour afficher le modal de modification des utilisateurs
function openEditModalUser(user) {
    const modalContent = `
        <div class="modal is-active" id="editUserModal">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Modifier Utilisateur</p>
                    <button class="delete" aria-label="close" onclick="closeModal('editUserModal')"></button>
                </header>
                <form method="POST">
                    <section class="modal-card-body">
                        <input type="hidden" name="id_utilisateur" value="${user.id_utilisateur}">
                        <div class="field">
                            <label class="label">Nom</label>
                            <div class="control">
                                <input class="input" type="text" name="nom" value="${user.nom}" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Prénom</label>
                            <div class="control">
                                <input class="input" type="text" name="prenom" value="${user.prenom}" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Email</label>
                            <div class="control">
                                <input class="input" type="email" name="email" value="${user.email}" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Rôle</label>
                            <div class="control">
                                <select class="input" name="role">
                                    <option value="utilisateur" ${user.role === "utilisateur" ? "selected" : ""}>Utilisateur</option>
                                    <option value="administrateur" ${user.role === "administrateur" ? "selected" : ""}>Administrateur</option>
                                </select>
                            </div>
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <button type="submit" name="edit_user" class="button is-success">Modifier</button>
                        <button type="button" class="button" onclick="closeModal('editUserModal')">Annuler</button>
                    </footer>
                </form>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML("beforeend", modalContent);
}

// Fonction pour afficher le modal de modification des avis
function openEditModalAvis(avis) {
    const modalContent = `
        <div class="modal is-active" id="editAvisModal">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Modifier Avis</p>
                    <button class="delete" aria-label="close" onclick="closeModal('editAvisModal')"></button>
                </header>
                <form method="POST">
                    <section class="modal-card-body">
                        <input type="hidden" name="id_avis" value="${avis.id_avis}">
                        <div class="field">
                            <label class="label">Commentaire</label>
                            <div class="control">
                                <textarea class="textarea" name="commentaire" required>${avis.commentaire}</textarea>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Note</label>
                            <div class="control">
                                <input class="input" type="number" name="note" min="1" max="5" value="${avis.note}" required>
                            </div>
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <button type="submit" name="edit_avis" class="button is-success">Modifier</button>
                        <button type="button" class="button" onclick="closeModal('editAvisModal')">Annuler</button>
                    </footer>
                </form>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML("beforeend", modalContent);
}

// Fonction pour afficher le modal de modification des recettes
function openEditModalRecette(recette) {
    const modalContent = `
        <div class="modal is-active" id="editRecetteModal">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Modifier Recette</p>
                    <button class="delete" aria-label="close" onclick="closeModal('editRecetteModal')"></button>
                </header>
                <form method="POST">
                    <section class="modal-card-body">
                        <input type="hidden" name="id_recette" value="${recette.id_recette}">
                        <div class="field">
                            <label class="label">Titre</label>
                            <div class="control">
                                <input class="input" type="text" name="titre" value="${recette.titre}" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Description</label>
                            <div class="control">
                                <textarea class="textarea" name="description" required>${recette.description}</textarea>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Catégorie</label>
                            <div class="control">
                                <select class="input" name="categorie">
                                    <option value="Prise de masse" ${recette.categorie === "Prise de masse" ? "selected" : ""}>Prise de masse</option>
                                    <option value="Maintien" ${recette.categorie === "Maintien" ? "selected" : ""}>Maintien</option>
                                    <option value="Sèche" ${recette.categorie === "Sèche" ? "selected" : ""}>Sèche</option>
                                </select>
                            </div>
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <button type="submit" name="edit_recette" class="button is-success">Modifier</button>
                        <button type="button" class="button" onclick="closeModal('editRecetteModal')">Annuler</button>
                    </footer>
                </form>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML("beforeend", modalContent);
}

// Fonction pour fermer un modal
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.remove();
}

</script>
</body>
</html>
