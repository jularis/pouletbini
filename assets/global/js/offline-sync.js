(function () {
    "use strict";

    var STORAGE_KEY = "pouletbini.offline.requests";
    var FORM_SELECTOR = "form[data-offline-sync]";
    var syncing = false;

    function readQueue() {
        try {
            return JSON.parse(localStorage.getItem(STORAGE_KEY) || "[]");
        } catch (error) {
            return [];
        }
    }

    function writeQueue(queue) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(queue));
        updateQueuedCount(queue.length);
    }

    function updateQueuedCount(count) {
        document.documentElement.setAttribute("data-offline-queue-count", String(count));
        window.dispatchEvent(new CustomEvent("offline-sync:queue", { detail: { count: count } }));
    }

    function serializeForm(form) {
        var formData = new FormData(form);
        var fields = [];

        formData.forEach(function (value, key) {
            if (value instanceof File) {
                return;
            }

            fields.push([key, value]);
        });

        return {
            id: Date.now().toString(36) + Math.random().toString(36).slice(2),
            action: form.action,
            method: (form.method || "POST").toUpperCase(),
            fields: fields,
            createdAt: new Date().toISOString(),
            label: form.getAttribute("data-offline-label") || "commande"
        };
    }

    function isAuthRedirect(response) {
        var url = new URL(response.url);
        var path = url.pathname.replace(/\/+$/, "");

        return path === "" || path === "/manager" || path === "/staff" || path === "/admin";
    }

    function requestToFormData(request) {
        var formData = new FormData();

        request.fields.forEach(function (field) {
            formData.append(field[0], field[1]);
        });

        return formData;
    }

    function showMessage(type, message) {
        if (typeof window.notify === "function") {
            window.notify(type, message);
            return;
        }

        if (window.iziToast && typeof window.iziToast[type] === "function") {
            window.iziToast[type]({ message: message, position: "topRight" });
            return;
        }

        window.dispatchEvent(new CustomEvent("offline-sync:message", {
            detail: { type: type, message: message }
        }));
    }

    function queueForm(form) {
        var queue = readQueue();
        var request = serializeForm(form);

        queue.push(request);
        writeQueue(queue);

        form.reset();
        showMessage("success", "Commande enregistree hors ligne. Synchronisation automatique au retour de la connexion.");
    }

    async function submitFormOnline(form) {
        var request = serializeForm(form);
        var response = await postQueuedRequest(request);

        if (response.redirected) {
            window.location.href = response.url;
            return;
        }

        window.location.reload();
    }

    async function postQueuedRequest(request) {
        var response = await fetch(request.action, {
            method: request.method,
            body: requestToFormData(request),
            credentials: "same-origin"
        });

        if (response.status === 419 || response.status === 401 || isAuthRedirect(response)) {
            throw new Error("AUTH_REQUIRED");
        }

        if (!response.ok) {
            throw new Error("SYNC_FAILED");
        }

        return response;
    }

    async function syncQueue() {
        if (syncing || !navigator.onLine) {
            return;
        }

        var queue = readQueue();

        if (!queue.length) {
            updateQueuedCount(0);
            return;
        }

        syncing = true;

        try {
            var remaining = [];

            for (var i = 0; i < queue.length; i++) {
                try {
                    await postQueuedRequest(queue[i]);
                } catch (error) {
                    remaining = queue.slice(i);

                    if (error.message === "AUTH_REQUIRED") {
                        showMessage("warning", "Connexion requise pour synchroniser les commandes hors ligne.");
                    }

                    break;
                }
            }

            writeQueue(remaining);

            if (!remaining.length && queue.length) {
                showMessage("success", "Commandes hors ligne synchronisees.");
                window.location.reload();
            }
        } finally {
            syncing = false;
        }
    }

    document.addEventListener("submit", function (event) {
        var form = event.target;

        if (!form.matches || !form.matches(FORM_SELECTOR)) {
            return;
        }

        event.preventDefault();

        if (!navigator.onLine) {
            queueForm(form);
            return;
        }

        submitFormOnline(form).catch(function (error) {
            if (error.message === "AUTH_REQUIRED") {
                showMessage("warning", "Connexion requise pour enregistrer cette commande.");
                return;
            }

            queueForm(form);
        });
    }, true);

    window.addEventListener("online", syncQueue);
    document.addEventListener("visibilitychange", function () {
        if (!document.hidden) {
            syncQueue();
        }
    });
    document.addEventListener("DOMContentLoaded", function () {
        updateQueuedCount(readQueue().length);
        syncQueue();
    });

    window.PouletBiniOfflineSync = {
        count: function () {
            return readQueue().length;
        },
        sync: syncQueue
    };
})();
