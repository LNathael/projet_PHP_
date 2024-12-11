<?php 
include '../includes/header.php'; 
include '../config/db.php'; // Remonte d'un niveau puis va dans "config/"

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceuil</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<main class="container mt-5">
    <!-- Titre principal -->
    <section class="hero is-primary is-medium has-text-centered">
        <div class="hero-body">
            <h1 class="title">Bienvenue sur notre site d'achat en ligne</h1>
            <p class="subtitle">Découvrez nos produits et nos services.</p>
        </div>
    </section>

    <!-- Section Carousel -->
    <section class="section">
        <h2 class="title is-4 has-text-centered">Nos meilleurs produits</h2>
        <div class="carousel">
            <div class="slide active">
                <img src="../assets/img/image1.jpg" alt="Image 1">
            </div>
            <div class="slide">
                <img src="../assets/img/image2.jpg" alt="Image 2">
            </div>
            <div class="slide">
                <img src="../assets/img/image3.jpg" alt="Image 3">
            </div>
        </div>
    </section>

    <!-- Bouton pour déclencher la popup -->
    <div class="has-text-centered">
        <button class="button is-primary popup-trigger">Afficher la popup</button>
    </div>

    <!-- Section Popup -->
    <div class="popup is-hidden">
        <div class="popup-content box">
            <button class="delete popup-close"></button>
            <h2 class="title is-4">Titre de la popup</h2>
            <p>Contenu de la popup ici.</p>
        </div>
    </div>
</main>

<?php 
include '../includes/footer.php'; 
?>
<script>
    // Gestion du carousel
const slides = document.querySelectorAll('.carousel .slide');
let currentIndex = 0;

function showSlide(index) {
    slides.forEach((slide, i) => {
        slide.classList.remove('active');
        if (i === index) slide.classList.add('active');
    });
}

setInterval(() => {
    currentIndex = (currentIndex + 1) % slides.length;
    showSlide(currentIndex);
}, 3000); // Changement d'image toutes les 3 secondes

// Gestion de la popup
const popupTrigger = document.querySelector('.popup-trigger');
const popup = document.querySelector('.popup');
const popupClose = document.querySelector('.popup-close');

popupTrigger.addEventListener('click', () => {
    popup.classList.remove('is-hidden');
});

popupClose.addEventListener('click', () => {
    popup.classList.add('is-hidden');
});

document.addEventListener('click', (e) => {
    if (e.target === popup) popup.classList.add('is-hidden');
});
</script>