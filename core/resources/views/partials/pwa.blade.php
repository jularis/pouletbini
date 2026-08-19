<link rel="manifest" href="{{ url('/manifest.json') }}">
<meta name="theme-color" content="#f15b2a">
<meta name="application-name" content="Poulet Bini">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="Poulet Bini">
<link rel="apple-touch-icon" href="{{ asset('assets/images/logoIcon/favicon.png') }}">

<style>
    .connection-status {
        align-items: center;
        border-radius: 6px;
        bottom: 16px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.18);
        color: #fff;
        display: none;
        font-size: 13px;
        font-weight: 700;
        gap: 8px;
        left: 16px;
        line-height: 1.3;
        max-width: calc(100% - 32px);
        padding: 10px 14px;
        position: fixed;
        z-index: 99999;
    }

    .connection-status::before {
        background: currentColor;
        border-radius: 50%;
        content: "";
        height: 8px;
        opacity: .9;
        width: 8px;
    }

    .connection-status.is-offline {
        background: #b42318;
        display: inline-flex;
    }

    .connection-status.is-online {
        background: #047857;
        display: inline-flex;
    }

    .offline-sync-widget {
        bottom: 16px;
        display: none;
        position: fixed;
        right: 16px;
        width: min(360px, calc(100% - 32px));
        z-index: 99998;
    }

    .offline-sync-widget.has-queue {
        display: block;
    }

    .offline-sync-button {
        align-items: center;
        background: #1f2937;
        border: 0;
        border-radius: 6px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.2);
        color: #fff;
        display: inline-flex;
        font-size: 13px;
        font-weight: 700;
        gap: 8px;
        justify-content: center;
        min-height: 42px;
        padding: 10px 14px;
        width: 100%;
    }

    .offline-sync-button__count {
        align-items: center;
        background: #f15b2a;
        border-radius: 999px;
        display: inline-flex;
        height: 24px;
        justify-content: center;
        min-width: 24px;
        padding: 0 7px;
    }

    .offline-sync-panel {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.22);
        color: #111827;
        margin-bottom: 8px;
        max-height: min(520px, calc(100vh - 92px));
        overflow: hidden;
    }

    .offline-sync-panel__header,
    .offline-sync-panel__footer {
        align-items: center;
        display: flex;
        justify-content: space-between;
        padding: 12px 14px;
    }

    .offline-sync-panel__header {
        border-bottom: 1px solid #eef0f3;
    }

    .offline-sync-panel__header strong {
        display: block;
        font-size: 14px;
        line-height: 1.3;
    }

    .offline-sync-panel__header span {
        color: #6b7280;
        display: block;
        font-size: 12px;
        line-height: 1.4;
        margin-top: 2px;
    }

    .offline-sync-panel__close {
        background: transparent;
        border: 0;
        color: #6b7280;
        font-size: 24px;
        line-height: 1;
        padding: 2px 4px;
    }

    .offline-sync-panel__list {
        max-height: 360px;
        overflow: auto;
        padding: 8px;
    }

    .offline-sync-item {
        align-items: flex-start;
        border: 1px solid #eef0f3;
        border-radius: 6px;
        display: flex;
        gap: 10px;
        padding: 10px;
    }

    .offline-sync-item + .offline-sync-item {
        margin-top: 8px;
    }

    .offline-sync-item__index {
        align-items: center;
        background: #fff1ec;
        border-radius: 999px;
        color: #b93815;
        display: inline-flex;
        flex: 0 0 28px;
        font-size: 12px;
        font-weight: 700;
        height: 28px;
        justify-content: center;
    }

    .offline-sync-item__body {
        min-width: 0;
    }

    .offline-sync-item__body strong,
    .offline-sync-item__body span,
    .offline-sync-item__body small {
        display: block;
        overflow-wrap: anywhere;
    }

    .offline-sync-item__body strong {
        font-size: 13px;
        line-height: 1.35;
    }

    .offline-sync-item__body span {
        color: #4b5563;
        font-size: 12px;
        line-height: 1.4;
        margin-top: 2px;
    }

    .offline-sync-item__body small,
    .offline-sync-empty {
        color: #6b7280;
        font-size: 11px;
        line-height: 1.4;
        margin-top: 4px;
    }

    .offline-sync-empty {
        padding: 14px;
        text-align: center;
    }

    .offline-sync-panel__footer {
        border-top: 1px solid #eef0f3;
    }

    .offline-sync-panel__sync {
        background: #047857;
        border: 0;
        border-radius: 6px;
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        min-height: 38px;
        padding: 8px 12px;
        width: 100%;
    }

    .offline-sync-panel__sync:disabled {
        cursor: not-allowed;
        opacity: .55;
    }

    @media (max-width: 575px) {
        .offline-sync-widget {
            bottom: 12px;
            right: 12px;
            width: calc(100% - 24px);
        }

        .connection-status {
            bottom: 64px;
        }
    }
</style>

<script>
    (function () {
        "use strict";

        if ("serviceWorker" in navigator) {
            window.addEventListener("load", function () {
                navigator.serviceWorker.register("{{ url('/sw.js') }}", { scope: "{{ rtrim(url('/'), '/') }}/" }).catch(function () {});
            });
        }

        function showStatus(online) {
            var status = document.querySelector("[data-connection-status]");

            if (!status) {
                status = document.createElement("div");
                status.setAttribute("data-connection-status", "");
                status.className = "connection-status";
                document.body.appendChild(status);
            }

            status.classList.toggle("is-online", online);
            status.classList.toggle("is-offline", !online);
            status.textContent = online ? "Connexion retablie" : "Mode hors connexion";

            if (online) {
                window.clearTimeout(status.hideTimer);
                status.hideTimer = window.setTimeout(function () {
                    status.classList.remove("is-online");
                }, 3000);
            }
        }

        window.addEventListener("online", function () {
            showStatus(true);
        });

        window.addEventListener("offline", function () {
            showStatus(false);
        });

        document.addEventListener("DOMContentLoaded", function () {
            if (!navigator.onLine) {
                showStatus(false);
            }
        });
    })();
</script>
<script src="{{ asset('assets/global/js/offline-sync.js') }}"></script>
