/**
 * Visit beacon (docs/56-location-and-visits.md): one page view after load, then a heartbeat every
 * minute while the tab is visible, so the admin live map shows who is online. Uses sendBeacon
 * (ic-csrf.js adds the CSRF token); nothing is sent while the tab is hidden.
 */
(function () {
    if (window.__icVisit) return;
    window.__icVisit = true;
    var meta = document.querySelector('meta[name="ic-visit-url"]');
    if (!meta || !navigator.sendBeacon) return;
    var url = meta.getAttribute('content');
    function send(heartbeat) {
        try {
            var body = new Blob([JSON.stringify({ page: location.pathname, heartbeat: !!heartbeat })], { type: 'application/json' });
            navigator.sendBeacon(url, body);
        } catch (e) {}
    }
    function start() {
        send(false);
        setInterval(function () { if (document.visibilityState === 'visible') send(true); }, 60000);
    }
    if (document.readyState === 'complete') start(); else window.addEventListener('load', start);
})();
