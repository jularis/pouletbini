const CACHE_VERSION = 'poulet-bini-v4';
const RUNTIME_CACHE = `${CACHE_VERSION}-runtime`;
const BASE_PATH = new URL('./', self.location).pathname;
const STATIC_ASSETS = [
  BASE_PATH,
  `${BASE_PATH}offline.html`,
  `${BASE_PATH}manifest.json`,
  `${BASE_PATH}assets/images/logoIcon/favicon.png`,
  `${BASE_PATH}assets/global/css/bootstrap.min.css`,
  `${BASE_PATH}assets/global/css/all.min.css`,
  `${BASE_PATH}assets/global/css/line-awesome.min.css`,
  `${BASE_PATH}assets/global/js/jquery-3.6.0.min.js`,
  `${BASE_PATH}assets/global/js/bootstrap.bundle.min.js`,
  `${BASE_PATH}assets/global/js/offline-sync.js`,
  `${BASE_PATH}assets/viseradmin/css/app.css`,
  `${BASE_PATH}assets/viseradmin/js/app.js`,
  `${BASE_PATH}assets/templates/basic/css/main.css`,
  `${BASE_PATH}assets/templates/basic/css/custom.css`,
  `${BASE_PATH}assets/templates/basic/js/main.js`
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_VERSION)
      .then((cache) => cache.addAll(STATIC_ASSETS))
      .then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys()
      .then((keys) => Promise.all(
        keys
          .filter((key) => key.startsWith('poulet-bini-') && key !== CACHE_VERSION && key !== RUNTIME_CACHE)
          .map((key) => caches.delete(key))
      ))
      .then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', (event) => {
  const { request } = event;

  if (request.method !== 'GET') {
    return;
  }

  const url = new URL(request.url);

  if (url.origin !== self.location.origin) {
    return;
  }

  if (request.mode === 'navigate') {
    event.respondWith(networkFirst(request));
    return;
  }

  event.respondWith(cacheFirst(request));
});

async function networkFirst(request) {
  try {
    const response = await fetch(request);
    const cache = await caches.open(RUNTIME_CACHE);
    cache.put(request, response.clone());
    return response;
  } catch (error) {
    const cached = await caches.match(request);
    return cached || caches.match(`${BASE_PATH}offline.html`);
  }
}

async function cacheFirst(request) {
  const cached = await caches.match(request);

  if (cached) {
    return cached;
  }

  try {
    const response = await fetch(request);

    if (response.ok) {
      const cache = await caches.open(RUNTIME_CACHE);
      cache.put(request, response.clone());
    }

    return response;
  } catch (error) {
    return new Response('', {
      status: 504,
      statusText: 'Offline'
    });
  }
}
