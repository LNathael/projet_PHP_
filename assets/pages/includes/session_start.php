<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifiez si l'utilisateur est connecté et définissez les variables de session si nécessaire
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $prenom = $_SESSION['prenom'];
    $nom = $_SESSION['nom'];
} else {
    $user_id = null;
    $prenom = null;
    $nom = null;
}
?>