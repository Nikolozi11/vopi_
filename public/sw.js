// push-შეტყობინებებს (მაღვიძარა) და მათზე დაჭერას ამუშავებს ეს ფაილი:
importScripts('/vopi-push-handler.js');

self.addEventListener('install', () => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

// PWA საძირკველი: ქსელი პირველია; თუ ქსელი არ არის, ვცდილობთ ქეშს, ბოლოს კი ვაბრუნებთ მარტივ პასუხს
self.addEventListener('fetch', (event) => {
    const req = event.request;
    const url = new URL(req.url);

    // მხოლოდ ჩვენი საიტის GET მოთხოვნები; Livewire, API და მაღვიძარას ბმულები ხელუხლებელია
    if (req.method !== 'GET' || url.origin !== self.location.origin) return;
    if (/^\/(livewire|alarm|cron|api)\b/.test(url.pathname)) return;

    event.respondWith(
        fetch(req).catch(async () => {
            const cached = await caches.match(req);
            return cached || new Response('ოფლაინ რეჟიმია. შეამოწმე ინტერნეტი.', {
                status: 503,
                headers: { 'Content-Type': 'text/plain; charset=utf-8' },
            });
        })
    );
});