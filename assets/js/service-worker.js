self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open('muscle-talk-cache').then(function(cache) {
            return cache.addAll([
                '/',
                '/accueil.php',
                '/assets/css/styles.css',
                '/assets/js/theme-toggle.js',
                '/../img/logo-192x192.png',
                '/../img/logo-512x512.png'
            ]);
        })
    );
});

self.addEventListener('fetch', function(event) {
    event.respondWith(
        caches.match(event.request).then(function(response) {
            return response || fetch(event.request);
        })
    );
});