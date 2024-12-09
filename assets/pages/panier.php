<?php
session_start();
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Ajouter un produit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_produit = $_POST['id_produit'];
    $quantite = $_POST['quantite'];

    if (isset($_SESSION['panier'][$id_produit])) {
        $_SESSION['panier'][$id_produit] += $quantite;
    } else {
        $_SESSION['panier'][$id_produit] = $quantite;
    }
}

// Afficher le panier
foreach ($_SESSION['panier'] as $id => $quantite) {
    echo "Produit $id - Quantité : $quantite<br>";
}
