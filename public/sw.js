self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(clients.claim());
});

// ოფლაინ მხარდაჭერა და PWA საძირკველი
self.addEventListener('fetch', (event) => {
    event.respondWith(
        fetch(event.request).catch(() => caches.match(event.request))
    );
});

// ფუშ-შეტყობინებების და მაღვიძარას დამჭერი
self.addEventListener('push', function(event) {
    const data = event.data ? event.data.json() : {};
    const title = data.title || '🚨 Vopi - დროა!';
    const options = {
        body: data.body || 'გაკვეთილის ან დავალების დრო მოვიდა!',
        icon: '/images/icon-192.png',
        badge: '/images/icon-192.png',
        vibrate: [300, 100, 300, 100, 300], // ზარის მსგავსი ვიბრაცია
        tag: 'vopi-alarm',
        renotify: true,
        requireInteraction: true // არ გაქრება, სანამ არ მიაჭერ
    };

    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});

// როცა შეტყობინებას დააჭერს, აპი ავტომატურად გაიხსნება
self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    event.waitUntil(
        clients.openWindow('/dashboard')
    );
});