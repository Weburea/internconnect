<?php

declare(strict_types=1);

/**
 * View: Browse Internships Page
 * Route: /internships
 * Layout: app (Public navbar, footer, toast, Jenny AI chatbot)
 *
 * @var string $title
 * @var string $initialSearch
 * @var string $initialLocation
 * @var string $initialCategory
 */

$initialSearch   = $initialSearch ?? ($_GET['search'] ?? '');
$initialLocation = $initialLocation ?? ($_GET['location'] ?? '');
$initialCategory = $initialCategory ?? ($_GET['category'] ?? '');
?>

<style>
    /* ─── Standardized Dropdown Component Styles ─── */
    .db-dropdown-wrap {
        position: relative;
        width: 100%;
        box-sizing: border-box;
    }

    .db-dropdown-menu-floating {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        min-width: 260px;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        box-shadow: 0 16px 36px -8px rgba(0, 6, 35, 0.16);
        z-index: 100;
        overflow: hidden;
        max-height: 290px;
        display: flex;
        flex-direction: column;
    }

    .db-dropdown-menu-searchable {
        padding: 0 !important;
        overflow: hidden !important;
        max-height: 290px !important;
        display: flex;
        flex-direction: column;
    }

    .db-dropdown-search-box {
        padding: 8px 10px;
        border-bottom: 1px solid #F1F5F9;
        background: #FFFFFF;
        flex-shrink: 0;
    }

    .db-dropdown-search-input-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }

    .db-dropdown-search-input {
        width: 100%;
        height: 36px;
        padding: 6px 10px 6px 32px;
        border: 1px solid #CBD5E1;
        border-radius: 8px;
        font-size: 12.5px;
        background: #F8FAFC;
        outline: none;
        box-sizing: border-box;
        font-family: inherit;
        transition: all 0.15s ease;
    }

    .db-dropdown-search-input:focus {
        border-color: #4F48E6;
        background: #FFFFFF;
        box-shadow: 0 0 0 2px rgba(79, 72, 230, 0.12);
    }

    .db-dropdown-items-scroll {
        max-height: 220px;
        overflow-y: auto !important;
        padding: 6px;
        scrollbar-gutter: stable;
        flex: 1;
        scrollbar-width: thin;
        scrollbar-color: #CBD5E1 transparent;
    }

    .db-dropdown-items-scroll::-webkit-scrollbar {
        width: 5px;
    }
    .db-dropdown-items-scroll::-webkit-scrollbar-track {
        background: transparent;
        border-radius: 4px;
    }
    .db-dropdown-items-scroll::-webkit-scrollbar-thumb {
        background: #CBD5E1;
        border-radius: 4px;
    }
    .db-dropdown-items-scroll::-webkit-scrollbar-thumb:hover {
        background: #94A3B8;
    }

    .db-dropdown-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 9px 12px;
        border-radius: 8px;
        font-size: 13px;
        color: #334155;
        cursor: pointer;
        transition: all 0.15s ease;
        border: none;
        background: transparent;
        width: 100%;
        text-align: left;
        box-sizing: border-box;
        white-space: nowrap !important;
        gap: 12px;
    }

    .db-dropdown-item:hover {
        background: #EEF2FF;
        color: #4F48E6;
    }

    .db-dropdown-item.is-selected {
        background: #EEF2FF;
        color: #4F48E6;
        font-weight: 700;
    }

    .db-dropdown-item-header {
        font-size: 10.5px;
        font-weight: 700;
        color: #94A3B8;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 6px 10px 4px;
    }

    /* ─── Sidebar Filter Card Styles (Neutral Clean Borders, Zero Accent Strips) ─── */
    .int-filter-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        padding: 22px 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        box-sizing: border-box;
    }

    .int-filter-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 16px;
        border-bottom: 1px solid #F1F5F9;
        margin-bottom: 20px;
    }

    .int-filter-title {
        font-size: 16px;
        font-weight: 800;
        color: #0F172A;
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
    }

    .int-filter-reset {
        font-size: 12.5px;
        font-weight: 700;
        color: #4F48E6;
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 4px 6px;
        border-radius: 6px;
        transition: all 0.15s ease;
    }

    .int-filter-reset:hover {
        background: #EEF2FF;
    }

    .int-filter-section {
        margin-bottom: 22px;
        padding-bottom: 20px;
        border-bottom: 1px solid #F1F5F9;
    }

    .int-filter-section:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .int-filter-label {
        font-size: 13.5px;
        font-weight: 700;
        color: #0F172A;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Checkbox & Radio lists */
    .int-check-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .int-check-label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 13.5px;
        color: #334155;
        cursor: pointer;
        user-select: none;
        gap: 8px;
    }

    .int-check-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .int-checkbox {
        accent-color: #4F48E6;
        width: 17px;
        height: 17px;
        border-radius: 5px;
        cursor: pointer;
        flex-shrink: 0;
    }

    .int-count-pill {
        font-size: 11px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 99px;
        background: #F1F5F9;
        color: #64748B;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    /* Stipend Range Slider (Naira ₦ Standard) */
    .int-slider-wrap {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .int-range-slider {
        width: 100%;
        accent-color: #4F48E6;
        height: 6px;
        border-radius: 4px;
        background: #E2E8F0;
        cursor: pointer;
    }

    .int-slider-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .int-slider-display {
        font-size: 13px;
        font-weight: 700;
        color: #0F172A;
    }

    .int-btn-apply-slider {
        background: #4F48E6;
        color: #FFFFFF;
        border: none;
        padding: 5px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.15s ease;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .int-btn-apply-slider:hover {
        background: #4338CA;
    }

    /* Tags Pill Cloud */
    .int-tags-cloud {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
    }

    .int-tag-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 99px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        user-select: none;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        color: #334155;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .int-tag-pill:hover {
        background: #EEF2FF;
        border-color: #C7D2FE;
        color: #4F48E6;
    }

    .int-tag-pill.is-active {
        background: #4F48E6;
        border-color: #4F48E6;
        color: #FFFFFF;
    }

    /* ─── Jenny AI Promo Card (Official Design System Standard) ─── */
    .int-jenny-promo-card {
        background: #000623;
        border: 1px solid #1E293B;
        border-radius: 18px;
        padding: 22px;
        color: #FFFFFF;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0, 6, 35, 0.1);
        box-sizing: border-box;
    }

    .int-jenny-promo-card::before {
        content: '';
        position: absolute;
        top: -30px;
        right: -30px;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(79, 72, 230, 0.35) 0%, transparent 70%);
        pointer-events: none;
    }

    .int-jenny-promo-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .int-jenny-avatar-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.12);
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .int-jenny-avatar-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .int-jenny-headline {
        font-size: 15px;
        font-weight: 800;
        color: #FFFFFF;
        margin: 0 0 2px;
        line-height: 1.3;
    }

    .int-jenny-badge {
        font-size: 10.5px;
        font-weight: 700;
        color: #818CF8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .int-jenny-subtext {
        font-size: 12.5px;
        color: #CBD5E1;
        line-height: 1.5;
        margin: 0 0 16px;
    }

    .int-jenny-cta-btn {
        width: 100%;
        background: #4F48E6;
        color: #FFFFFF;
        border: none;
        padding: 10px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.15s ease;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
        box-shadow: 0 4px 14px rgba(79, 72, 230, 0.35);
    }

    .int-jenny-cta-btn:hover {
        background: #4338CA;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(79, 72, 230, 0.45);
    }

    /* ─── Circular Pagination Design (Standardized) ─── */
    .int-pagination-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 32px;
        flex-wrap: wrap;
    }

    .int-page-btn {
        width: 34px;
        height: 34px;
        min-width: 34px;
        padding: 0;
        border-radius: 9999px !important;
        background: #FFFFFF;
        border: 1px solid #CBD5E1;
        color: #334155;
        font-size: 12.5px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .int-page-btn:hover {
        border-color: #4F48E6;
        color: #4F48E6;
        background: #F8FAFC;
    }

    .int-page-btn.is-active {
        background: #4F48E6;
        border-color: #4F48E6;
        color: #FFFFFF;
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(79, 72, 230, 0.25);
    }

    .int-page-nav-text-btn {
        height: 34px;
        padding: 0 14px;
        border-radius: 9999px;
        background: transparent;
        border: none;
        color: #64748B;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .int-page-nav-text-btn:hover {
        color: #4F48E6;
    }

    .int-page-nav-text-btn:disabled,
    .int-page-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    /* ─── Mobile Filter Bar & Collapsible Sidebar Styles ─── */
    .int-mobile-filter-bar {
        display: none;
        align-items: center;
        justify-content: space-between;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        padding: 12px 18px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0, 6, 35, 0.04);
        box-sizing: border-box;
    }

    .int-mobile-filter-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #4F48E6;
        color: #FFFFFF;
        border: none;
        padding: 9px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
        box-shadow: 0 2px 8px rgba(79, 72, 230, 0.25);
        transition: all 0.2s ease;
    }

    @keyframes filterBtnPulse {
        0%, 100% {
            box-shadow: 0 2px 8px rgba(79, 72, 230, 0.25);
            transform: scale(1);
        }
        50% {
            box-shadow: 0 4px 16px rgba(79, 72, 230, 0.45);
            transform: scale(1.02);
        }
    }

    @keyframes filterIconWiggle {
        0%, 100% { transform: rotate(0deg); }
        20% { transform: rotate(-8deg); }
        40% { transform: rotate(8deg); }
        60% { transform: rotate(-4deg); }
        80% { transform: rotate(4deg); }
    }

    @media (max-width: 1024px) {
        .int-mobile-filter-bar {
            display: flex;
        }

        .int-mobile-filter-btn {
            animation: filterBtnPulse 2.8s infinite ease-in-out;
        }

        .int-mobile-filter-btn svg {
            animation: filterIconWiggle 2.8s infinite ease-in-out;
        }

        .int-sidebar-col {
            display: none !important;
            margin-bottom: 24px;
        }

        .int-sidebar-col.is-mobile-open {
            display: block !important;
        }
    }
</style>

<div class="bg-gray-50 min-h-screen py-10" x-data="{
    search: '<?= htmlspecialchars($initialSearch, ENT_QUOTES, 'UTF-8') ?>',
    location: '<?= htmlspecialchars($initialLocation, ENT_QUOTES, 'UTF-8') ?>',
    category: '<?= htmlspecialchars($initialCategory, ENT_QUOTES, 'UTF-8') ?>',
    catSearch: '',
    selectedTypes: [],
    selectedDatePosted: 'all',
    maxStipendSlider: 500000,
    appliedMaxStipend: 500000,
    // Jenny search assist (POST /jenny/search; Pro 10 a day, Premium unlimited, docs/55-jenny-ai-chat.md)
    appliedMinStipend: 0,
    jennyQuery: '',
    jennyBusy: false,
    jennySummary: '',
    jennyError: '',
    jennyUpgrade: '',
    jennyLeft: null,
    selectedTags: [],
    mobileFiltersOpen: false,
    currentPage: 1,
    perPage: 6,
    alertEnabled: false,
    savedStates: {
        'Nexura': false,
        'Airbnb': true,
        'WellSpring Care': false,
        'BrightWave': false,
        'Paystack': false,
        'Flutterwave': false
    },

    locationList: [
        { label: 'All Locations', value: '', country: '' },
        { label: 'Lagos, Nigeria', value: 'Lagos, NG', country: 'Nigeria' },
        { label: 'Abuja, Nigeria', value: 'Abuja, NG', country: 'Nigeria' },
        { label: 'Port Harcourt, Nigeria', value: 'Port Harcourt, NG', country: 'Nigeria' },
        { label: 'Ibadan, Nigeria', value: 'Ibadan, NG', country: 'Nigeria' },
        { label: 'Enugu, Nigeria', value: 'Enugu, NG', country: 'Nigeria' },
        { label: 'London, United Kingdom', value: 'London, UK', country: 'United Kingdom' },
        { label: 'New York, United States', value: 'New York, USA', country: 'United States' },
        { label: 'San Francisco, United States', value: 'San Francisco, USA', country: 'United States' },
        { label: 'Toronto, Canada', value: 'Toronto, Canada', country: 'Canada' },
        { label: 'Berlin, Germany', value: 'Berlin, Germany', country: 'Germany' },
        { label: 'Accra, Ghana', value: 'Accra, Ghana', country: 'Ghana' },
        { label: 'Nairobi, Kenya', value: 'Nairobi, Kenya', country: 'Kenya' },
        { label: 'Johannesburg, South Africa', value: 'Johannesburg, SA', country: 'South Africa' },
        { label: 'Remote (Worldwide)', value: 'Remote', country: 'Remote' }
    ],

    categoriesList: [
        { id: '', name: 'All Categories' },
        { id: 'ENGINEERING', name: 'Tech/Engineering' },
        { id: 'DESIGN', name: 'Design & Creative' },
        { id: 'FINANCE', name: 'Financial Services' },
        { id: 'MARKETING', name: 'Marketing & Growth' },
        { id: 'DATA', name: 'Data Science & AI' },
        { id: 'PRODUCT', name: 'Product & Project' },
        { id: 'OPERATIONS', name: 'Operations & Strategy' }
    ],

    internshipTypesList: [
        { id: 'Full-Time', name: 'Full-Time' },
        { id: 'Part-Time', name: 'Part-Time' },
        { id: 'Remote', name: 'Remote' },
        { id: 'Hybrid', name: 'Hybrid' },
        { id: 'On-site', name: 'On-site' }
    ],

    datePostedOptions: [
        { id: 'all', name: 'All' },
        { id: 'hour', name: 'Last Hour' },
        { id: '24h', name: 'Last 24 Hours' },
        { id: '7d', name: 'Last 7 Days' },
        { id: '30d', name: 'Last 30 Days' }
    ],

    availableTags: [
        'Engineering', 'Design', 'UI/UX', 'Marketing', 'Management',
        'Python', 'React', 'Figma', 'Fintech', 'Data Analysis', 'Soft Skills'
    ],

    // premium/locked come from PlanService (admin sets the Premium rule in Plans & limits).
    internships: <?= htmlspecialchars(json_encode(\App\Services\PlanService::annotateListings(require dirname(__DIR__, 2) . '/backend/Data/internships.php'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>,

    get filteredCategoriesList() {
        if (!this.catSearch || !this.catSearch.trim()) {
            return this.categoriesList;
        }
        const q = this.catSearch.toLowerCase().trim();
        return this.categoriesList.filter(c => c.name.toLowerCase().includes(q));
    },

    async jennySearch() {
        const q = this.jennyQuery.trim();
        if (!q || this.jennyBusy) return;
        this.jennyBusy = true; this.jennyError = ''; this.jennyUpgrade = '';
        try {
            const r = await fetch('<?= url('/jenny/search') ?>', { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify({ q: q }) });
            const d = await r.json().catch(() => ({}));
            if (!d.success) {
                this.jennyError = d.error || 'Jenny could not search just now. Please try again.';
                this.jennyUpgrade = d.upgrade ? (d.upgrade_url || '<?= url('/pricing') ?>') : '';
            } else {
                const f = d.filters;
                this.search = f.keywords || '';
                this.location = f.location || '';
                this.category = f.category || '';
                const modes = { 'REMOTE': 'Remote', 'HYBRID': 'Hybrid', 'ON SITE': 'On-site' };
                this.selectedTypes = f.work_mode && modes[f.work_mode] ? [modes[f.work_mode]] : [];
                this.appliedMinStipend = f.min_stipend || 0;
                this.selectedTags = [];
                this.currentPage = 1;
                this.jennySummary = f.summary || 'Filters set by Jenny.';
                this.jennyLeft = d.usage && d.usage.remaining !== null ? d.usage.remaining : null;
            }
        } catch (e) {
            this.jennyError = 'Network error. Please try again.';
        }
        this.jennyBusy = false;
    },
    // Near me (docs/56-location-and-visits.md): browser location if allowed, else the city from the IP address.
    nearMeBusy: false,
    async nearMe() {
        if (this.nearMeBusy) return;
        this.nearMeBusy = true;
        const toast = (t, type) => window.dispatchEvent(new CustomEvent('show-toast', { detail: { text: t, message: t, type: type } }));
        const cities = [['Lagos', 6.5244, 3.3792], ['Abuja', 9.0765, 7.3986], ['Port Harcourt', 4.8156, 7.0498], ['Ibadan', 7.3775, 3.947], ['Enugu', 6.4584, 7.5464], ['Kano', 12.0022, 8.592], ['Benin City', 6.335, 5.6037], ['Kaduna', 10.5105, 7.4165], ['Accra', 5.6037, -0.187], ['Nairobi', -1.2921, 36.8219]];
        const gps = () => new Promise((resolve) => {
            if (!navigator.geolocation) return resolve(null);
            navigator.geolocation.getCurrentPosition((p) => resolve(p.coords), () => resolve(null), { timeout: 6000, maximumAge: 600000 });
        });
        let city = null;
        const c = await gps();
        if (c) {
            let best = null, bestD = Infinity;
            cities.forEach(([name, lat, lng]) => { const d = Math.pow(lat - c.latitude, 2) + Math.pow(lng - c.longitude, 2); if (d < bestD) { bestD = d; best = name; } });
            city = best;
        }
        if (!city) {
            try { const r = await fetch('<?= url('/geo/me') ?>', { headers: { 'Accept': 'application/json' } }); const d = await r.json(); if (d.success) city = d.city; } catch (e) {}
        }
        if (city) { this.location = city; this.currentPage = 1; toast('Showing internships near ' + city + '.', 'success'); }
        else { toast('We could not find your location. Choose a city instead.', 'error'); }
        this.nearMeBusy = false;
    },
    clearJenny() {
        this.jennySummary = ''; this.jennyQuery = ''; this.appliedMinStipend = 0;
        this.search = ''; this.location = ''; this.category = ''; this.selectedTypes = []; this.currentPage = 1;
    },

    get filteredInternships() {
        return this.internships.filter(item => {
            // Search keyword
            if (this.search && this.search.trim()) {
                const kw = this.search.toLowerCase().trim();
                const matchTitle = item.title.toLowerCase().includes(kw);
                const matchCompany = item.company.toLowerCase().includes(kw);
                const matchDesc = item.description.toLowerCase().includes(kw);
                if (!matchTitle && !matchCompany && !matchDesc) return false;
            }

            // Location / Country
            if (this.location && this.location.trim()) {
                const loc = this.location.toLowerCase().trim();
                const matchLoc = item.location.toLowerCase().includes(loc);
                const matchCountry = item.country && item.country.toLowerCase().includes(loc);
                if (!matchLoc && !matchCountry) return false;
            }

            // Category
            if (this.category && this.category !== '') {
                if (item.category.toUpperCase() !== this.category.toUpperCase()) return false;
            }

            // Internship Types
            if (this.selectedTypes.length > 0) {
                const matchesType = this.selectedTypes.some(typeId => {
                    if (typeId === 'Full-Time') return item.commitment.toLowerCase() === 'full-time';
                    if (typeId === 'Part-Time') return item.commitment.toLowerCase() === 'part-time';
                    if (typeId === 'Remote') return item.tag === 'REMOTE';
                    if (typeId === 'Hybrid') return item.tag === 'HYBRID';
                    if (typeId === 'On-site') return item.tag === 'ON SITE';
                    return false;
                });
                if (!matchesType) return false;
            }

            // Date Posted
            if (this.selectedDatePosted && this.selectedDatePosted !== 'all') {
                if (this.selectedDatePosted === 'hour' && item.hoursAgo > 1) return false;
                if (this.selectedDatePosted === '24h' && item.hoursAgo > 24) return false;
                if (this.selectedDatePosted === '7d' && item.hoursAgo > 168) return false;
                if (this.selectedDatePosted === '30d' && item.hoursAgo > 720) return false;
            }

            // Monthly Stipend
            if (this.appliedMaxStipend && item.stipendAmount > this.appliedMaxStipend) {
                return false;
            }
            if (this.appliedMinStipend && !item.locked && item.stipendAmount < this.appliedMinStipend) {
                return false;
            }

            // Popular Skills & Tags
            if (this.selectedTags.length > 0) {
                const matchesTag = this.selectedTags.some(tag => item.tags && item.tags.includes(tag));
                if (!matchesTag) return false;
            }

            return true;
        });
    },

    get paginatedInternships() {
        const start = (this.currentPage - 1) * this.perPage;
        return this.filteredInternships.slice(start, start + this.perPage);
    },

    get totalPages() {
        return Math.ceil(this.filteredInternships.length / this.perPage) || 1;
    },

    getCategoryLabel() {
        const found = this.categoriesList.find(c => c.id === this.category);
        return found && found.id ? found.name : 'Choose category';
    },

    getCategoryCount(catId) {
        if (!catId) return this.internships.length;
        return this.internships.filter(item => item.category.toUpperCase() === catId.toUpperCase()).length;
    },

    getTypeCount(typeId) {
        return this.internships.filter(item => {
            if (typeId === 'Full-Time') return item.commitment.toLowerCase() === 'full-time';
            if (typeId === 'Part-Time') return item.commitment.toLowerCase() === 'part-time';
            if (typeId === 'Remote') return item.tag === 'REMOTE';
            if (typeId === 'Hybrid') return item.tag === 'HYBRID';
            if (typeId === 'On-site') return item.tag === 'ON SITE';
            return false;
        }).length;
    },

    getDateCount(dpId) {
        if (dpId === 'all') return this.internships.length;
        if (dpId === 'hour') return this.internships.filter(item => item.hoursAgo <= 1).length;
        if (dpId === '24h') return this.internships.filter(item => item.hoursAgo <= 24).length;
        if (dpId === '7d') return this.internships.filter(item => item.hoursAgo <= 168).length;
        if (dpId === '30d') return this.internships.filter(item => item.hoursAgo <= 720).length;
        return 0;
    },

    toggleTag(tag) {
        if (this.selectedTags.includes(tag)) {
            this.selectedTags = this.selectedTags.filter(t => t !== tag);
        } else {
            this.selectedTags.push(tag);
        }
        this.currentPage = 1;
    },

    resetFilters() {
        this.search = '';
        this.location = '';
        this.category = '';
        this.catSearch = '';
        this.selectedTypes = [];
        this.selectedDatePosted = 'all';
        this.maxStipendSlider = 500000;
        this.appliedMaxStipend = 500000;
        this.selectedTags = [];
        this.currentPage = 1;
        $dispatch('show-toast', { text: 'All filters reset.', type: 'info' });
    },

    performSearch() {
        this.currentPage = 1;
        const el = document.getElementById('browse-results-grid');
        if (el) {
            el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Page Title -->
        <div class="mb-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight">Find Your Next Internship</h1>
        </div>

        <!-- Search Bar Section (Keyword, Location Search, Category Dropdown with Inner Search, Search Action) -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm mb-10 p-4 relative z-20" style="overflow: visible;">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                
                <!-- 1. Keyword search (5 cols) -->
                <div class="md:col-span-5 relative flex items-center border border-gray-200 rounded-xl bg-gray-50 focus-within:bg-white focus-within:border-primary-hover focus-within:ring-2 focus-within:ring-primary-light transition duration-150" style="height: 48px;">
                    <span class="absolute left-4 text-gray-400 pointer-events-none flex items-center justify-center">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" 
                           x-model="search" 
                           @keydown.enter="performSearch()"
                           placeholder="Search roles, companies, or keywords..." 
                           class="w-full pl-11 pr-4 py-3 rounded-xl text-sm border-0 focus:ring-0 focus:outline-none bg-transparent placeholder-gray-400 text-gray-800 font-medium" />
                </div>

                <!-- 2. Location Search with Country/City Search Dropdown (3 cols) -->
                <div class="md:col-span-3 db-dropdown-wrap" x-data="{ open: false }" @click.outside="open = false">
                    <div class="relative flex items-center border border-gray-200 rounded-xl bg-gray-50 focus-within:bg-white focus-within:border-primary-hover focus-within:ring-2 focus-within:ring-primary-light transition duration-150" style="height: 48px; position: relative;">
                        <span class="pointer-events-none text-gray-400 flex items-center justify-center" style="position: absolute; left: 14px; width: 20px; height: 20px;">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </span>
                        <input type="text" 
                               x-model="location" 
                               @focus="open = true" 
                               @input="open = true" 
                               @keydown.enter="open = false; performSearch()" 
                               placeholder="Location" 
                               style="height: 48px; padding-left: 44px; padding-right: 36px;"
                               class="w-full rounded-xl text-sm border-0 focus:ring-0 focus:outline-none bg-transparent placeholder-gray-400 text-gray-800 font-medium" />
                        
                        <span @click="open = !open" 
                              style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); cursor: pointer; display: flex; align-items: center;" 
                              class="text-gray-400">
                            <svg class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
                            </svg>
                        </span>
                    </div>

                    <!-- Dropdown List of Countries and Locations -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100" 
                         x-transition:enter-start="transform opacity-0 scale-95" 
                         x-transition:enter-end="transform opacity-100 scale-100" 
                         x-transition:leave="transition ease-in duration-75" 
                         x-transition:leave-start="transform opacity-100 scale-100" 
                         x-transition:leave-end="transform opacity-0 scale-95" 
                         class="db-dropdown-menu-floating" 
                         style="display: none;">
                        <div class="db-dropdown-items-scroll">
                            <div class="db-dropdown-item-header">Select Location / Country</div>
                            <button type="button" class="db-dropdown-item" @click="open = false; nearMe()" style="color: #4F48E6; font-weight: 700;">
                                <span style="display: inline-flex; align-items: center; gap: 8px;">
                                    <svg width="14" height="14" style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2v3m0 14v3M2 12h3m14 0h3"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                                    <span x-text="nearMeBusy ? 'Finding you...' : 'Near me'"></span>
                                </span>
                            </button>
                            <template x-for="loc in locationList.filter(l => !location || l.label.toLowerCase().includes(location.toLowerCase()) || l.country.toLowerCase().includes(location.toLowerCase()))" :key="loc.label">
                                <button type="button" 
                                        class="db-dropdown-item" 
                                        :class="{ 'is-selected': location === loc.value || (loc.value === '' && !location) }"
                                        @click="location = loc.value; open = false; performSearch();">
                                    <span x-text="loc.label"></span>
                                    <svg x-show="location === loc.value || (loc.value === '' && !location)" style="width: 14px; height: 14px; color: #4F48E6; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- 3. Category Dropdown with Inner Search Box (2 cols) -->
                <div class="md:col-span-2 db-dropdown-wrap" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open; if(open) { $nextTick(() => { const el = $refs.catSearchInput; if(el) el.focus(); }); }" type="button" 
                        style="height: 48px;"
                        class="w-full flex items-center justify-between pl-11 pr-4 py-3 rounded-xl text-sm font-medium bg-gray-50 border border-gray-200 hover:bg-gray-100/50 focus:outline-none focus:border-primary-hover focus:ring-2 focus:ring-primary-light transition duration-150 relative text-left"
                        :class="{ 'border-primary ring-2 ring-primary-light bg-white': open }">
                        <span class="absolute left-4 text-gray-400 pointer-events-none flex items-center justify-center">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </span>
                        <span class="truncate" 
                              :class="category ? 'text-gray-900 font-semibold' : 'text-gray-600'" 
                              x-text="getCategoryLabel()">Choose category</span>
                        <svg class="h-4 w-4 text-gray-400 transition-transform duration-200 flex-shrink-0" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
                        </svg>
                    </button>

                    <!-- Dropdown List of Categories with Inner Search Box -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100" 
                         x-transition:enter-start="transform opacity-0 scale-95" 
                         x-transition:enter-end="transform opacity-100 scale-100" 
                         x-transition:leave="transition ease-in duration-75" 
                         x-transition:leave-start="transform opacity-100 scale-100" 
                         x-transition:leave-end="transform opacity-0 scale-95" 
                         class="db-dropdown-menu-floating db-dropdown-menu-searchable" 
                         style="display: none; min-width: 280px;">
                        
                        <!-- Inner Search Box -->
                        <div class="db-dropdown-search-box">
                            <div class="db-dropdown-search-input-wrap">
                                <svg style="width: 14px; height: 14px; color: #94A3B8; position: absolute; left: 10px; pointer-events: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input type="text" 
                                       x-ref="catSearchInput"
                                       x-model="catSearch" 
                                       @click.stop 
                                       placeholder="Search categories..." 
                                       class="db-dropdown-search-input">
                                <button type="button" 
                                        x-show="catSearch" 
                                        @click.stop="catSearch = ''" 
                                        style="position: absolute; right: 8px; background: transparent; border: none; color: #94A3B8; cursor: pointer; padding: 2px;"
                                        aria-label="Clear category search"
                                        x-cloak>
                                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Scrollable Categories List with Count Badges -->
                        <div class="db-dropdown-items-scroll">
                            <template x-for="c in filteredCategoriesList" :key="c.id">
                                <button type="button" 
                                        class="db-dropdown-item" 
                                        :class="{ 'is-selected': category === c.id }"
                                        @click="category = (category === c.id && c.id !== '' ? '' : c.id); open = false; performSearch();">
                                    <div style="display: flex; align-items: center; gap: 8px; min-width: 0;">
                                        <span x-text="c.name" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"></span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 6px; flex-shrink: 0;">
                                        <span class="int-count-pill" x-text="getCategoryCount(c.id)"></span>
                                        <svg x-show="category === c.id" style="width: 14px; height: 14px; color: #4F48E6; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </button>
                            </template>

                            <div x-show="filteredCategoriesList.length === 0" 
                                 style="padding: 16px 12px; text-align: center; color: #94A3B8; font-size: 12.5px;">
                                No matching categories found
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Search Action Button (2 cols) -->
                <div class="md:col-span-2">
                    <button type="button" 
                            @click="performSearch()"
                            style="height: 48px; white-space: nowrap !important; flex-shrink: 0 !important;"
                            class="w-full bg-primary hover:bg-primary-hover text-white text-sm font-semibold rounded-xl transition shadow-sm inline-flex items-center justify-center">
                        <span>Search</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Filter Toggle Bar (Visible on mobile/tablets) -->
        <div class="int-mobile-filter-bar">
            <span style="font-size: 13.5px; font-weight: 700; color: #0F172A;" x-text="filteredInternships.length + ' Opportunities Found'"></span>
            <button type="button" @click="mobileFiltersOpen = !mobileFiltersOpen" class="int-mobile-filter-btn">
                <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                <span x-text="mobileFiltersOpen ? 'Hide Filters' : 'Filter Results'">Filter Results</span>
            </button>
        </div>

        <!-- Two Column Content Area -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8" id="browse-results-grid">
            
            <!-- Left Sidebar Filter Column (Strict User-Requested Order) -->
            <aside class="lg:col-span-3 space-y-6 int-sidebar-col" :class="{ 'is-mobile-open': mobileFiltersOpen }">
                
                <!-- Main Filter Card -->
                <div class="int-filter-card">
                    
                    <!-- Filter Header -->
                    <div class="int-filter-header">
                        <h3 class="int-filter-title">
                            <svg style="width: 18px; height: 18px; color: #4F48E6;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            <span>Filter by</span>
                        </h3>
                        <button type="button" class="int-filter-reset" @click="resetFilters()">Reset All</button>
                    </div>

                    <!-- 1. Internship Type Checklist -->
                    <div class="int-filter-section">
                        <label class="int-filter-label">Internship Type</label>
                        <div class="int-check-list">
                            <template x-for="type in internshipTypesList" :key="type.id">
                                <label class="int-check-label">
                                    <div class="int-check-left">
                                        <input type="checkbox" 
                                               class="int-checkbox" 
                                               :value="type.id" 
                                               x-model="selectedTypes">
                                        <span x-text="type.name"></span>
                                    </div>
                                    <span class="int-count-pill" x-text="getTypeCount(type.id)"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <!-- 2. Date Posted Checklist -->
                    <div class="int-filter-section">
                        <label class="int-filter-label">Date Posted</label>
                        <div class="int-check-list">
                            <template x-for="dp in datePostedOptions" :key="dp.id">
                                <label class="int-check-label">
                                    <div class="int-check-left">
                                        <input type="radio" 
                                               name="date_posted_internships" 
                                               class="int-checkbox" 
                                               :value="dp.id" 
                                               x-model="selectedDatePosted">
                                        <span x-text="dp.name"></span>
                                    </div>
                                    <span class="int-count-pill" x-text="getDateCount(dp.id)"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <!-- 3. Monthly Stipend Slider -->
                    <div class="int-filter-section">
                        <label class="int-filter-label">Monthly Stipend</label>
                        <div class="int-slider-wrap">
                            <input type="range" 
                                   class="int-range-slider" 
                                   min="0" 
                                   max="500000" 
                                   step="25000" 
                                   x-model="maxStipendSlider">
                            
                            <div class="int-slider-actions">
                                <span class="int-slider-display" x-text="'₦0 - ₦' + Number(maxStipendSlider).toLocaleString() + '/mo'"></span>
                                <button type="button" 
                                        class="int-btn-apply-slider" 
                                        @click="appliedMaxStipend = Number(maxStipendSlider); $dispatch('show-toast', { text: 'Stipend filter applied.', type: 'info' })">
                                    Apply
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Popular Skills & Tags -->
                    <div class="int-filter-section" style="border-bottom: none; margin-bottom: 0; padding-bottom: 0;">
                        <label class="int-filter-label">Popular Skills & Tags</label>
                        <div class="int-tags-cloud">
                            <template x-for="tag in availableTags" :key="tag">
                                <button type="button" 
                                        class="int-tag-pill" 
                                        :class="{ 'is-active': selectedTags.includes(tag) }" 
                                        @click="toggleTag(tag)">
                                    <span x-text="tag"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                </div>

                <!-- 5. Get New Alerts Block (SECOND TO LAST) -->
                <div class="bg-primary-light border border-primary-light rounded-2xl p-6 shadow-sm">
                    <h4 class="text-sm font-bold text-gray-900 mb-2">Get New Alerts</h4>
                    <p class="text-xs text-gray-500 leading-relaxed mb-4">
                        Receive notifications for new internships matching your profile.
                    </p>
                    <button type="button" 
                        @click="alertEnabled = !alertEnabled; $dispatch('show-toast', { text: alertEnabled ? 'Alerts subscription activated!' : 'Alerts subscription disabled.', type: 'info' })" 
                        class="w-full text-center text-xs font-semibold py-2.5 px-4 rounded-xl border border-primary text-primary bg-white hover:bg-primary-light transition active:scale-95 shadow-sm inline-flex items-center justify-center gap-1.5"
                        style="white-space: nowrap !important; flex-shrink: 0 !important;">
                        <span x-text="alertEnabled ? 'Alerts Enabled' : 'Enable Alerts'"></span>
                    </button>
                </div>

                <!-- 6. AI Internship Matcher Card (LAST ELEMENT) -->
                <div class="int-jenny-promo-card">
                    <div class="int-jenny-promo-header">
                        <div class="int-jenny-avatar-box">
                            <img src="<?= asset('assets/images/jenny_ai/jenny_ai_hi.png') ?>" alt="Jenny AI Mascot">
                        </div>
                        <div>
                            <h4 class="int-jenny-headline">Can't find the right fit?</h4>
                            <span class="int-jenny-badge">AI Internship Matcher</span>
                        </div>
                    </div>
                    <p class="int-jenny-subtext">
                        Jenny can match you with internships based on your skills and goals, no scrolling required.
                    </p>
                    <button type="button" 
                            class="int-jenny-cta-btn" 
                            @click="$dispatch('open-jenny-ai')">
                        <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span>Chat with Jenny</span>
                    </button>
                </div>

            </aside>

            <!-- Right Grid List Column -->
            <div class="lg:col-span-9 space-y-8">

                <!-- Jenny search assist: describe the internship in your own words (Pro and Premium) -->
                <style>
                    .ijs { position: relative; border-radius: 20px; padding: 18px; background: linear-gradient(135deg, #F5F3FF 0%, #EEF2FF 45%, #FFFFFF 100%); border: 1px solid #E0E7FF; box-shadow: 0 10px 30px -18px rgba(79, 72, 230, .45); overflow: hidden; }
                    .ijs::after { content: ''; position: absolute; right: -60px; top: -60px; width: 180px; height: 180px; border-radius: 50%; background: radial-gradient(circle, rgba(79, 72, 230, .14), transparent 70%); pointer-events: none; }
                    .ijs-head { position: relative; display: flex; align-items: center; gap: 10px; margin-bottom: 12px; flex-wrap: wrap; }
                    .ijs-avatar { width: 36px; height: 36px; border-radius: 50%; background: #FFFFFF; box-shadow: 0 0 0 3px #E0E7FF; flex-shrink: 0; }
                    .ijs-title { font-size: 15px; font-weight: 800; color: #000623; line-height: 1.2; }
                    .ijs-sub { font-size: 12.5px; color: #64748B; margin-top: 2px; }
                    .ijs-badge { font-size: 10px; font-weight: 800; letter-spacing: .06em; color: #FFFFFF; background: #4F48E6; padding: 3px 9px; border-radius: 999px; white-space: nowrap; }
                    .ijs-left { margin-left: auto; font-size: 11.5px; font-weight: 700; color: #4F48E6; background: #FFFFFF; border: 1px solid #E0E7FF; padding: 3px 10px; border-radius: 999px; white-space: nowrap; }
                    .ijs-row { position: relative; display: flex; align-items: center; gap: 8px; background: #FFFFFF; border: 1.5px solid #C7D2FE; border-radius: 16px; padding: 6px 6px 6px 14px; transition: border-color .15s ease, box-shadow .15s ease; }
                    .ijs-row:focus-within { border-color: #4F48E6; box-shadow: 0 0 0 4px rgba(79, 72, 230, .12); }
                    .ijs-row svg.ijs-spark { width: 18px; height: 18px; color: #4F48E6; flex-shrink: 0; }
                    .ijs-input { flex: 1; min-width: 0; border: 0; outline: none; background: transparent; font-size: 14.5px; color: #0F172A; font-family: inherit; height: 40px; }
                    .ijs-input::placeholder { color: #94A3B8; }
                    .ijs-btn { display: inline-flex; align-items: center; gap: 7px; height: 42px; padding: 0 18px; border: 0; border-radius: 12px; background: #4F48E6; color: #FFFFFF; font-size: 14px; font-weight: 700; cursor: pointer; white-space: nowrap; font-family: inherit; flex-shrink: 0; transition: background .15s ease; }
                    .ijs-btn:hover { background: #4338CA; }
                    .ijs-btn[disabled] { opacity: .65; cursor: progress; }
                    .ijs-btn svg { width: 16px; height: 16px; }
                    .ijs-chips { position: relative; display: flex; gap: 8px; flex-wrap: wrap; margin-top: 12px; }
                    .ijs-chip { border: 1px solid #E0E7FF; background: #FFFFFF; color: #3730A3; font-size: 12.5px; font-weight: 600; padding: 6px 12px; border-radius: 999px; cursor: pointer; font-family: inherit; }
                    .ijs-chip:hover { border-color: #4F48E6; color: #4F48E6; }
                    .ijs-note { position: relative; margin-top: 12px; display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; font-size: 13px; border-radius: 12px; padding: 9px 12px; }
                    .ijs-note.ok { background: #FFFFFF; color: #312E81; border: 1px solid #E0E7FF; }
                    .ijs-note.warn { background: #FFFBEB; color: #92400E; border: 1px solid #FDE68A; }
                    .ijs-note a, .ijs-note button { color: #4F48E6; font-weight: 700; text-decoration: none; background: none; border: 0; cursor: pointer; font-size: 13px; font-family: inherit; padding: 0; }
                    .ijs-spin { width: 16px; height: 16px; border: 2px solid rgba(255,255,255,.4); border-top-color: #FFFFFF; border-radius: 50%; animation: ijsSpin .8s linear infinite; }
                    @keyframes ijsSpin { to { transform: rotate(360deg); } }
                    @media (max-width: 520px) { .ijs-row { flex-wrap: wrap; padding: 8px; } .ijs-input { flex-basis: calc(100% - 30px); } .ijs-btn { width: 100%; justify-content: center; } .ijs-left { margin-left: 0; } }
                    @media (prefers-reduced-motion: reduce) { .ijs-spin { animation: none; } }
                </style>
                <form class="ijs ic-jenny-search" @submit.prevent="jennySearch()" aria-label="Search with Jenny">
                    <div class="ijs-head">
                        <img class="ijs-avatar" src="<?= asset('assets/images/jenny_ai/jenny_icon.png') ?>" alt="" width="36" height="36">
                        <div style="min-width: 0;">
                            <div class="ijs-title">Describe it, Jenny finds it</div>
                            <div class="ijs-sub">Type what you want in your own words. Jenny sets the filters for you.</div>
                        </div>
                        <span class="ijs-badge">PRO &amp; PREMIUM</span>
                        <span class="ijs-left" x-show="jennyLeft !== null" x-cloak x-text="jennyLeft + ' searches left today'"></span>
                    </div>
                    <div class="ijs-row">
                        <svg class="ijs-spark" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        <input class="ijs-input" type="text" x-model="jennyQuery" maxlength="300" placeholder="e.g. Remote design internship paying over 250k" aria-label="Describe the internship you want">
                        <button type="submit" class="ijs-btn" :disabled="jennyBusy">
                            <span class="ijs-spin" x-show="jennyBusy" x-cloak></span>
                            <svg x-show="!jennyBusy" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <span x-text="jennyBusy ? 'Searching...' : 'Ask Jenny'">Ask Jenny</span>
                        </button>
                    </div>
                    <div class="ijs-chips" x-show="!jennySummary && !jennyError">
                        <button type="button" class="ijs-chip" @click="jennyQuery = 'Remote design internship paying over 250k'; jennySearch()">Remote design, 250k+</button>
                        <button type="button" class="ijs-chip" @click="jennyQuery = 'Hybrid data analyst internship'; jennySearch()">Hybrid data roles</button>
                        <button type="button" class="ijs-chip" @click="jennyQuery = 'Software engineering internship in London'; jennySearch()">Engineering in London</button>
                        <button type="button" class="ijs-chip" @click="jennyQuery = 'Marketing internship I can do remotely'; jennySearch()">Remote marketing</button>
                    </div>
                    <div class="ijs-note ok" x-show="jennySummary" x-cloak>
                        <span x-text="jennySummary"></span>
                        <button type="button" @click="clearJenny()">Clear</button>
                    </div>
                    <div class="ijs-note warn" x-show="jennyError" x-cloak>
                        <span x-text="jennyError"></span>
                        <a x-show="jennyUpgrade" :href="jennyUpgrade">See plans</a>
                    </div>
                </form>
                
                <!-- Zero State -->
                <div x-show="filteredInternships.length === 0" x-transition 
                    class="bg-white border border-gray-200 rounded-2xl p-12 text-center shadow-sm">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">No internships found</h3>
                    <p class="text-sm text-gray-500 mb-6">We couldn't find any opportunities matching your criteria.</p>
                    <button @click="resetFilters()" class="bg-primary hover:bg-primary-hover text-white text-sm font-semibold py-2 px-5 rounded-lg transition shadow-sm">
                        Reset Filters
                    </button>
                </div>

                <!-- Grid wrapper (2-column cards) -->
                <div x-show="filteredInternships.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <template x-for="item in paginatedInternships" :key="item.id">
                        
                        <!-- Internship Card -->
                        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow relative flex flex-col justify-between h-full" style="min-width: 0;">
                            
                            <!-- Card Header Info -->
                            <div>
                                <div class="flex justify-between items-start gap-3">
                                    <!-- Brand Logo / Category (shrinks so the title truncates instead of pushing the actions off-screen) -->
                                    <div class="flex items-center space-x-3" style="min-width: 0; flex: 1 1 auto;">
                                        <!-- Clickable Company Logo linking to Company Profile -->
                                        <a :href="'<?= url('/company/') ?>' + (item.companySlug || item.company.toLowerCase().replace(/[^a-z0-9]+/g, '-'))" 
                                           class="hover:opacity-90 transition flex-shrink-0"
                                           :aria-label="'View ' + item.company + ' profile'">
                                            <div :class="item.logoBg + ' h-10 w-10 rounded-xl flex items-center justify-center font-bold text-lg shadow-sm flex-shrink-0'" x-text="item.logoInitial"></div>
                                        </a>
                                        <div style="min-width: 0;">
                                            <span style="display: flex; align-items: center; gap: 6px; min-width: 0;"><span class="text-[10px] font-bold text-primary uppercase tracking-widest" x-text="item.category"></span><span x-show="item.premium" style="display: none; font-size: 9.5px; font-weight: 800; letter-spacing: .06em; color: #FFFFFF; background: #000623; padding: 2px 7px; border-radius: 999px; white-space: nowrap;">PREMIUM</span></span>
                                            <h3 class="font-bold text-gray-900 text-base leading-tight mt-0.5 truncate">
                                                <a :href="'<?= url('/internship/') ?>' + item.id" class="text-gray-900 hover:text-primary transition" x-text="item.title"></a>
                                            </h3>
                                        </div>
                                    </div>
                                    
                                    <!-- Bookmark Icon -->
                                    <button type="button" @click="savedStates[item.company] = !savedStates[item.company]; $dispatch('show-toast', { text: savedStates[item.company] ? 'Saved to bookmarks' : 'Removed from bookmarks', type: 'info' })" 
                                        class="text-gray-400 hover:text-primary focus:outline-none transition p-1 hover:bg-gray-50 rounded-lg flex-shrink-0"
                                        :aria-label="savedStates[item.company] ? 'Unsave internship' : 'Save internship'">
                                        <!-- Saved state active -->
                                        <svg x-show="savedStates[item.company]" class="h-6 w-6 text-primary" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                        </svg>
                                        <!-- Saved state inactive -->
                                        <svg x-show="!savedStates[item.company]" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Company & Location & Commitment -->
                                <div class="mt-3 text-xs text-gray-500 flex items-center space-x-2">
                                    <!-- Clickable Company Name linking to Company Profile -->
                                    <a :href="'<?= url('/company/') ?>' + (item.companySlug || item.company.toLowerCase().replace(/[^a-z0-9]+/g, '-'))" 
                                       class="font-medium text-gray-700 hover:text-primary transition hover:underline" 
                                       x-text="item.company"></a>
                                    <span>•</span>
                                    <span x-text="item.location"></span>
                                    <span>•</span>
                                    <span class="italic text-[10px]" x-text="item.commitment"></span>
                                </div>

                                <!-- Description -->
                                <p class="mt-4 text-xs text-gray-600 leading-relaxed" x-text="item.description"></p>
                            </div>

                            <!-- Card Footer Tags, Stipend & Button -->
                            <div class="mt-6 pt-4 border-t border-gray-100 flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <!-- Remote Badge -->
                                    <span class="bg-gray-100 text-gray-700 text-[10px] font-bold px-3 py-1.5 rounded-lg tracking-wide uppercase" x-text="item.tag"></span>
                                    <!-- Stipend Badge -->
                                    <span class="text-xs font-semibold text-gray-800" x-text="item.locked ? 'Pay shown to Premium' : '₦' + Number(item.stipendAmount).toLocaleString() + '/mo'" :style="item.locked ? 'color: #64748B; font-weight: 500;' : ''"></span>
                                </div>
                                
                                <!-- Apply Action Button -->
                                <a :href="item.locked ? '<?= url('/pricing?plan=premium&ref=premium-listing') ?>' : '<?= url('/internship/') ?>' + item.id"
                                   class="bg-primary hover:bg-primary-hover text-white text-xs font-bold px-4 py-2 rounded-xl transition active:scale-95 shadow-sm inline-flex items-center justify-center"
                                   :style="'white-space: nowrap !important; flex-shrink: 0 !important;' + (item.locked ? ' background: #000623;' : '')"
                                   x-text="item.locked ? 'Unlock' : 'Apply'">
                                    Apply
                                </a>
                            </div>

                        </div>

                    </template>
                </div>

                <!-- Circular Pagination Section (Matching Standardized Circular Design) -->
                <div x-show="filteredInternships.length > 0" class="int-pagination-wrap">
                    <button type="button" 
                            @click="if(currentPage > 1) { currentPage--; window.scrollTo({top: 0, behavior: 'smooth'}); }" 
                            class="int-page-nav-text-btn"
                            :disabled="currentPage === 1">
                        Previous
                    </button>
                    
                    <template x-for="p in totalPages" :key="p">
                        <button type="button" 
                                @click="currentPage = p; window.scrollTo({top: 0, behavior: 'smooth'});" 
                                class="int-page-btn" 
                                :class="{ 'is-active': currentPage === p }" 
                                x-text="p">
                        </button>
                    </template>

                    <button type="button" 
                            @click="if(currentPage < totalPages) { currentPage++; window.scrollTo({top: 0, behavior: 'smooth'}); }" 
                            class="int-page-nav-text-btn"
                            :disabled="currentPage === totalPages">
                        Next
                    </button>
                </div>

            </div>

        </div>

    </div>
</div>
