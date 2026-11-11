<?php

declare(strict_types=1);

/**
 * Component: Landing Page - Explore by Category
 * Clean, left-aligned category navigation with pure white background,
 * typography matching the Our Mission H2, and no divider borders.
 */
?>
<style>
/* ─── Explore by Category Section ─── */
.categories-section-wrap {
    position: relative;
    background-color: #FFFFFF !important;
    padding: 32px 0 64px 0;
    width: 100%;
    box-sizing: border-box;
    border: none !important;
    border-top: none !important;
    border-bottom: none !important;
}

@media (min-width: 768px) {
    .categories-section-wrap {
        padding: 40px 0 80px 0;
    }
}

.categories-inner-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px;
    width: 100%;
    box-sizing: border-box;
}

.categories-content-layout {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    text-align: left;
    gap: 24px;
    width: 100%;
    box-sizing: border-box;
}

/* Typography explicitly matching the Our Mission H2 headline */
.categories-headline-title {
    font-size: clamp(28px, 3.5vw, 44px) !important;
    font-weight: 800 !important;
    line-height: 1.15 !important;
    color: #0F172A !important;
    letter-spacing: -0.025em !important;
    margin: 0 !important;
    text-align: left !important;
}

.categories-pills-row {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-start;
    gap: 12px;
    width: 100%;
}

.hero-category-pill {
    display: inline-flex !important;
    align-items: center !important;
    gap: 8px !important;
    padding: 10px 22px !important;
    border-radius: 9999px !important;
    border: 1px solid #E2E8F0 !important;
    background: #FFFFFF !important;
    color: #334155 !important;
    font-size: 14.5px !important;
    font-weight: 500 !important;
    text-decoration: none !important;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
    box-shadow: none !important;
}

.hero-category-pill:hover {
    border-color: #4F48E6 !important;
    color: #4F48E6 !important;
    background: #F5F3FF !important;
    transform: translateY(-1px);
}

.hero-category-pill-all {
    background: #000623 !important;
    border-color: #000623 !important;
    color: #FFFFFF !important;
    font-weight: 600 !important;
    box-shadow: none !important;
}

.hero-category-pill-all:hover {
    background: #4F48E6 !important;
    border-color: #4F48E6 !important;
    color: #FFFFFF !important;
}

.categories-arrow-icon {
    width: 15px;
    height: 15px;
    flex-shrink: 0;
    transition: transform 0.2s ease;
}

.hero-category-pill-all:hover .categories-arrow-icon {
    transform: translateX(2px);
}
</style>

<section id="categories-section" class="categories-section-wrap">
    <div class="categories-inner-container">
        <div class="categories-content-layout">
            <h2 class="categories-headline-title">
                Explore by Category
            </h2>
            
            <div class="categories-pills-row">
                <a href="<?= url('/search?search=UI%2FUX+Design') ?>" class="hero-category-pill">
                    UI/UX Design
                </a>
                <a href="<?= url('/search?search=Graphic+Design') ?>" class="hero-category-pill">
                    Graphic Design
                </a>
                <a href="<?= url('/search?search=Web+Development') ?>" class="hero-category-pill">
                    Web Development
                </a>
                <a href="<?= url('/search?search=Mobile+Development') ?>" class="hero-category-pill">
                    Mobile Development
                </a>
                <a href="<?= url('/search?search=Data+Science') ?>" class="hero-category-pill">
                    Data Science &amp; AI
                </a>
                <a href="<?= url('/internships') ?>" class="hero-category-pill hero-category-pill-all">
                    <span>View All Categories</span>
                    <svg class="categories-arrow-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>
