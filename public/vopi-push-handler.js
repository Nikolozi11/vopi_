// დაამატე შენი service worker-ის (sw.js) პირველ ხაზზე:
//   importScripts('/vopi-push-handler.js');

self.addEventListener('push', (event) => {
    let data = {};
    try {
        data = event.data ? event.data.json() : {};
    } catch (e) {
        data = { body: event.data ? event.data.text() : '' };
    }

    event.waitUntil(
        self.registration.showNotification(data.title || 'Vopi', {
            body: data.body || '',
            icon: '/images/logo.png',
            badge: '/images/logo.png',
            tag: data.tag || 'vopi-task',
            renotify: true,
            requireInteraction: true,            // არ ქრება, სანამ არ დააჭერ
            vibrate: [500, 200, 500, 200, 500, 200, 900],
            data: { url: data.url || '/dashboard' },
            actions: [{ action: 'open', title: 'გახსნა' }],
        })
    );
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    const url = (event.notification.data && event.notification.data.url) || '/dashboard';

    event.waitUntil(
        self.clients.matchAll({ type: 'window', includeUncontrolled: true }).then((list) => {
            for (const c of list) {
                if ('focus' in c) { c.navigate(url); return c.focus(); }
            }
            return self.clients.openWindow(url);
        })
    );
});
