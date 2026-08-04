/**
 * InterConnect — Dynamic Animated Favicon
 *
 * Draws the InterConnect brand logo on an off-screen canvas and animates
 * its colors in a smooth neon pulse (Purple → White → Dark → Purple).
 * The canvas is exported as a data URL and written to the <link rel="icon">
 * element on every animation frame (throttled to ~12fps for tab performance).
 *
 * Color palette:
 *   Neon Purple  #4F47E6
 *   Light Purple #8B83FF
 *   White        #FFFFFF
 *   Deep Dark    #191C1D
 */
(function () {
    'use strict';

    /* ─── Config ─────────────────────────────────────────── */
    const SIZE        = 64;          // canvas resolution (px)
    const FPS         = 20;          // updates per second
    const CYCLE_MS    = 3000;        // full colour-cycle duration (ms)
    const INTERVAL_MS = 1000 / FPS;

    /* ─── Colour helpers ─────────────────────────────────── */
    function hexToRgb(hex) {
        const r = parseInt(hex.slice(1, 3), 16);
        const g = parseInt(hex.slice(3, 5), 16);
        const b = parseInt(hex.slice(5, 7), 16);
        return [r, g, b];
    }

    function lerpColour(c1, c2, t) {
        return [
            Math.round(c1[0] + (c2[0] - c1[0]) * t),
            Math.round(c1[1] + (c2[1] - c1[1]) * t),
            Math.round(c1[2] + (c2[2] - c1[2]) * t),
        ];
    }

    function rgbToHex([r, g, b]) {
        return '#' + [r, g, b].map(v => v.toString(16).padStart(2, '0')).join('');
    }

    /**
     * Given a t ∈ [0, 1] drive through three keyframes:
     *   0.0 → 0.33  Purple → White
     *   0.33 → 0.67 White  → Dark
     *   0.67 → 1.0  Dark   → Purple
     */
    const PURPLE = hexToRgb('#4F47E6');
    const LIGHT  = hexToRgb('#FFFFFF');
    const DARK   = hexToRgb('#191C1D');
    const ACCENT = hexToRgb('#8B83FF'); // subtle secondary path tint

    function neonColour(t) {
        // primary shape colour
        let primary;
        if (t < 0.33) {
            primary = lerpColour(PURPLE, LIGHT, t / 0.33);
        } else if (t < 0.67) {
            primary = lerpColour(LIGHT, DARK, (t - 0.33) / 0.34);
        } else {
            primary = lerpColour(DARK, PURPLE, (t - 0.67) / 0.33);
        }

        // secondary (slightly offset by 120°/cycle)
        const t2 = (t + 0.33) % 1;
        let secondary;
        if (t2 < 0.33) {
            secondary = lerpColour(ACCENT, LIGHT, t2 / 0.33);
        } else if (t2 < 0.67) {
            secondary = lerpColour(LIGHT, DARK, (t2 - 0.33) / 0.34);
        } else {
            secondary = lerpColour(DARK, ACCENT, (t2 - 0.67) / 0.33);
        }

        return { primary: rgbToHex(primary), secondary: rgbToHex(secondary) };
    }

    /* ─── Canvas setup ───────────────────────────────────── */
    const canvas  = document.createElement('canvas');
    canvas.width  = SIZE;
    canvas.height = SIZE;
    const ctx = canvas.getContext('2d');

    /* ─── SVG paths (from new Logo_dark_dx00tg.svg, viewBox 0 0 28 32) ──
     *
     *  We scale from 28×32 → 64×64 (fit with padding).
     *  Scale factor: 64 / max(28, 32) = 2.0 with padding.
     */
    const ORIG_W = 28;
    const ORIG_H = 32;
    const PAD    = 6;
    const scale  = Math.min((SIZE - PAD * 2) / ORIG_W, (SIZE - PAD * 2) / ORIG_H);
    const ox     = (SIZE - ORIG_W * scale) / 2;
    const oy     = (SIZE - ORIG_H * scale) / 2;

    /**
     * Build Path2D objects for the new brand icon (top dot and main body).
     */
    // Top dot mark
    const dotD = new Path2D(
        'M13.9334 0.00117504C15.2436 0.013806 16.5208 0.414069 17.6039 1.1515C18.687 1.88893 19.5275 2.93052 20.0195 4.14496C20.5115 5.35939 20.633 6.69231 20.3685 7.97565C20.1041 9.259 19.4656 10.4353 18.5336 11.3564C17.6016 12.2774 16.4178 12.9018 15.1314 13.151C13.845 13.4002 12.5136 13.263 11.3051 12.7566C10.0966 12.2503 9.06505 11.3974 8.34052 10.3057C7.61598 9.21391 7.2309 7.93207 7.23381 6.62177C7.22762 5.74393 7.39728 4.87374 7.73277 4.06252C8.06826 3.25129 8.56279 2.51546 9.1872 1.89841C9.81161 1.28136 10.5533 0.795594 11.3684 0.469749C12.1835 0.143903 13.0557 -0.0154196 13.9334 0.00117504Z'
    );

    // Main brand shape
    const mainD = new Path2D(
        'M27.7388 13.2846V23.6218C25.5532 23.5558 23.4266 24.3363 21.8027 25.8005C20.1787 27.2646 19.1829 29.2993 19.0229 31.48C19.0041 31.6568 18.9891 31.8561 18.9815 32.0254H8.86632C8.86632 31.9276 8.83247 31.8298 8.81366 31.7358C7.798 26.3302 6.17295 24.8631 0 23.5127V13.3561C5.01811 12.9498 10.2017 15.2369 13.8882 19.104C17.3038 15.5003 22.1903 13.0702 27.7388 13.2846Z'
    );

    /* ─── Draw one frame ─────────────────────────────────── */
    function drawFrame(colours) {
        ctx.clearRect(0, 0, SIZE, SIZE);

        // Apply scale + centering transform
        ctx.save();
        ctx.translate(ox, oy);
        ctx.scale(scale, scale);

        // Main brand shape
        ctx.fillStyle = colours.primary;
        ctx.fill(mainD);

        // Top dot shape
        ctx.fillStyle = colours.secondary;
        ctx.fill(dotD);

        ctx.restore();
    }

    /* ─── Favicon link reference ─────────────────────────── */
    function getFaviconLink() {
        let link = document.querySelector('link[rel="icon"]');
        if (!link) {
            link = document.createElement('link');
            link.rel  = 'icon';
            link.type = 'image/png';
            document.head.appendChild(link);
        }
        return link;
    }

    /* ─── Animation loop ─────────────────────────────────── */
    let startTime = null;
    let lastFrameTime = 0;
    const MAX_ANIMATION_MS = 10000; // Animate for 10 seconds, then stop
    const faviconLink = getFaviconLink();

    function animate(timestamp) {
        if (!startTime) startTime = timestamp;
        const elapsed = timestamp - startTime;

        if (elapsed >= MAX_ANIMATION_MS) {
            // Settle on clean solid brand purple icon and stop animation loop
            drawFrame({ primary: '#4F47E6', secondary: '#4F47E6' });
            faviconLink.href = canvas.toDataURL('image/png');
            return;
        }

        if (timestamp - lastFrameTime >= INTERVAL_MS) {
            const t = (timestamp % CYCLE_MS) / CYCLE_MS;  // 0 → 1
            const colours = neonColour(t);
            drawFrame(colours);
            faviconLink.href = canvas.toDataURL('image/png');
            lastFrameTime = timestamp;
        }
        requestAnimationFrame(animate);
    }

    // Kick off animation when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => requestAnimationFrame(animate));
    } else {
        requestAnimationFrame(animate);
    }

})();
