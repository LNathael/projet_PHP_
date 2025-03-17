<?php
require_once '../config/db.php'; // Connexion à la base de données

// Récupérer le dernier message envoyé avec les infos du salon
$stmt = $pdo->query("
    SELECT m.contenu, m.date_message, s.id_salon, s.nom_salon
    FROM messages m
    JOIN salons s ON m.id_salon = s.id_salon
    ORDER BY m.date_message DESC
    LIMIT 1
");
$dernier_message = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier s'il y a un message à afficher
if ($dernier_message) :
?>
    <div id="popup-message" class="popup">
        <a href="../Salon/salons.php $dernier_message['id_salon']; ?>" class="popup-content">
            <p><strong>Dernier message dans #<?= htmlspecialchars($dernier_message['nom_salon']); ?></strong></p>
            <p><?= htmlspecialchars($dernier_message['contenu']); ?></p>
        </a>
    </div>

    <style>
        .popup {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background:rgb(53, 62, 71);
            color:rgb(0, 0, 0);
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            animation: fadeIn 0.5s ease-in-out;
        }

        .popup-content {
            text-decoration: none;
            color: white;
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <script>
        setTimeout(() => {
            let popup = document.getElementById('popup-message');
            if (popup) popup.style.display = 'none';
        }, 5000);
    </script>

<?php endif; ?>
<audio id="notif-sound" src="./../../sounds/notification.mp3" preload="auto"></audio>
<script>
    document.getElementById('notif-sound').play();
</script>
