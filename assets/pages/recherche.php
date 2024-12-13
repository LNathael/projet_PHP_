<?php
// Inclure le header
include '../includes/header.php'; 

// Vérifier si une recherche a été effectuée
if (isset($_GET['query']) && !empty(trim($_GET['query']))) {
    // Nettoyer la requête pour éviter les injections
    $query = htmlspecialchars($_GET['query']);

    // Afficher le terme recherché
    echo "<h1 class='title is-4'>Résultats de recherche pour : <span class='has-text-primary'>$query</span></h1>";

    // Simuler des données (exemple) ou les extraire d'une base de données
    $pages = [
        [
            "titre" => "Livraison et Retours",
            "contenu" => "Nous livrons dans toute la France sous 3 à 5 jours ouvrés. Les frais de livraison sont calculés en fonction du poids.",
            "lien" => "livraison_retours.php"
        ],
        [
            "titre" => "FAQ - Questions Fréquentes",
            "contenu" => "Nous acceptons les paiements par carte bancaire, PayPal et virement bancaire. Suivez votre commande avec un numéro de suivi.",
            "lien" => "faq.php"
        ],
        [
            "titre" => "Blog - Conseils Musculation",
            "contenu" => "Découvrez nos astuces pour la prise de masse, la perte de poids, et les routines adaptées à vos objectifs sportifs.",
            "lien" => "blog.php"
        ],
    ];

    // Rechercher les résultats correspondants
    $resultats = [];
    foreach ($pages as $page) {
        if (stripos($page['contenu'], $query) !== false || stripos($page['titre'], $query) !== false) {
            $resultats[] = $page;
        }
    }

    // Afficher les résultats trouvés
    if (!empty($resultats)) {
        echo "<ul class='content'>";
        foreach ($resultats as $resultat) {
            $highlighted = str_ireplace($query, "<span class='has-background-warning'>$query</span>", $resultat['contenu']);
            echo "<li>
                <a href='{$resultat['lien']}' class='has-text-link'><strong>{$resultat['titre']}</strong></a>
                <p>$highlighted</p>
            </li>";
        }
        echo "</ul>";
    } else {
        // Aucun résultat
        echo "<p class='has-text-danger'>Aucun résultat trouvé pour votre recherche.</p>";
    }
} else {
    // Si aucune recherche n'a été effectuée
    echo "<h1 class='title is-4'>Veuillez saisir un terme de recherche.</h1>";
}

// Inclure le footer
include '../includes/footer.php'; 
?>
