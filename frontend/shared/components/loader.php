<?php
/**
 * Component: Global Page Loader
 *
 * Displays a premium animated loading screen.
 * Redesigned to maintain absolute aspect ratio integrity, eliminating stretching.
 * Features a clean breathing logo mark surrounded by a sleek revolving spinner ring.
 *
 * Included via:
 *   - frontend/shared/layouts/app.php
 *   - frontend/shared/layouts/auth.php  (only for /welcome, /welcome-pro)
 *
 * Controlled by $showLoader variable (defaults true).
 */
?>
<div id="global-page-loader"
     style="
        position: fixed;
        inset: 0;
        z-index: 9999999;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #ffffff;
        transition: opacity 0.6s ease-out;
     ">

    <!-- Premium Loader Ring and Centered Logo -->
    <div class="premium-loader-container">
        
        <!-- Rotating Spinner Ring -->
        <div class="loader-spinner-ring"></div>

        <!-- Bounded Logo Mark (Aspect Ratio Locked) -->
        <div class="loader-logo-mark-wrap">
            <svg class="loader-logo-mark" viewBox="0 0 28 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Dot (Path 1) -->
                <path class="loader-path-color" d="M13.9334 0.00117504C15.2436 0.013806 16.5208 0.414069 17.6039 1.1515C18.687 1.88893 19.5275 2.93052 20.0195 4.14496C20.5115 5.35939 20.633 6.69231 20.3685 7.97565C20.1041 9.259 19.4656 10.4353 18.5336 11.3564C17.6016 12.2774 16.4178 12.9018 15.1314 13.151C13.845 13.4002 12.5136 13.263 11.3051 12.7566C10.0966 12.2503 9.06505 11.3974 8.34052 10.3057C7.61598 9.21391 7.2309 7.93207 7.23381 6.62177C7.22762 5.74393 7.39728 4.87374 7.73277 4.06252C8.06826 3.25129 8.56279 2.51546 9.1872 1.89841C9.81161 1.28136 10.5533 0.795594 11.3684 0.469749C12.1835 0.143903 13.0557 -0.0154196 13.9334 0.00117504Z" fill="#4F48E6"/>
                <!-- Fork Body (Path 2) -->
                <path class="loader-path-color" d="M27.7388 13.2846V23.6218C25.5532 23.5558 23.4266 24.3363 21.8027 25.8005C20.1787 27.2646 19.1829 29.2993 19.0229 31.48C19.0041 31.6568 18.9891 31.8561 18.9815 32.0254H8.86632C8.86632 31.9276 8.83247 31.8298 8.81366 31.7358C7.798 26.3302 6.17295 24.8631 0 23.5127V13.3561C5.01811 12.9498 10.2017 15.2369 13.8882 19.104C17.3038 15.5003 22.1903 13.0702 27.7388 13.2846Z" fill="#4F48E6"/>
            </svg>
        </div>

    </div>

    <!-- Loading label -->
    <span class="loader-label">Loading</span>

</div>

<style>
/* ================================================================
   GLOBAL PAGE LOADER  —  Modern Shimmer & Revolving Spinner
   ================================================================ */

.premium-loader-container {
    position: relative;
    width: 120px;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* ── Rotating Outer Ring ─────────────────────────────────────── */
.loader-spinner-ring {
    position: absolute;
    inset: 0;
    border: 3px solid #EEF2FF;
    border-top-color: #4F48E6;
    border-radius: 50%;
    animation: spin 1.2s cubic-bezier(0.5, 0.1, 0.4, 0.9) infinite;
    will-change: transform;
}

/* ── Bounded Logo Wrapper ────────────────────────────────────── */
.loader-logo-mark-wrap {
    width: 44px;
    height: 52px;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: breathe 2.4s ease-in-out infinite;
    will-change: transform;
}

.loader-logo-mark {
    width: 100%;
    height: 100%;
    display: block;
}

/* ── Subtle color shift on SVG paths ────────────────────────── */
.loader-path-color {
    animation: subtleColorShift 4.8s ease-in-out infinite;
    will-change: fill;
}

/* ================================================================
   KEYFRAMES
   ================================================================ */

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

/* Breathe animation scales the logo uniformly, avoiding stretching */
@keyframes breathe {
    0%, 100% {
        transform: scale(0.93);
    }
    50% {
        transform: scale(1.07);
    }
}

@keyframes subtleColorShift {
    0%, 100% { fill: #4F48E6; }
    50%       { fill: #4338CA; }
}

/* ── Loading label ──────────────────────────────────────────── */
.loader-label {
    margin-top: 36px;
    font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.24em;
    text-transform: uppercase;
    color: #9CA3AF;
    animation: labelPulse 1.8s ease-in-out infinite;
    will-change: opacity;
}

@keyframes labelPulse {
    0%, 100% { opacity: 0.4; }
    50%       { opacity: 0.9; }
}
</style>

<script>
(function () {
    const loader = document.getElementById('global-page-loader');
    if (!loader) return;

    /* Prevent scroll while loading */
    document.body.style.overflow = 'hidden';

    /* Minimum brand display: 1.5s — short, clean load */
    const MIN_MS = 1500;
    const start  = Date.now();

    function dismiss() {
        loader.style.opacity = '0';
        document.body.style.overflow = '';
        setTimeout(function () { loader.style.display = 'none'; }, 620);
    }

    function tryDismiss() {
        const elapsed   = Date.now() - start;
        const remaining = MIN_MS - elapsed;
        if (remaining <= 0) {
            dismiss();
        } else {
            setTimeout(dismiss, remaining);
        }
    }

    /* Dismiss as soon as all assets are ready */
    if (document.readyState === 'complete') {
        tryDismiss();
    } else {
        window.addEventListener('load', tryDismiss, { once: true });
        /* Safety hard cap — never hold longer than 4s */
        setTimeout(dismiss, 4000);
    }
})();
</script>
