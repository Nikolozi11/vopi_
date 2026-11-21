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
            renotify: true,                       // ყოველ გამეორებაზე თავიდან დარეკოს
            requireInteraction: true,             // არ ქრება, სანამ არ დააჭერ
            silent: false,
            vibrate: [600, 200, 600, 200, 600, 200, 1000],
            data: { url: data.url || '/dashboard', stop_url: data.stop_url || null },
            actions: [
                { action: 'stop', title: 'გაჩერება' },
                { action: 'open', title: 'გახსნა' },
            ],
        })
    );
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    const d = event.notification.data || {};
    const stop = d.stop_url ? fetch(d.stop_url, { mode: 'no-cors' }).catch(() => {}) : Promise.resolve();

    if (event.action === 'stop') {
        event.waitUntil(stop);
        return;
    }

    const url = d.url || '/dashboard';
    event.waitUntil(
        Promise.all([stop, self.clients.matchAll({ type: 'window', includeUncontrolled: true }).then((list) => {
            for (const c of list) {
                if ('focus' in c) { c.navigate(url); return c.focus(); }
            }
            return self.clients.openWindow(url);
        })])
    );
});

// ნოტიფიკაციის სვაიპი/დახურვაც აჩერებს გამეორებას
self.addEventListener('notificationclose', (event) => {
    const d = event.notification.data || {};
    if (d.stop_url) event.waitUntil(fetch(d.stop_url, { mode: 'no-cors' }).catch(() => {}));
});
