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
</style>

<script>
    (function () {
        "use strict";

        if ("serviceWorker" in navigator) {
            window.addEventListener("load", function () {
                navigator.serviceWorker.register("{{ url('/sw.js') }}", { scope: "{{ url('/') }}/" }).catch(function () {});
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
