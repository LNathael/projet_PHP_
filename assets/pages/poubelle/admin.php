<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrateur') {
    header('Location: ../connexion.php');
    exit;
}
?>

<h1>Administration</h1>
<nav>
    <a href="gestion_produits.php">Gestion des produits</a>
    <a href="gestion_commandes.php">Gestion des commandes</a>
    <a href="gestion_utilisateurs.php">Gestion des utilisateurs</a>
</nav>
