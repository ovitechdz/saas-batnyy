const CACHE_NAME = 'ovitech-offline-v1';
const OFFLINE_URL = '/offline';

const ASSETS = [
    OFFLINE_URL,
    '/manifest.json'
];

self.addEventListener('install', event => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return cache.addAll(ASSETS);
        })
    );
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});

self.addEventListener('fetch', event => {
    // We only care about HTML document requests for the offline fallback
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request).catch(() => {
                return caches.match(OFFLINE_URL);
            })
        );
    } else {
        // For other requests, try network first, then cache
        event.respondWith(
            fetch(event.request).catch(() => {
                return caches.match(event.request);
            })
        );
    }
});

self.addEventListener('sync', event => {
    if (event.tag === 'sync-livestock') {
        event.waitUntil(syncLivestock());
    }
});

async function syncLivestock() {
    console.log('Syncing livestock in background...');
}
