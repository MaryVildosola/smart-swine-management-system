const CACHE_NAME = 'porcitrack-cache-v4';
const STATIC_ASSETS = [
    '/',
    '/login',
    '/worker/dashboard',
    '/worker/tasks',
    '/assets/images/pig-logo.png',
    '/backend/assets/css/style.css',
    '/backend/assets/js/main.js',
    'https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css',
    'https://cdn.tailwindcss.com'
];

// Install Event
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Service Worker: Caching Static Assets');
                return cache.addAll(STATIC_ASSETS);
            })
            .then(() => self.skipWaiting())
    );
});

// Activate Event
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cache => {
                    if (cache !== CACHE_NAME) {
                        console.log('Service Worker: Clearing Old Cache');
                        return caches.delete(cache);
                    }
                })
            );
        })
    );
});

// Fetch Event (Stale-While-Revalidate Strategy)
self.addEventListener('fetch', event => {
    // Only handle GET requests for now
    if (event.request.method !== 'GET') return;

    // For worker portal routes, use Network-First to ensure fresh data (status changes, etc)
    if (event.request.url.includes('/worker/')) {
        event.respondWith(
            fetch(event.request)
                .then(networkResponse => {
                    if (networkResponse && networkResponse.status === 200) {
                        const responseClone = networkResponse.clone();
                        caches.open(CACHE_NAME).then(cache => {
                            cache.put(event.request, responseClone);
                        });
                    }
                    return networkResponse;
                })
                .catch(() => caches.match(event.request))
        );
        return;
    }

    event.respondWith(
        caches.match(event.request).then(cachedResponse => {
            const fetchPromise = fetch(event.request).then(networkResponse => {
                // Cache the new response
                if (networkResponse && networkResponse.status === 200) {
                    const responseClone = networkResponse.clone();
                    caches.open(CACHE_NAME).then(cache => {
                        cache.put(event.request, responseClone);
                    });
                }
                return networkResponse;
            }).catch(() => {
                // If offline and not in cache, you could return an offline page
            });

            return cachedResponse || fetchPromise;
        })
    );
});
