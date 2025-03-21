// Assurez-vous que le DOM est chargé avant d'exécuter les scriptsdocument.addEventListener('DOMContentLoaded', () => {
    // Initialiser Swiper pour les produits
    new Swiper('.produits-swiper', {
        slidesPerView: 1,
        spaceBetween: 10,
        navigation: {
            nextEl: '.produits-button-next',
            prevEl: '.produits-button-prev',
        },
        pagination: {
            el: '.produits-pagination',
            clickable: true,
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 30,
            },
            1024: {
                slidesPerView: 4,
                spaceBetween: 40,
            },
        },
    });

    // Initialiser Swiper pour les recettes
    new Swiper('.recettes-swiper', {
        slidesPerView: 1,
        spaceBetween: 10,
        navigation: {
            nextEl: '.recettes-button-next',
            prevEl: '.recettes-button-prev',
        },
        pagination: {
            el: '.recettes-pagination',
            clickable: true,
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 30,
            },
            1024: {
                slidesPerView: 4,
                spaceBetween: 40,
            },
        },
    });

    // Initialiser Swiper pour les commentaires
    new Swiper('.commentaires-swiper', {
        slidesPerView: 1,
        spaceBetween: 10,
        navigation: {
            nextEl: '.commentaires-button-next',
            prevEl: '.commentaires-button-prev',
        },
        pagination: {
            el: '.commentaires-pagination',
            clickable: true,
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 30,
            },
            1024: {
                slidesPerView: 4,
                spaceBetween: 40,
            },
        },
    });


    
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