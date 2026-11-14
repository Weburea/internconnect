/**
 * InternConnect scroll reveal for public pages (home, find jobs, pricing, e-learning, how it works ...).
 * Enabled by <body data-reveal="auto"> (frontend/shared/layouts/app.php, landing views only).
 *
 * Inside <main>, headings, badges, lead text, cards and images fade up as they enter the screen;
 * siblings in the same grid are staggered. After each animation the helper attributes are removed,
 * so the page's own hover transforms and transitions work unchanged. Cards added later by Alpine
 * (filters, x-for) animate the same way. Does nothing when the visitor prefers reduced motion.
 * Rule: .agents/rules/rules.md "Public Page Motion Rule".
 */
(function () {
    var body = document.body;
    if (!body || body.getAttribute('data-reveal') !== 'auto') return;
    if (!('IntersectionObserver' in window)) return;
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    // translate/scale (not transform) so cards that already use transform (lifted, scaled) never jump.
    var css = '.ic-reveal-on [data-ic-reveal]{opacity:0;translate:0 18px;transition:opacity .6s cubic-bezier(.16,1,.3,1),translate .6s cubic-bezier(.16,1,.3,1),scale .6s cubic-bezier(.16,1,.3,1);transition-delay:var(--ic-reveal-delay,0ms)}'
        + '.ic-reveal-on [data-ic-reveal="zoom"]{translate:0 0;scale:.97}'
        + '.ic-reveal-on [data-ic-reveal].is-in{opacity:1;translate:0 0;scale:1}';
    var style = document.createElement('style');
    style.textContent = css;
    document.head.appendChild(style);

    var root = document.getElementById('main-content') || document.querySelector('main') || body;
    var SELECTORS = [
        'h1', 'section h2', '.ic-badge', 'section h2 + p', 'section h1 + p',
        'article',
        '[class*="-card"]:not([class*="-card-"]):not([class*="card-"])',
        '[class$="-card"]',
        'section img:not([width="16"]):not([width="20"]):not([width="24"])',
    ].join(',');
    var MAX = 400;
    var seen = new WeakSet();
    var count = 0;

    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            var el = entry.target;
            io.unobserve(el);
            el.classList.add('is-in');
            var delay = parseInt(el.style.getPropertyValue('--ic-reveal-delay') || '0', 10) || 0;
            setTimeout(function () {
                el.removeAttribute('data-ic-reveal');
                el.classList.remove('is-in');
                el.style.removeProperty('--ic-reveal-delay');
            }, 700 + delay);
        });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });

    function eligible(el) {
        if (seen.has(el) || count >= MAX) return false;
        if (el.closest('nav, header, footer, [role="dialog"], [aria-modal="true"], .elearn-modal-backdrop, [x-cloak]')) return false;
        if (el.closest('[data-no-reveal]')) return false;
        // Only the outermost target animates (a card's own heading moves with the card).
        // Cards inside CSS-animated tracks (testimonial marquees) keep their own motion.
        var p = el.parentElement;
        while (p && p !== root) {
            if (p.hasAttribute('data-ic-reveal') || seen.has(p)) return false;
            if (getComputedStyle(p).animationName !== 'none') return false;
            p = p.parentElement;
        }
        var rect = el.getBoundingClientRect();
        // Items parked off-screen sideways (marquees, carousels) are left as they are.
        if (rect.width > 0 && (rect.left >= window.innerWidth || rect.right <= 0)) return false;
        return rect.width > 0 || rect.height > 0 || el.offsetParent !== null;
    }

    // Tailwind-built cards have no "card" class: repeated rounded boxes side by side in a grid count as cards.
    function utilityCards() {
        var out = [];
        root.querySelectorAll('[class*="rounded-xl"],[class*="rounded-2xl"],[class*="rounded-3xl"]').forEach(function (el) {
            var parent = el.parentElement;
            if (!parent || parent.children.length < 2) return;
            var same = 0;
            for (var i = 0; i < parent.children.length; i++) { if (String(parent.children[i].className).indexOf('rounded-') !== -1) same++; }
            if (same < 2) return;
            var h = el.getBoundingClientRect().height;
            if (h >= 80 && h <= 1000) out.push(el);
        });
        // Grid children styled inline (e.g. pricing plans): rounded boxes in a Tailwind grid.
        root.querySelectorAll('.grid > *').forEach(function (el) {
            if (out.indexOf(el) !== -1 || el.parentElement.children.length < 2) return;
            var cs = getComputedStyle(el);
            var h = el.getBoundingClientRect().height;
            if (parseFloat(cs.borderTopLeftRadius) >= 12 && h >= 80 && h <= 1000) out.push(el);
        });
        return out;
    }

    function scan() {
        // Outer cards first, so a card animates as one block (its own heading is skipped).
        var nodes = utilityCards().concat(Array.prototype.slice.call(root.querySelectorAll(SELECTORS)));
        var groups = new Map();
        for (var i = 0; i < nodes.length; i++) {
            var el = nodes[i];
            if (!eligible(el)) continue;
            seen.add(el);
            count++;
            el.setAttribute('data-ic-reveal', el.tagName === 'IMG' ? 'zoom' : 'up');
            var key = el.parentElement;
            var n = groups.get(key) || 0;
            groups.set(key, n + 1);
            el.style.setProperty('--ic-reveal-delay', Math.min(n, 6) * 70 + 'ms');
            io.observe(el);
        }
    }

    document.documentElement.classList.add('ic-reveal-on');
    scan();
    document.addEventListener('DOMContentLoaded', scan);
    document.addEventListener('alpine:initialized', function () { setTimeout(scan, 30); });
    // Cards rendered later (filters, x-for templates) animate too.
    var pending = null;
    new MutationObserver(function () {
        if (pending) return;
        pending = setTimeout(function () { pending = null; scan(); }, 80);
    }).observe(root, { childList: true, subtree: true });
    // Safety net: never leave content hidden (e.g. elements inside horizontally scrolled strips).
    setTimeout(function () {
        root.querySelectorAll('[data-ic-reveal]:not(.is-in)').forEach(function (el) {
            var r = el.getBoundingClientRect();
            if (r.top < window.innerHeight && r.bottom > 0) { el.classList.add('is-in'); }
        });
    }, 2500);
})();
