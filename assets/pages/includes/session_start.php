<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifiez si l'utilisateur est connecté et définissez les variables de session si nécessaire
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $prenom = isset($_SESSION['prenom']) ? $_SESSION['prenom'] : null;
    $nom = isset($_SESSION['nom']) ? $_SESSION['nom'] : null;
} else {
    $user_id = null;
    $prenom = null;
    $nom = null;
}
?>