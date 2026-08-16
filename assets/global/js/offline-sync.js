(function () {
    "use strict";

    var STORAGE_KEY = "pouletbini.offline.requests";
    var LOCK_KEY = "pouletbini.offline.sync.lock";
    var FORM_SELECTOR = "form[data-offline-sync]";
    var PANEL_ID = "offline-sync-panel";
    var LOCK_TTL = 30000;
    var TAB_ID = Date.now().toString(36) + Math.random().toString(36).slice(2);
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
        renderQueuePanel(readQueue());
    }

    function getFieldValue(request, name) {
        var found = request.fields.find(function (field) {
            return field[0] === name;
        });

        return found ? found[1] : "";
    }

    function getItemsCount(request) {
        var itemIndexes = {};

        request.fields.forEach(function (field) {
            var match = field[0].match(/^items\[(\d+)]\[produit]$/);

            if (match) {
                itemIndexes[match[1]] = true;
            }
        });

        return Object.keys(itemIndexes).length;
    }

    function formatDate(value) {
        if (!value) {
            return "";
        }

        try {
            return new Intl.DateTimeFormat("fr-FR", {
                dateStyle: "short",
                timeStyle: "short"
            }).format(new Date(value));
        } catch (error) {
            return value;
        }
    }

    function describeRequest(request) {
        var receiver = getFieldValue(request, "receiver_name") || "Client non renseigne";
        var phone = getFieldValue(request, "receiver_phone");
        var total = getFieldValue(request, "frais_livraison");
        var itemCount = getItemsCount(request);
        var details = [];

        if (phone) {
            details.push(phone);
        }

        if (itemCount) {
            details.push(itemCount + " article" + (itemCount > 1 ? "s" : ""));
        }

        if (total) {
            details.push("Livraison " + total + " FCFA");
        }

        return {
            title: receiver,
            meta: details.join(" - ")
        };
    }

    function ensureQueuePanel() {
        var panel = document.getElementById(PANEL_ID);

        if (panel) {
            return panel;
        }

        var wrapper = document.createElement("div");
        wrapper.className = "offline-sync-widget";
        wrapper.innerHTML = [
            '<button type="button" class="offline-sync-button" data-offline-sync-toggle aria-expanded="false">',
            '    <span class="offline-sync-button__count" data-offline-sync-count>0</span>',
            '    <span>Commandes en attente</span>',
            '</button>',
            '<section class="offline-sync-panel" id="' + PANEL_ID + '" data-offline-sync-panel hidden>',
            '    <div class="offline-sync-panel__header">',
            '        <div>',
            '            <strong>Commandes en attente</strong>',
            '            <span data-offline-sync-summary>Aucune commande</span>',
            '        </div>',
            '        <button type="button" class="offline-sync-panel__close" data-offline-sync-close aria-label="Fermer">&times;</button>',
            '    </div>',
            '    <div class="offline-sync-panel__list" data-offline-sync-list></div>',
            '    <div class="offline-sync-panel__footer">',
            '        <button type="button" class="offline-sync-panel__sync" data-offline-sync-now>Synchroniser</button>',
            '    </div>',
            '</section>'
        ].join("");

        document.body.appendChild(wrapper);

        wrapper.querySelector("[data-offline-sync-toggle]").addEventListener("click", function () {
            toggleQueuePanel();
        });
        wrapper.querySelector("[data-offline-sync-close]").addEventListener("click", function () {
            setQueuePanelOpen(false);
        });
        wrapper.querySelector("[data-offline-sync-now]").addEventListener("click", function () {
            syncQueue();
        });

        return wrapper.querySelector("[data-offline-sync-panel]");
    }

    function setQueuePanelOpen(open) {
        var panel = ensureQueuePanel();
        var toggle = document.querySelector("[data-offline-sync-toggle]");

        panel.hidden = !open;

        if (toggle) {
            toggle.setAttribute("aria-expanded", open ? "true" : "false");
        }
    }

    function toggleQueuePanel() {
        var panel = ensureQueuePanel();

        setQueuePanelOpen(panel.hidden);
    }

    function renderQueuePanel(queue) {
        if (!document.body) {
            return;
        }

        var panel = ensureQueuePanel();
        var widget = panel.closest(".offline-sync-widget");
        var count = queue.length;
        var counter = widget.querySelector("[data-offline-sync-count]");
        var summary = widget.querySelector("[data-offline-sync-summary]");
        var list = widget.querySelector("[data-offline-sync-list]");
        var syncButton = widget.querySelector("[data-offline-sync-now]");

        widget.classList.toggle("has-queue", count > 0);
        counter.textContent = String(count);
        summary.textContent = count ? count + " commande" + (count > 1 ? "s" : "") + " a synchroniser" : "Aucune commande";
        syncButton.disabled = !count || !navigator.onLine || syncing;

        if (!count) {
            list.innerHTML = '<div class="offline-sync-empty">Aucune commande en attente.</div>';
            return;
        }

        list.innerHTML = queue.map(function (request, index) {
            var description = describeRequest(request);

            return [
                '<article class="offline-sync-item">',
                '    <div class="offline-sync-item__index">' + (index + 1) + '</div>',
                '    <div class="offline-sync-item__body">',
                '        <strong>' + escapeHtml(description.title) + '</strong>',
                '        <span>' + escapeHtml(description.meta || "Commande hors ligne") + '</span>',
                '        <small>Enregistree le ' + escapeHtml(formatDate(request.createdAt)) + '</small>',
                '    </div>',
                '</article>'
            ].join("");
        }).join("");
    }

    function escapeHtml(value) {
        return String(value).replace(/[&<>"']/g, function (character) {
            return {
                "&": "&amp;",
                "<": "&lt;",
                ">": "&gt;",
                '"': "&quot;",
                "'": "&#039;"
            }[character];
        });
    }

    function serializeForm(form) {
        var formData = new FormData(form);
        var fields = [];
        var requestId = Date.now().toString(36) + Math.random().toString(36).slice(2);
        var hasOfflineSyncId = false;

        formData.forEach(function (value, key) {
            if (value instanceof File) {
                return;
            }

            if (key === "offline_sync_id") {
                hasOfflineSyncId = true;
            }

            fields.push([key, value]);
        });

        if (!hasOfflineSyncId) {
            fields.push(["offline_sync_id", requestId]);
        }

        return {
            id: requestId,
            action: form.action,
            method: (form.method || "POST").toUpperCase(),
            fields: fields,
            createdAt: new Date().toISOString(),
            label: form.getAttribute("data-offline-label") || "commande"
        };
    }

    function acquireSyncLock() {
        var now = Date.now();

        try {
            var currentLock = JSON.parse(localStorage.getItem(LOCK_KEY) || "null");

            if (currentLock && currentLock.expiresAt > now && currentLock.owner !== TAB_ID) {
                return false;
            }

            localStorage.setItem(LOCK_KEY, JSON.stringify({
                owner: TAB_ID,
                expiresAt: now + LOCK_TTL
            }));

            return true;
        } catch (error) {
            return true;
        }
    }

    function refreshSyncLock() {
        try {
            localStorage.setItem(LOCK_KEY, JSON.stringify({
                owner: TAB_ID,
                expiresAt: Date.now() + LOCK_TTL
            }));
        } catch (error) {}
    }

    function releaseSyncLock() {
        try {
            var currentLock = JSON.parse(localStorage.getItem(LOCK_KEY) || "null");

            if (currentLock && currentLock.owner === TAB_ID) {
                localStorage.removeItem(LOCK_KEY);
            }
        } catch (error) {}
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

        if (!acquireSyncLock()) {
            return;
        }

        var queue = readQueue();

        if (!queue.length) {
            updateQueuedCount(0);
            releaseSyncLock();
            return;
        }

        syncing = true;
        renderQueuePanel(queue);

        try {
            var remaining = [];

            for (var i = 0; i < queue.length; i++) {
                try {
                    refreshSyncLock();
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
            releaseSyncLock();
            renderQueuePanel(readQueue());
        }
    }

    document.addEventListener("submit", function (event) {
        var form = event.target;

        if (!form.matches || !form.matches(FORM_SELECTOR)) {
            return;
        }

        event.preventDefault();

        if (form.dataset.offlineSyncSubmitting === "1") {
            return;
        }

        form.dataset.offlineSyncSubmitting = "1";

        if (!navigator.onLine) {
            queueForm(form);
            delete form.dataset.offlineSyncSubmitting;
            return;
        }

        submitFormOnline(form).catch(function (error) {
            if (error.message === "AUTH_REQUIRED") {
                showMessage("warning", "Connexion requise pour enregistrer cette commande.");
                delete form.dataset.offlineSyncSubmitting;
                return;
            }

            queueForm(form);
            delete form.dataset.offlineSyncSubmitting;
        });
    }, true);

    window.addEventListener("online", syncQueue);
    document.addEventListener("visibilitychange", function () {
        if (!document.hidden) {
            syncQueue();
        }
    });
    document.addEventListener("DOMContentLoaded", function () {
        renderQueuePanel(readQueue());
        updateQueuedCount(readQueue().length);
        syncQueue();
    });
    window.addEventListener("storage", function (event) {
        if (event.key === STORAGE_KEY) {
            renderQueuePanel(readQueue());
            updateQueuedCount(readQueue().length);
        }
    });

    window.PouletBiniOfflineSync = {
        count: function () {
            return readQueue().length;
        },
        sync: syncQueue
    };
})();
