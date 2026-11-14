/**
 * Cookie consent (docs/54-email-newsletter-contact.md, "Cookies").
 * Choices live in the first-party cookie `ic_consent` (12 months) so the server can read them too:
 *   {"v":1,"functional":true,"analytics":false,"t":1731400000}
 * Essential cookies (session, security) never need consent. Analytics (visitor id `ic_vid`)
 * is only set after consent. Changing a choice fires `ic-consent-changed` on window.
 */
(function () {
    if (window.icConsent) return;
    var NAME = 'ic_consent';

    function read() {
        var m = document.cookie.match(/(?:^|;\s*)ic_consent=([^;]+)/);
        if (!m) return null;
        try {
            var v = JSON.parse(decodeURIComponent(m[1]));
            return v && v.v === 1 ? v : null;
        } catch (e) {
            return null;
        }
    }

    function write(choice) {
        var value = {
            v: 1,
            functional: !!choice.functional,
            analytics: !!choice.analytics,
            t: Math.floor(Date.now() / 1000)
        };
        var secure = location.protocol === 'https:' ? '; Secure' : '';
        document.cookie = NAME + '=' + encodeURIComponent(JSON.stringify(value)) + '; Max-Age=' + (365 * 86400) + '; Path=/; SameSite=Lax' + secure;
        if (!value.analytics) {
            // Withdrawing consent removes the analytics visitor id straight away.
            document.cookie = 'ic_vid=; Max-Age=0; Path=/; SameSite=Lax' + secure;
        }
        window.dispatchEvent(new CustomEvent('ic-consent-changed', { detail: value }));
        return value;
    }

    window.icConsent = {
        /** Saved choice, or null when the visitor has not chosen yet. */
        get: read,
        /** Has the visitor chosen yet? */
        decided: function () { return read() !== null; },
        allows: function (category) {
            if (category === 'essential') return true;
            var c = read();
            return !!(c && c[category]);
        },
        set: write,
        acceptAll: function () { return write({ functional: true, analytics: true }); },
        essentialOnly: function () { return write({ functional: false, analytics: false }); }
    };
})();
