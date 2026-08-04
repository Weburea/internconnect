<?php
/**
 * Component: Global Page Loader
 *
 * Displays a premium animated loading screen.
 * Animation: InternConnect logo mark — top dot bounces on the fork crossbar,
 * fork body sways on impact, colors cycle purple → charcoal → purple.
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

    <!-- Animated Logo Mark -->
    <div class="loader-logo-wrap">

        <!-- The bouncing ball (top dot of the logo) -->
        <div class="loader-ball-wrap">
            <svg class="loader-ball" viewBox="0 0 28 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path class="loader-color-shape"
                      d="M13.9334 0.001C15.2436 0.014 16.5208 0.414 17.6039 1.152
                         C18.687 1.889 19.5275 2.931 20.0195 4.145
                         C20.5115 5.359 20.633 6.692 20.3685 7.976
                         C20.1041 9.259 19.4656 10.435 18.5336 11.356
                         C17.6016 12.277 16.4178 12.902 15.1314 13.151
                         C13.845 13.4 12.5136 13.263 11.3051 12.757
                         C10.0966 12.25 9.065 11.397 8.341 10.306
                         C7.616 9.214 7.231 7.932 7.234 6.622
                         C7.228 5.744 7.397 4.874 7.733 4.063
                         C8.068 3.251 8.563 2.515 9.187 1.898
                         C9.812 1.281 10.553 0.796 11.368 0.470
                         C12.184 0.144 13.056 -0.015 13.9334 0.001Z"/>
            </svg>
        </div>

        <!-- The fork body (the T/fork mark) -->
        <div class="loader-fork-wrap">
            <svg class="loader-fork" viewBox="0 0 28 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path class="loader-color-shape"
                      d="M27.7388 0.001V10.338
                         C25.5532 10.272 23.4266 11.053 21.8027 12.517
                         C20.1787 13.981 19.1829 16.016 19.0229 18.196
                         C19.004 18.373 18.989 18.572 18.9815 18.742H8.86632
                         C8.86632 18.644 8.83247 18.546 8.81366 18.452
                         C7.798 13.047 6.17295 11.579 0 10.229V0.072
                         C5.01811 -0.334 10.2017 1.953 13.8882 5.820
                         C17.3038 2.217 22.1903 -0.213 27.7388 0.001Z"/>
            </svg>
        </div>

    </div>

    <!-- Loading label -->
    <span class="loader-label">Loading</span>

</div>

<style>
/* ================================================================
   GLOBAL PAGE LOADER  —  Enlarged + Ultra-Smooth Physics
   ================================================================ */

.loader-logo-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    gap: 0;
}

/* ── Ball container ─────────────────────────────────────────── */
.loader-ball-wrap {
    width: 130px;
    height: 120px;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    animation: ballBounce 1.8s infinite;
    transform-origin: center bottom;
    will-change: transform;
}

.loader-ball {
    width: 84px;
    height: 84px;
    display: block;
}

/* ── Fork container ─────────────────────────────────────────── */
.loader-fork-wrap {
    width: 130px;
    height: 96px;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    animation: forkSway 3.6s ease-in-out infinite;
    transform-origin: center 6px;
    will-change: transform;
}

.loader-fork {
    width: 92px;
    height: 72px;
    display: block;
}

/* ── Color cycle on both paths ──────────────────────────────── */
.loader-color-shape {
    animation: colorCycle 3.6s ease-in-out infinite;
    will-change: fill;
}

/* ================================================================
   KEYFRAMES
   ================================================================ */

/*
 * ballBounce  — realistic gravity fall + spring rebound
 *
 *   0%   ball at peak height (−80px above fork)
 *  68%   ball lands: squash flat  scaleY(0.76) scaleX(1.28)
 *  80%   first rebound: stretch tall
 *  90%   small secondary squash
 * 100%   back at peak — seamless loop
 */
@keyframes ballBounce {
    0% {
        transform: translateY(-80px) scaleY(1) scaleX(1);
        animation-timing-function: cubic-bezier(0.55, 0, 1, 0.45);
    }
    68% {
        transform: translateY(5px) scaleY(0.74) scaleX(1.30);
        animation-timing-function: cubic-bezier(0, 0.6, 0.4, 1);
    }
    80% {
        transform: translateY(-22px) scaleY(1.10) scaleX(0.92);
        animation-timing-function: cubic-bezier(0.55, 0, 1, 0.45);
    }
    91% {
        transform: translateY(-6px) scaleY(0.96) scaleX(1.04);
        animation-timing-function: cubic-bezier(0, 0.6, 0.4, 1);
    }
    100% {
        transform: translateY(-80px) scaleY(1) scaleX(1);
    }
}

/*
 * forkSway  — pendulum absorption (3.6s = 2x bounce)
 *  Peaks timed so fork tilts just as ball strikes (68% of 1.8s = 1.224s ≈ 34%)
 */
@keyframes forkSway {
    0%   { transform: rotate(0deg);     }
    18%  { transform: rotate(-2.2deg);  }
    34%  { transform: rotate(1.8deg);   }
    50%  { transform: rotate(-1.1deg);  }
    66%  { transform: rotate(0.7deg);   }
    82%  { transform: rotate(-0.3deg);  }
    100% { transform: rotate(0deg);     }
}

/*
 * colorCycle  — silky purple → charcoal → purple
 *  Long hold periods make each state feel deliberate
 */
@keyframes colorCycle {
    0%   { fill: #4F48E6; }
    38%  { fill: #4F48E6; }
    52%  { fill: #1C1C1E; }
    88%  { fill: #1C1C1E; }
    100% { fill: #4F48E6; }
}

/* ── Loading label ──────────────────────────────────────────── */
.loader-label {
    margin-top: 40px;
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
    0%, 100% { opacity: 0.3; }
    50%       { opacity: 0.9; }
}
</style>

<script>
(function () {
    const loader = document.getElementById('global-page-loader');
    if (!loader) return;

    /* Prevent scroll while loading */
    document.body.style.overflow = 'hidden';

    /* Minimum brand display: 2.4s — long enough to see 1+ full animation cycles */
    const MIN_MS = 2400;
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
        /* Safety hard cap — never hold longer than 5s */
        setTimeout(dismiss, 5000);
    }
})();
</script>
