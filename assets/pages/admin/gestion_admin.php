<?php
session_start();
require_once '../config/db.php';
include '../includes/session_start.php';

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

// Ajouter un produit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $nom = htmlspecialchars($_POST['nom'] ?? '');
    $description = htmlspecialchars($_POST['description'] ?? '');
    $prix = floatval($_POST['prix'] ?? 0);
    $quantite = intval($_POST['quantite'] ?? 0);
    $libelle = htmlspecialchars($_POST['libelle'] ?? ''); // Nouveau champ
    $imagePath = null;

    // Validation des champs obligatoires
    if (empty($nom) || empty($description) || $prix <= 0 || $quantite <= 0 || empty($libelle)) {
        echo "Veuillez remplir tous les champs correctement.";
    } else {
        // Gestion de l'image
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../uploads/produits/';
            $imageName = uniqid('produit_') . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName)) {
                $imagePath = 'uploads/produits/' . $imageName;
            }
        }

        // Insérer le produit dans la base de données
        $stmt = $pdo->prepare("INSERT INTO produits (nom_produit, description, prix, quantite_disponible, libelle, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $description, $prix, $quantite, $libelle, $imagePath]);
        echo "Produit ajouté avec succès.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}


// Supprimer un produit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $id_produit = intval($_POST['id_produit']);

    // Supprimer l'image associée
    $stmt = $pdo->prepare("SELECT image FROM produits WHERE id_produit = ?");
    $stmt->execute([$id_produit]);
    $produit = $stmt->fetch();
    if ($produit && !empty($produit['image'])) {
        $imagePath = __DIR__ . '/../../' . $produit['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath); // Supprimer le fichier image
        }
    }

    // Supprimer le produit de la base de données
    $stmt = $pdo->prepare("DELETE FROM produits WHERE id_produit = ?");
    $stmt->execute([$id_produit]);
    echo "Produit supprimé avec succès.";
}

// Modifier un produit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_product'])) {
    $id_produit = intval($_POST['id_produit']);
    $nom = htmlspecialchars($_POST['nom'] ?? '');
    $description = htmlspecialchars($_POST['description'] ?? '');
    $prix = floatval($_POST['prix'] ?? 0);
    $quantite = intval($_POST['quantite'] ?? 0);
    $libelle = htmlspecialchars($_POST['libelle'] ?? ''); // Nouveau champ
    $imagePath = null;

    // Gestion de l'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/produits/';
        $imageName = uniqid('produit_') . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName)) {
            $imagePath = 'uploads/produits/' . $imageName;
        }
    }

    // Construction de la requête SQL
    $sql = "UPDATE produits SET nom_produit = ?, description = ?, prix = ?, quantite_disponible = ?, libelle = ?";
    $params = [$nom, $description, $prix, $quantite, $libelle];

    if ($imagePath) {
        $sql .= ", image = ?";
        $params[] = $imagePath;
    }
    $sql .= " WHERE id_produit = ?";
    $params[] = $id_produit;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    echo "Produit modifié avec succès.";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}








/*
// Modifier un produit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_product'])) {
    $id_produit = intval($_POST['id_produit']);
    $nom = htmlspecialchars($_POST['nom']);
    $description = htmlspecialchars($_POST['description']);
    $prix = floatval($_POST['prix']);
    $quantite = intval($_POST['quantite']);
    $imagePath = null;

    

    // Gérer l'upload de l'image (si une nouvelle image est uploadée)
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../uploads/produits/';
        $imageName = uniqid('produit_') . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imagePath = $uploadDir . $imageName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            $imagePath = 'uploads/produits/' . $imageName;

            // Supprimer l'ancienne image
            $stmt = $pdo->prepare("SELECT image FROM produits WHERE id_produit = ?");
            $stmt->execute([$id_produit]);
            $produit = $stmt->fetch();
            if ($produit && !empty($produit['image'])) {
                $oldImagePath = __DIR__ . '/../../' . $produit['image'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        }
    }
    // Mise à jour du produit
    $stmt = $pdo->prepare("UPDATE produits SET nom_produit = ?, description = ?, prix = ?, quantite_disponible = ?, image = COALESCE(?, image) WHERE id_produit = ?");
    $stmt->execute([$nom, $description, $prix, $quantite, $imagePath, $id_produit]);
    $message = "Produit modifié avec succès.";
}
*/

// Récupérer les produits
$produits = $pdo->query("SELECT * FROM produits ORDER BY nom_produit ASC")->fetchAll(PDO::FETCH_ASSOC);
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
    <h1 class="title">Gestion des Produits</h1>

    <!-- Message -->
    <?php if (!empty($message)): ?>
        <div class="notification is-success">
            <?= htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Ajouter un produit -->
    <section>
        <form method="POST" enctype="multipart/form-data" class="box">
        <h2 class="title is-4">Ajouter un produit</h2>

        <!-- Champ Nom du produit -->
        <div class="field">
            <label class="label">Nom du produit</label>
            <div class="control">
                <input class="input" type="text" name="nom" placeholder="Nom du produit" required>
            </div>
        </div>

        <div class="field">
        <label class="label">Libellé</label>
            <div class="control">
                <input class="input" type="text" name="libelle" placeholder="Libellé du produit" required>
            </div>
        </div>
        <!-- Champ Description -->
        <div class="field">
            <label class="label">Description</label>
            <div class="control">
                <textarea class="textarea" name="description" placeholder="Description du produit" required></textarea>
            </div>
        </div>

        <!-- Champ Prix -->
        <div class="field">
            <label class="label">Prix</label>
            <div class="control">
                <input class="input" type="number" step="0.01" name="prix" placeholder="Prix en €" required>
            </div>
        </div>

        <!-- Champ Quantité -->
        <div class="field">
            <label class="label">Quantité disponible</label>
            <div class="control">
                <input class="input" type="number" name="quantite" placeholder="Quantité disponible" required>
            </div>
        </div>

        <!-- Champ Image -->
        <div class="field">
            <label class="label">Image du produit</label>
            <div class="control">
                <input class="input" type="file" name="image" accept="image/*" required>
            </div>
        </div>

        <!-- Bouton Soumettre -->
        <div class="control">
            <button class="button is-primary" type="submit" name="add_product">Ajouter le produit</button>
        </div>
    </form>
    </section>

    <!-- Liste des produits -->
    <section class="mt-5">
        <h2 class="title is-4">Liste des produits</h2>
        <table class="table is-striped is-fullwidth">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Libellé</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produits as $produit): ?>
                    <tr>
                        <td>
                            <?php if (!empty($produit['image'])): ?>
                                <img src="../../../<?= htmlspecialchars($produit['image'] ?? ''); ?>" style="max-width: 100px;">
                                
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($produit['nom_produit'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($produit['libelle'] ?? ''); ?></td>
                        <td><?= htmlspecialchars(substr($produit['description'] ?? '', 0, 50)); ?>...</td>
                        <td><?= number_format($produit['prix'] ?? 0, 2); ?> €</td>
                        <td><?= htmlspecialchars($produit['quantite_disponible'] ?? '0'); ?></td>
                        <td>
                            <!-- Supprimer -->
                            <form method="POST" style="display:inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                                <input type="hidden" name="id_produit" value="<?= htmlspecialchars($produit['id_produit'] ?? ''); ?>">
                                <button type="submit" name="delete_product" class="button is-danger is-small">Supprimer</button>
                            </form>
                            <!-- Modifier -->
                            <button class="button is-link is-small" 
                                    onclick="openEditModalProduit({
                                        id: <?= $produit['id_produit']; ?>,
                                        nom: '<?= addslashes($produit['nom_produit'] ?? ''); ?>',
                                        libelle: '<?= addslashes($produit['libelle'] ?? ''); ?>',
                                        description: '<?= addslashes($produit['description'] ?? ''); ?>',
                                        prix: <?= $produit['prix'] ?? 0; ?>,
                                        quantite: <?= $produit['quantite_disponible'] ?? 0; ?>
                                    })">
                                Modifier
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </section>
    <h1 class="title mt-5">Page de Gestion Administrateur</h1>
    <!-- Section Gestion Produits -->
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
                                    <option value="super_administrateur" ${user.role === "super_administrateur" ? "selected" : ""}>Super Administrateur</option>
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


// Fonction pour afficher le modal de modification des produits
function openEditModalProduit(produit) {
    const existingModal = document.getElementById("editProductModal");
    if (existingModal) existingModal.remove();

    const modalContent = `
        <div class="modal is-active" id="editProductModal">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Modifier Produit</p>
            <button class="delete" aria-label="close" onclick="closeModal('editProductModal')"></button>
        </header>
        <form method="POST" enctype="multipart/form-data">
            <section class="modal-card-body">
                <div class="columns is-gapless">
                     <div class="columns">
                    <!-- Colonne pour l'image -->
                    <div class="column is-one-third has-text-centered">
                        <figure class="image is-128x128 is-inline-block">
                            <img id="productImagePreview" src="../../uploads/produits/${produit.image}" alt="Aperçu de l'image" style="max-width: 100%; border: 1px solid #ddd; border-radius: 5px; margin-bottom: 10px;">
                        </figure>
                        <div class="field mt-3">
                            <label class="label">Changer l'image</label>
                            <div class="control">
                                <input class="input" type="file" name="image" accept="image/*" onchange="previewImage(event)">
                            </div>
                        </div>
                    </div>
                    </div>

                    <!-- Colonne pour les champs -->
                    <div class="column">
                        <div class="field">
                            <label class="label">Nom</label>
                            <div class="control">
                                <input class="input" type="text" name="nom" value="${produit.nom}" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Libellé</label>
                            <div class="control">
                                <input class="input" type="text" name="libelle" value="${produit.libelle || ''}" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Description</label>
                            <div class="control">
                                <textarea class="textarea" name="description" required>${produit.description}</textarea>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Prix</label>
                            <div class="control">
                                <input class="input" type="number" step="0.01" name="prix" value="${produit.prix}" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Quantité</label>
                            <div class="control">
                                <input class="input" type="number" name="quantite" value="${produit.quantite}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <button type="submit" name="edit_product" class="button is-success">Modifier</button>
                <button type="button" class="button" onclick="closeModal('editProductModal')">Annuler</button>
            </footer>
        </form>
    </div>
</div>

    `;

    document.body.insertAdjacentHTML("beforeend", modalContent);

// Ajuste la hauteur après l'insertion
const modalCard = document.querySelector("#editProductModal .modal-card");
modalCard.style.maxHeight = "150vh";
}

function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('productImagePreview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}



function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.remove();
}


</script>
</body>
</html>
