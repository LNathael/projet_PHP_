document.addEventListener('DOMContentLoaded', () => {
    // Fonction pour initialiser un Swiper
    function initSwiper(selector, next, prev, pagination) {
        new Swiper(selector, {
            slidesPerView: 1,
            spaceBetween: 10,
            navigation: {
                nextEl: next,
                prevEl: prev,
            },
            pagination: {
                el: pagination,
                clickable: true,
            },
            breakpoints: {
                640: { slidesPerView: 2, spaceBetween: 20 },
                768: { slidesPerView: 3, spaceBetween: 30 },
                1024: { slidesPerView: 4, spaceBetween: 40 },
            },
        });
    }

    // Initialisation des Swipers
    initSwiper('.produits-swiper', '.produits-button-next', '.produits-button-prev', '.produits-pagination');
    initSwiper('.recettes-swiper', '.recettes-button-next', '.recettes-button-prev', '.recettes-pagination');

    // Gestion interactive du panier avec AJAX et protection CSRF
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', async () => {
            const productId = button.dataset.id;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch('../pages/panier.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ id_produit: productId, quantite: 1 })
                });

                const data = await response.json();
                alert(data.success ? 'Produit ajouté au panier !' : `Erreur : ${data.message}`);
            } catch (error) {
                console.error('Erreur :', error);
            }
        });
    });

    // Gestion des popups
    const popup = document.querySelector('.popup');
    if (popup) {
        document.querySelector('.popup-trigger').addEventListener('click', () => popup.classList.add('visible'));
        document.querySelector('.popup-close').addEventListener('click', () => popup.classList.remove('visible'));

        popup.addEventListener('click', (e) => {
            if (e.target === popup) popup.classList.remove('visible');
        });
    }

    // Gestion du menu burger
    document.querySelectorAll('.navbar-burger').forEach(el => {
        el.addEventListener('click', () => {
            const target = document.getElementById(el.dataset.target);
            el.classList.toggle('is-active');
            target.classList.toggle('is-active');
        });
    });
});
