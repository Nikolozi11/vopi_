self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(clients.claim());
});

// ფონური შეტყობინებების მიღება
self.addEventListener('push', function(event) {
    const data = event.data ? event.data.json() : { title: 'შეგახსენება', body: 'დროა სასწავლო აქტივობის!' };
    
    const options = {
        body: data.body,
        icon: '/images/icon-192.png',
        badge: '/images/icon-192.png'
    };

    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});