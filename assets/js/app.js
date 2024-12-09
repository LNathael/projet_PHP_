// Assurez-vous que le DOM est chargé avant d'exécuter les scripts
document.addEventListener('DOMContentLoaded', () => {

    // Gestion interactive du panier avec AJAX
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    if (addToCartButtons) {
        addToCartButtons.forEach(button => {
            button.addEventListener('click', () => {
                const productId = button.dataset.id;
                const quantity = 1; // Par défaut

                fetch('../pages/panier.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id_produit: productId, quantite: quantity }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Produit ajouté au panier !');
                        } else {
                            alert('Erreur : ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Erreur :', error);
                    });
            });
        });
    }

    // Carrousel simple pour afficher des images en boucle
    const carousel = document.querySelector('.carousel');
    if (carousel) {
        const slides = carousel.querySelectorAll('.slide');
        let currentIndex = 0;

        setInterval(() => {
            slides[currentIndex].classList.remove('active');
            currentIndex = (currentIndex + 1) % slides.length;
            slides[currentIndex].classList.add('active');
        }, 3000); // Change toutes les 3 secondes
    }

    // Gestion des popups
    const popupTrigger = document.querySelector('.popup-trigger');
    const popup = document.querySelector('.popup');
    const popupClose = document.querySelector('.popup-close');

    if (popupTrigger && popup && popupClose) {
        popupTrigger.addEventListener('click', () => {
            popup.classList.add('visible');
        });

        popupClose.addEventListener('click', () => {
            popup.classList.remove('visible');
        });

        // Fermer la popup en cliquant à l'extérieur
        popup.addEventListener('click', (e) => {
            if (e.target === popup) {
                popup.classList.remove('visible');
            }
        });
    }
        // Active la fonctionnalité du menu burger pour les écrans mobiles
        const navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
    
        if (navbarBurgers.length > 0) {
            navbarBurgers.forEach(el => {
                el.addEventListener('click', () => {
                    const target = el.dataset.target;
                    const $target = document.getElementById(target);
    
                    el.classList.toggle('is-active');
                    $target.classList.toggle('is-active');
                });
            });
        }
    
});
