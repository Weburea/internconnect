<?php
declare(strict_types=1);
/**
 * Admin Live Visitors Map Component
 * Zoomable world map of current sessions with country and session breakdowns.
 * Reads every location from a JSON feed (default /admin/api/live-sessions).
 * Reusable: set $liveMap before including, e.g. for live interviews:
 *   $liveMap = ['feed' => url('/admin/api/interviews/live-map'), 'title' => 'Live interviews', ...]
 *
 * @var array $liveMap  feed, title, subtitle, totalLabel, labelA, labelB, emptyText
 */
$lm = array_merge([
    'feed'       => url('/admin/api/live-sessions'),
    'title'      => 'Live visitors',
    'subtitle'   => 'Where people are using InternConnect right now',
    'totalLabel' => 'online now',
    'labelA'     => 'students',
    'labelB'     => 'employers',
    'emptyText'  => 'Nobody is online right now.',
], $liveMap ?? []);
$lmLabels = ['a' => $lm['labelA'], 'b' => $lm['labelB'], 'total' => $lm['totalLabel']];
unset($liveMap);
?>
<?php if (empty($GLOBALS['__adminLiveMapAssets'])): $GLOBALS['__adminLiveMapAssets'] = true; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/maplibre-gl@4.7.1/dist/maplibre-gl.css">
<script src="https://cdn.jsdelivr.net/npm/maplibre-gl@4.7.1/dist/maplibre-gl.js"></script>
<script>
function adminLiveMap(feedUrl, labels) {
    labels = labels || { a: 'students', b: 'employers', total: 'online' };
    // MapLibre objects stay outside Alpine state because reactive proxies break them
    let map = null;
    let popup = null;
    let latestLocations = [];

    const regions = {
        world: [[-168, -56], [180, 74]],
        africa: [[-20, -36], [55, 38]],
        nigeria: [[2.5, 4], [15, 14]],
        northAmerica: [[-170, 10], [-50, 72]],
        southAmerica: [[-85, -56], [-33, 14]],
        europe: [[-12, 35], [40, 70]],
        asia: [[25, -10], [150, 55]],
    };

    const toGeoJson = (locations) => ({
        type: 'FeatureCollection',
        features: locations.map((place) => ({
            type: 'Feature',
            geometry: { type: 'Point', coordinates: [place.lng, place.lat] },
            properties: place,
        })),
    });

    const buildPopup = (place) => {
        const root = document.createElement('div');
        root.className = 'admin-map-popup';

        const title = document.createElement('strong');
        title.textContent = place.city + ', ' + place.country;
        root.appendChild(title);

        const detail = document.createElement('span');
        detail.textContent = place.online.toLocaleString() + ' ' + labels.total + ' · ' + place.students.toLocaleString() + ' ' + labels.a + ' · ' + place.employers.toLocaleString() + ' ' + labels.b;
        root.appendChild(detail);

        return root;
    };

    return {
        isSample: false,
        total: 0,
        students: 0,
        employers: 0,
        countries: [],
        sessions: [],
        panelTab: 'countries',
        region: 'world',
        mapUnavailable: false,
        regionOptions: [
            { id: 'world', label: 'World' },
            { id: 'africa', label: 'Africa' },
            { id: 'nigeria', label: 'Nigeria' },
            { id: 'northAmerica', label: 'North America' },
            { id: 'southAmerica', label: 'South America' },
            { id: 'europe', label: 'Europe' },
            { id: 'asia', label: 'Asia' },
        ],

        async init() {
            await this.refresh();
            this.createMap();
            setInterval(() => {
                if (!document.hidden) this.refresh();
            }, 30000);
        },

        async refresh() {
            try {
                const response = await fetch(feedUrl, { headers: { Accept: 'application/json' }, credentials: 'same-origin' });
                if (!response.ok) return;

                const feed = await response.json();
                this.isSample = feed.is_sample;
                this.total = feed.total;
                this.students = feed.students;
                this.employers = feed.employers;
                this.countries = feed.countries;
                this.sessions = feed.sessions;
                latestLocations = feed.locations;

                const source = map && map.getSource('live-sessions');
                if (source) source.setData(toGeoJson(latestLocations));
            } catch (error) {
                // The next poll retries; the last good data stays on screen
            }
        },

        createMap() {
            if (typeof maplibregl === 'undefined') {
                this.mapUnavailable = true;
                return;
            }

            try {
                map = new maplibregl.Map({
                    container: this.$refs.mapCanvas,
                    style: 'https://tiles.openfreemap.org/styles/positron',
                    bounds: regions.world,
                    fitBoundsOptions: { padding: 16 },
                    minZoom: 0.6,
                    renderWorldCopies: false,
                    attributionControl: { compact: true },
                    cooperativeGestures: true,
                });
            } catch (error) {
                this.mapUnavailable = true;
                return;
            }

            map.addControl(new maplibregl.NavigationControl({ showCompass: false }), 'top-right');
            map.on('load', () => this.addSessionLayers());
        },

        addSessionLayers() {
            map.getStyle().layers.forEach((layer) => {
                if (layer.type === 'background') map.setPaintProperty(layer.id, 'background-color', '#F8FAFC');
                if (layer.type === 'fill' && layer.id.includes('water')) map.setPaintProperty(layer.id, 'fill-color', '#E9EDFB');
            });

            map.addSource('live-sessions', {
                type: 'geojson',
                data: toGeoJson(latestLocations),
                cluster: true,
                clusterMaxZoom: 8,
                clusterRadius: 44,
                clusterProperties: { online: ['+', ['get', 'online']] },
            });

            const markerRadius = ['interpolate', ['linear'], ['get', 'online'], 1, 11, 50, 14, 200, 18, 700, 24];

            map.addLayer({
                id: 'live-pulse',
                type: 'circle',
                source: 'live-sessions',
                paint: { 'circle-color': '#4F48E6', 'circle-opacity': 0.14, 'circle-radius': ['*', markerRadius, 1.7] },
            });
            map.addLayer({
                id: 'live-marker',
                type: 'circle',
                source: 'live-sessions',
                paint: { 'circle-color': '#4F48E6', 'circle-radius': markerRadius, 'circle-stroke-color': '#FFFFFF', 'circle-stroke-width': 2 },
            });
            map.addLayer({
                id: 'live-count',
                type: 'symbol',
                source: 'live-sessions',
                layout: {
                    'text-field': ['to-string', ['get', 'online']],
                    'text-font': ['Noto Sans Bold'],
                    'text-size': 11,
                    'text-allow-overlap': true,
                },
                paint: { 'text-color': '#FFFFFF' },
            });

            map.on('click', 'live-marker', (event) => this.openMarker(event.features[0]));
            map.on('mouseenter', 'live-marker', () => { map.getCanvas().style.cursor = 'pointer'; });
            map.on('mouseleave', 'live-marker', () => { map.getCanvas().style.cursor = ''; });

            this.animatePulse(markerRadius);
        },

        animatePulse(markerRadius) {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

            setInterval(() => {
                if (document.hidden) return;
                const phase = (Date.now() % 2200) / 2200;
                map.setPaintProperty('live-pulse', 'circle-radius', ['*', markerRadius, 1.25 + phase * 1.15]);
                map.setPaintProperty('live-pulse', 'circle-opacity', 0.26 * (1 - phase));
            }, 50);
        },

        async openMarker(feature) {
            if (feature.properties.cluster) {
                const zoom = await map.getSource('live-sessions').getClusterExpansionZoom(feature.properties.cluster_id);
                map.easeTo({ center: feature.geometry.coordinates, zoom });
                return;
            }
            this.showPopup(feature.properties, feature.geometry.coordinates);
        },

        showPopup(place, coordinates) {
            if (popup) popup.remove();
            popup = new maplibregl.Popup({ closeButton: false, offset: 18, maxWidth: '260px' })
                .setLngLat(coordinates)
                .setDOMContent(buildPopup(place))
                .addTo(map);
        },

        selectRegion(id) {
            this.region = id;
            if (popup) popup.remove();
            if (map) map.fitBounds(regions[id], { padding: 24, duration: 900 });
        },

        focusCountry(code) {
            const points = latestLocations.filter((place) => place.code === code);
            if (!map || !points.length) return;

            this.region = '';
            const bounds = new maplibregl.LngLatBounds();
            points.forEach((place) => bounds.extend([place.lng, place.lat]));
            map.fitBounds(bounds, { padding: 90, maxZoom: 6, duration: 900 });
        },

        focusSession(session) {
            const place = latestLocations.find((item) => item.city === session.city && item.code === session.code);
            if (!map || !place) return;

            this.region = '';
            map.flyTo({ center: [place.lng, place.lat], zoom: 9.5, duration: 1000 });
            this.showPopup(place, [place.lng, place.lat]);
        },
    };
}
</script>
<?php endif; ?>

<section class="admin-card admin-live-card admin-fade-in admin-fade-in-3" x-data="adminLiveMap(<?= htmlspecialchars(json_encode($lm['feed']), ENT_QUOTES, 'UTF-8') ?>, <?= htmlspecialchars(json_encode($lmLabels), ENT_QUOTES, 'UTF-8') ?>)" aria-label="<?= htmlspecialchars($lm['title']) ?> map">
    <div class="admin-card-header flex-wrap">
        <div class="admin-live-heading">
            <h2 class="admin-card-title"><?= htmlspecialchars($lm['title']) ?></h2>
            <p class="admin-card-subtitle"><?= htmlspecialchars($lm['subtitle']) ?></p>
        </div>
        <span class="admin-live-source" x-show="isSample">Sample data</span>
    </div>

    <div class="admin-map-regions no-scrollbar" role="group" aria-label="Map region">
        <template x-for="option in regionOptions" :key="option.id">
            <button type="button" class="admin-map-region-btn" :class="{ 'is-active': region === option.id }" @click="selectRegion(option.id)" x-text="option.label"></button>
        </template>
    </div>

    <div class="admin-live-layout">
        <div class="admin-map-frame">
            <div class="admin-map-canvas" x-ref="mapCanvas"></div>
            <div class="admin-map-fallback" x-show="mapUnavailable" style="display: none;">
                <strong>Map could not load</strong>
                <span>The country and session lists are still up to date.</span>
            </div>
        </div>

        <aside class="admin-live-panel">
            <div class="admin-live-total">
                <div class="admin-live-total-main">
                    <span class="admin-live-beacon"></span>
                    <span class="admin-live-total-value" x-text="total.toLocaleString()">0</span>
                    <span class="admin-live-total-label"><?= htmlspecialchars($lm['totalLabel']) ?></span>
                </div>
                <div class="admin-live-total-split">
                    <span><strong x-text="students.toLocaleString()">0</strong> <?= htmlspecialchars($lm['labelA']) ?></span>
                    <span><strong x-text="employers.toLocaleString()">0</strong> <?= htmlspecialchars($lm['labelB']) ?></span>
                </div>
            </div>

            <div class="admin-segmented-ctrl admin-live-tabs">
                <button type="button" class="admin-segmented-btn" :class="{ 'is-active': panelTab === 'countries' }" @click="panelTab = 'countries'">Countries</button>
                <button type="button" class="admin-segmented-btn" :class="{ 'is-active': panelTab === 'sessions' }" @click="panelTab = 'sessions'">Sessions</button>
            </div>

            <p class="admin-live-row-meta" x-show="total === 0" style="padding: 12px 4px; margin: 0;"><?= htmlspecialchars($lm['emptyText']) ?></p>
            <div class="admin-live-list" x-show="panelTab === 'countries'">
                <template x-for="country in countries" :key="country.code">
                    <button type="button" class="admin-live-row" @click="focusCountry(country.code)">
                        <span class="admin-live-code" x-text="country.code"></span>
                        <span class="admin-live-row-body">
                            <span class="admin-live-row-top">
                                <span class="admin-live-row-name" x-text="country.name"></span>
                                <span class="admin-live-row-count" x-text="country.online.toLocaleString()"></span>
                            </span>
                            <span class="admin-progress-bg admin-live-bar">
                                <span class="admin-progress-fill admin-live-fill" :style="{ width: Math.max(4, country.share) + '%' }"></span>
                            </span>
                        </span>
                    </button>
                </template>
            </div>

            <div class="admin-live-list" x-show="panelTab === 'sessions'" style="display: none;">
                <template x-for="session in sessions" :key="session.id">
                    <button type="button" class="admin-live-row" @click="focusSession(session)">
                        <span class="admin-live-code" x-text="session.code"></span>
                        <span class="admin-live-row-body">
                            <span class="admin-live-row-top">
                                <span class="admin-live-row-name" x-text="session.city + ', ' + session.country"></span>
                                <span class="admin-live-row-time" x-text="session.active"></span>
                            </span>
                            <span class="admin-live-row-meta" x-text="session.ip + ' · ' + session.device"></span>
                            <span class="admin-live-row-meta" x-text="session.role + ' · ' + session.page"></span>
                        </span>
                    </button>
                </template>
            </div>
        </aside>
    </div>
</section>
