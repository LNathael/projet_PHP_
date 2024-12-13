<?php
// Inclure le header
include '../includes/header.php'; 

// Vérifier si une recherche a été effectuée
if (isset($_GET['query']) && strlen(trim($_GET['query'])) > 0) {
    // Nettoyer et récupérer la requête
    $query = htmlspecialchars(trim($_GET['query']));

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
            // Mettre en évidence les termes de recherche dans le contenu
            $keywords = explode(' ', $query);
            $highlighted = $resultat['contenu'];
            foreach ($keywords as $word) {
                $highlighted = str_ireplace($word, "<span class='has-background-warning'>$word</span>", $highlighted);
            }

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
    // Si aucune recherche valide n'a été effectuée
    echo "<h1 class='title is-4'>Veuillez saisir un terme de recherche valide.</h1>";
}

// Inclure le footer
include '../includes/footer.php'; 
?>
