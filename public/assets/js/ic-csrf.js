/**
 * Adds the CSRF token (from <meta name="csrf-token">) to every same-origin state-changing request:
 * fetch(), XMLHttpRequest, <form method="post"> submits (including form.submit()) and sendBeacon().
 * Loaded first, without defer, by every layout. Server check: App\Core\Security::verifyCsrf().
 * Spec: docs/51-api-and-security.md
 */
(function () {
    if (window.__icCsrf) return;
    var meta = document.querySelector('meta[name="csrf-token"]');
    var token = meta ? meta.getAttribute('content') : '';
    if (!token) return;
    window.__icCsrf = token;

    var SAFE = { GET: 1, HEAD: 1, OPTIONS: 1 };
    function sameOrigin(url) {
        try { return new URL(url, window.location.href).origin === window.location.origin; } catch (e) { return false; }
    }
    function isApi(url) {
        try { return new URL(url, window.location.href).pathname.indexOf('/api/') !== -1; } catch (e) { return false; }
    }

    // fetch()
    if (window.fetch) {
        var nativeFetch = window.fetch;
        window.fetch = function (input, init) {
            init = init || {};
            var url = typeof input === 'string' ? input : (input && input.url) || '';
            var method = String(init.method || (input && input.method) || 'GET').toUpperCase();
            if (!SAFE[method] && sameOrigin(url) && !isApi(url)) {
                var headers = new Headers(init.headers || (typeof input !== 'string' && input && input.headers) || {});
                if (!headers.has('X-CSRF-Token')) headers.set('X-CSRF-Token', token);
                init = Object.assign({}, init, { headers: headers, credentials: init.credentials || 'same-origin' });
            }
            return nativeFetch.call(this, input, init);
        };
    }

    // XMLHttpRequest
    var open = XMLHttpRequest.prototype.open;
    var send = XMLHttpRequest.prototype.send;
    XMLHttpRequest.prototype.open = function (method, url) {
        this.__icMethod = String(method || 'GET').toUpperCase();
        this.__icUrl = url;
        return open.apply(this, arguments);
    };
    XMLHttpRequest.prototype.send = function () {
        if (!SAFE[this.__icMethod] && sameOrigin(this.__icUrl) && !isApi(this.__icUrl)) {
            try { this.setRequestHeader('X-CSRF-Token', token); } catch (e) {}
        }
        return send.apply(this, arguments);
    };

    // Forms
    function addField(form) {
        if (!form || String(form.method || 'get').toLowerCase() !== 'post') return;
        if (!sameOrigin(form.action || window.location.href)) return;
        var input = form.querySelector('input[name="_csrf"]');
        if (!input) {
            input = document.createElement('input');
            input.type = 'hidden';
            input.name = '_csrf';
            form.appendChild(input);
        }
        input.value = token;
    }
    document.addEventListener('submit', function (e) { addField(e.target); }, true);
    var nativeSubmit = HTMLFormElement.prototype.submit;
    HTMLFormElement.prototype.submit = function () { addField(this); return nativeSubmit.apply(this, arguments); };
    // FormData built from a form element before fetch() still gets the header above.

    // sendBeacon cannot set headers: pass the token in the query string.
    if (navigator.sendBeacon) {
        var beacon = navigator.sendBeacon.bind(navigator);
        navigator.sendBeacon = function (url, data) {
            if (sameOrigin(url) && !isApi(url)) {
                url += (String(url).indexOf('?') === -1 ? '?' : '&') + '_csrf=' + encodeURIComponent(token);
            }
            return beacon(url, data);
        };
    }
})();
