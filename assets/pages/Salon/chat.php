<?php
session_start();
require_once '../config/db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$id_utilisateur = $_SESSION['user_id'];
$id_salon = $_GET['salon'] ?? null;

// Vérifier que l'ID du salon est valide
if (!$id_salon) {
    die('Salon introuvable.');
}

// Récupérer les infos du salon
$stmt = $pdo->prepare("SELECT * FROM salons WHERE id_salon = ?");
$stmt->execute([$id_salon]);
$salon = $stmt->fetch();

if (!$salon) {
    die('Salon introuvable.');
}

// Récupérer les messages du salon
$stmt = $pdo->prepare("
    SELECT m.*, u.nom, u.prenom, u.role, u.photo_profil, u.badge, r.titre AS recette_titre, r.image AS recette_image, p.nom_produit, p.image AS produit_image 
    FROM messages m
    LEFT JOIN utilisateurs u ON m.id_utilisateur = u.id_utilisateur
    LEFT JOIN recettes r ON m.id_recette = r.id_recette
    LEFT JOIN produits p ON m.id_produit = p.id_produit
    WHERE m.id_salon = ?
    ORDER BY m.date_message ASC
");
$stmt->execute([$id_salon]);
$messages = $stmt->fetchAll();

// Récupérer les recettes et les produits
$recettes = $pdo->query("SELECT * FROM recettes")->fetchAll();
$produits = $pdo->query("SELECT * FROM produits")->fetchAll();
?>

<?php include '../includes/header.php'; ?>
<head>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        .role-utilisateur { background-color: #f0f0f0; }
        .role-administrateur { background-color: #d9534f; }
        .role-super_administrateur { background-color: #c9302c; }
        .role-coach { background-color: #5cb85c; }
        .role-commercial { background-color: #5bc0de; }
        .image-apercu { max-width: 150px; max-height: 150px; }
        .role-text-utilisateur { color: #000; }
        .role-text-administrateur { color: #d9534f; }
        .role-text-super_administrateur { color: #c9302c; }
        .role-text-coach { color: #5cb85c; }
        .role-text-commercial { color: #5bc0de; }
        .profile-image { width: 50px; height: 50px; border-radius: 50%; }
        .mention { color: #007bff; cursor: pointer; }
        .badge { background-color: #ffdd57; padding: 2px 5px; border-radius: 3px; margin-left: 5px; }
    </style>
</head>
<div class="container mt-5">
    <h1 class="title has-text-centered">💬 <?= htmlspecialchars($salon['nom_salon']); ?></h1>

    <div class="box" id="message-container">
        <?php foreach ($messages as $message): ?>
            <div class="message is-info role-<?= htmlspecialchars($message['role']); ?>">
                <div class="message-header">
                    <a href="profil_utilisateur.php?id=<?= $message['id_utilisateur']; ?>">
                        <?php if ($message['photo_profil']): ?>
                            <img src="../../../<?= htmlspecialchars($message['photo_profil']); ?>" alt="Photo de profil" class="profile-image">
                        <?php else: ?>
                            <img src="../../uploads/profils/default.png" alt="Photo de profil" class="profile-image">
                        <?php endif; ?>
                    </a>
                    <p><strong class="role-text-<?= htmlspecialchars($message['role']); ?>"><?= htmlspecialchars($message['prenom'] . ' ' . $message['nom']); ?>
                    <?php
                        switch ($message['role']) {
                            case 'coach':
                                echo ' (Coach)';
                                break;
                            case 'administrateur':
                                echo ' (Administrateur)';
                                break;
                            case 'super_administrateur':
                                echo ' (Super Administrateur)';
                                break;
                            case 'commercial':
                                echo ' (Commercial)';
                                break;
                        }
                        ?>
                    </strong>
                    <?php if ($message['badge']): ?>
                        <span class="badge"><?= htmlspecialchars($message['badge']); ?></span>
                    <?php endif; ?>
                    </p>
                    <span class="is-size-7"><?= htmlspecialchars($message['date_message']); ?></span>
                </div>
                <div class="message-body">
                    <?= nl2br(htmlspecialchars($message['contenu'])); ?>
                    <?php if ($message['recette_titre']): ?>
                        <div class="box mt-3">
                            <h2 class="title is-5"><?= htmlspecialchars($message['recette_titre']); ?></h2>
                            <img src="../../../<?= htmlspecialchars($message['recette_image']); ?>" alt="Image de la recette" class="image-apercu">
                        </div>
                    <?php elseif ($message['nom_produit']): ?>
                        <div class="box mt-3">
                            <h2 class="title is-5"><?= htmlspecialchars($message['nom_produit']); ?></h2>
                            <img src="../../../<?= htmlspecialchars($message['produit_image']); ?>" alt="Image du produit" class="image-apercu">
                        </div>
                    <?php endif; ?>
                    <div class="mt-2">
                        <a href="#" class="mention" data-mention="<?= htmlspecialchars($message['prenom'] . ' ' . $message['nom']); ?>">Mentionner</a> | 
                        <a href="#" class="reply" data-reply="<?= htmlspecialchars($message['id_message']); ?>">Répondre</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($salon['nom_salon'] != '#Annonces' || (isset($_SESSION['role']) && in_array($_SESSION['role'], ['administrateur', 'super_administrateur', 'commercial']))): ?>
        <form method="POST" action="send_message.php" class="mt-4">
            <input type="hidden" name="id_salon" value="<?= $id_salon; ?>">
            <input type="hidden" name="reply_to" id="reply_to" value="">

            <?php if ($salon['nom_salon'] == '#Nutrition'): ?>
                <div class="field">
                    <label class="label">Sélectionnez une recette</label>
                    <div class="control">
                        <div class="select">
                            <select name="id_recette" id="id_recette">
                                <option value="">Aucune</option>
                                <?php foreach ($recettes as $recette): ?>
                                    <option value="<?= $recette['id_recette']; ?>"><?= htmlspecialchars($recette['titre']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="recette-apercu" class="box" style="display: none;">
                    <h2 class="title is-5" id="recette-titre"></h2>
                    <p id="recette-description"></p>
                    <img id="recette-image" src="" alt="Image de la recette" class="image-apercu">
                </div>
            <?php elseif ($salon['nom_salon'] == '#Produits'): ?>
                <div class="field">
                    <label class="label">Sélectionnez un produit</label>
                    <div class="control">
                        <div class="select">
                            <select name="id_produit" id="id_produit">
                                <option value="">Aucun</option>
                                <?php foreach ($produits as $produit): ?>
                                    <option value="<?= $produit['id_produit']; ?>"><?= htmlspecialchars($produit['nom_produit']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="produit-apercu" class="box" style="display: none;">
                    <h2 class="title is-5" id="produit-titre"></h2>
                    <p id="produit-description"></p>
                    <img id="produit-image" src="" alt="Image du produit" class="image-apercu">
                </div>
            <?php endif; ?>

            <textarea class="textarea" name="contenu" id="message_content" placeholder="Écrivez votre message..." required></textarea>
            <button type="submit" class="button is-primary mt-2">
                <i class="fas fa-paper-plane"></i> Envoyer
            </button>
        </form>
    <?php else: ?>
        <p class="has-text-danger">Vous n'avez pas le droit d'écrire dans ce salon.</p>
    <?php endif; ?>

    <a href="salons.php" class="button is-light mt-3">Retour aux salons</a>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const recettes = <?= json_encode($recettes); ?>;
        const produits = <?= json_encode($produits); ?>;

        document.getElementById('id_recette')?.addEventListener('change', function () {
            const idRecette = this.value;
            const recette = recettes.find(r => r.id_recette == idRecette);
            if (recette) {
                document.getElementById('recette-titre').textContent = recette.titre;
                document.getElementById('recette-description').textContent = recette.description;
                document.getElementById('recette-image').src = '../../' + recette.image;
                document.getElementById('recette-apercu').style.display = 'block';
            } else {
                document.getElementById('recette-apercu').style.display = 'none';
            }
        });

        document.getElementById('id_produit')?.addEventListener('change', function () {
            const idProduit = this.value;
            const produit = produits.find(p => p.id_produit == idProduit);
            if (produit) {
                document.getElementById('produit-titre').textContent = produit.nom_produit;
                document.getElementById('produit-description').textContent = produit.description;
                document.getElementById('produit-image').src = '../../' + produit.image;
                document.getElementById('produit-apercu').style.display = 'block';
            } else {
                document.getElementById('produit-apercu').style.display = 'none';
            }
        });

        document.querySelectorAll('.mention').forEach(function (element) {
            element.addEventListener('click', function () {
                const mention = this.getAttribute('data-mention');
                const messageContent = document.getElementById('message_content');
                messageContent.value += '@' + mention + ' ';
                messageContent.focus();
            });
        });

        document.querySelectorAll('.reply').forEach(function (element) {
            element.addEventListener('click', function () {
                const replyTo = this.getAttribute('data-reply');
                document.getElementById('reply_to').value = replyTo;
                const messageContent = document.getElementById('message_content');
                messageContent.value += 'Réponse à [message ' + replyTo + ']: ';
                messageContent.focus();
            });
        });
    });
</script>

<?php include '../includes/footer.php'; ?>