<?php
declare(strict_types=1);
/**
 * Admin: API reference (Swagger UI for /api/v1).
 * Route: /admin/api-docs (spec: /admin/api-docs/openapi.json, built by App\Api\OpenApiSpec)
 * Spec: docs/51-api-and-security.md
 *
 * @var array  $staff
 * @var string $specUrl
 * @var bool   $apiReady  JWT_SECRET and API_DEMO_PASSWORD are set
 */
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swagger-ui-dist@5.17.14/swagger-ui.css">
<style>
.apd { display: flex; flex-direction: column; gap: 18px; }
.apd-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 12px 20px; flex-wrap: wrap; }
.apd-head h1 { font-size: 24px; font-weight: 800; color: #000623; margin: 0 0 4px; letter-spacing: -0.02em; }
.apd-head p { font-size: 13.5px; color: #64748B; margin: 0; max-width: 680px; line-height: 1.5; }
.apd-actions { display: flex; gap: 10px; flex-wrap: wrap; }
.apd-btn { display: inline-flex; align-items: center; gap: 7px; height: 38px; padding: 0 14px; border-radius: 10px; border: 1px solid #E2E8F0; background: #FFFFFF; font-size: 13px; font-weight: 700; color: #334155; text-decoration: none; }
.apd-btn:hover { background: #F8FAFC; color: #0F172A; }
.apd-btn svg { width: 15px; height: 15px; }
.apd-cards { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; }
.apd-card { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 14px; padding: 16px 18px; min-width: 0; }
.apd-card strong { display: block; font-size: 13.5px; color: #0F172A; margin-bottom: 4px; }
.apd-card span { display: block; font-size: 12.5px; color: #64748B; line-height: 1.5; }
.apd-card code { font-size: 12px; background: #F1F5F9; padding: 1px 6px; border-radius: 6px; color: #334155; word-break: break-all; }
.apd-warn { padding: 12px 14px; border-radius: 12px; background: #FFFBEB; border: 1px solid #FDE68A; color: #92400E; font-size: 13px; line-height: 1.5; }
.apd-ui { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 18px; padding: 6px 22px 22px; min-width: 0; }
/* Swagger UI, InternConnect theme */
.apd-ui .swagger-ui, .apd-ui .swagger-ui .opblock-tag, .apd-ui .swagger-ui .opblock .opblock-summary-description, .apd-ui .swagger-ui table thead tr td, .apd-ui .swagger-ui table thead tr th, .apd-ui .swagger-ui .parameter__name, .apd-ui .swagger-ui .response-col_status, .apd-ui .swagger-ui .tab li, .apd-ui .swagger-ui .opblock-description-wrapper p, .apd-ui .swagger-ui label, .apd-ui .swagger-ui .btn, .apd-ui .swagger-ui select, .apd-ui .swagger-ui input, .apd-ui .swagger-ui .model-title, .apd-ui .swagger-ui section.models h4, .apd-ui .swagger-ui .operation-filter-input { font-family: inherit; }
.apd-ui .swagger-ui .wrapper { padding: 0; max-width: none; }
.apd-ui .swagger-ui .topbar, .apd-ui .swagger-ui .information-container { display: none; }
.apd-ui .swagger-ui .scheme-container { background: transparent; box-shadow: none; padding: 16px 0 6px; margin: 0; }
.apd-ui .swagger-ui .scheme-container .schemes { align-items: center; gap: 12px; flex-wrap: wrap; }
.apd-ui .swagger-ui .scheme-container .schemes > label { font-size: 12px; font-weight: 700; color: #64748B; }
.apd-ui .swagger-ui .filter-container { margin: 6px 0 4px; }
.apd-ui .swagger-ui .filter-container .filter { padding: 0; }
.apd-ui .swagger-ui .operation-filter-input { width: 100%; margin: 0; border: 1px solid #CBD5E1; border-radius: 12px; padding: 11px 14px 11px 40px; font-size: 14px; background: #FFFFFF url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' viewBox='0 0 24 24' stroke='%2394A3B8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z'/%3E%3C/svg%3E") no-repeat 14px center; box-sizing: border-box; }
.apd-ui .swagger-ui .filter-container .operation-filter-input { padding-left: 40px !important; }
.apd-ui .swagger-ui .opblock .tab-header .tab-item.active h4 span::after { background: #4F48E6; }
.apd-ui .swagger-ui .btn { border-radius: 10px; font-weight: 700; font-size: 13px; box-shadow: none; padding: 8px 16px; transition: background .15s ease, border-color .15s ease, color .15s ease; }
.apd-ui .swagger-ui .btn.authorize { color: #4F48E6; border: 1px solid #C7D2FE; background: #F5F6FF; display: inline-flex; align-items: center; gap: 6px; }
.apd-ui .swagger-ui .btn.authorize:hover { background: #EEF0FF; border-color: #4F48E6; }
.apd-ui .swagger-ui .btn.authorize svg { fill: #4F48E6; width: 14px; height: 14px; }
.apd-ui .swagger-ui .btn.execute, .apd-ui .swagger-ui .btn.modal-btn.auth.authorize, .apd-ui .swagger-ui .auth-btn-wrapper .btn-done { background: #4F48E6; border: 1px solid #4F48E6; color: #FFFFFF; }
.apd-ui .swagger-ui .btn.execute:hover { background: #4338CA; }
.apd-ui .swagger-ui .btn.try-out__btn, .apd-ui .swagger-ui .btn.cancel { background: #FFFFFF; border: 1px solid #CBD5E1; color: #0F172A; }
.apd-ui .swagger-ui .btn.try-out__btn:hover { border-color: #4F48E6; color: #4F48E6; }
.apd-ui .swagger-ui .btn.cancel { color: #B91C1C; border-color: #FECACA; }
.apd-ui .swagger-ui .btn-group { gap: 8px; padding: 14px 20px; }
.apd-ui .swagger-ui .btn-group .btn { border-radius: 10px !important; }
/* Tag groups */
.apd-ui .swagger-ui .opblock-tag-section { margin-top: 10px; }
.apd-ui .swagger-ui .opblock-tag { font-size: 17px; font-weight: 800; color: #000623; letter-spacing: -.01em; border-bottom: 1px solid #EEF0F4; padding: 16px 4px 12px; margin: 0 0 10px; }
.apd-ui .swagger-ui .opblock-tag:hover { background: transparent; }
.apd-ui .swagger-ui .opblock-tag small { font-size: 12.5px; font-weight: 500; color: #64748B; padding-left: 10px; }
.apd-ui .swagger-ui .opblock-tag svg, .apd-ui .swagger-ui .expand-operation svg { width: 16px; height: 16px; fill: #94A3B8; }
/* Operations */
.apd-ui .swagger-ui .opblock { border-radius: 14px; border: 1px solid #E2E8F0 !important; background: #FFFFFF !important; box-shadow: none; margin: 0 0 10px; overflow: hidden; transition: border-color .15s ease, box-shadow .15s ease; }
.apd-ui .swagger-ui .opblock:hover { border-color: #C7D2FE !important; box-shadow: 0 8px 24px -18px rgba(79, 72, 230, .45); }
.apd-ui .swagger-ui .opblock.is-open { border-color: #C7D2FE !important; box-shadow: 0 14px 34px -24px rgba(0, 6, 35, .35); }
.apd-ui .swagger-ui .opblock .opblock-summary { padding: 10px 12px; gap: 12px; border: 0; align-items: center; }
.apd-ui .swagger-ui .opblock .opblock-summary-method { min-width: 62px; padding: 6px 0; border-radius: 8px; font-size: 11.5px; font-weight: 800; letter-spacing: .06em; text-shadow: none; text-align: center; }
.apd-ui .swagger-ui .opblock.opblock-get .opblock-summary-method { background: #4F48E6; }
.apd-ui .swagger-ui .opblock.opblock-post .opblock-summary-method { background: #000623; }
.apd-ui .swagger-ui .opblock.opblock-put .opblock-summary-method, .apd-ui .swagger-ui .opblock.opblock-patch .opblock-summary-method { background: #6366F1; }
.apd-ui .swagger-ui .opblock.opblock-delete .opblock-summary-method { background: #B91C1C; }
.apd-ui .swagger-ui .opblock .opblock-summary-path, .apd-ui .swagger-ui .opblock .opblock-summary-path__deprecated { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; font-size: 14px; font-weight: 700; color: #0F172A; padding: 0; word-break: break-all; }
.apd-ui .swagger-ui .opblock .opblock-summary-description { font-size: 13px; color: #64748B; padding: 0; }
.apd-ui .swagger-ui .opblock .opblock-summary-control:focus { outline: none; }
.apd-ui .swagger-ui .opblock .opblock-summary-control:focus-visible { outline: 2px solid #4F48E6; outline-offset: 2px; border-radius: 8px; }
.apd-ui .swagger-ui .authorization__btn svg { fill: #94A3B8; width: 14px; height: 14px; }
.apd-ui .swagger-ui .authorization__btn.locked svg { fill: #4F48E6; }
.apd-ui .swagger-ui .opblock .opblock-section-header { background: #F8FAFC; box-shadow: none; border-top: 1px solid #EEF0F4; border-bottom: 1px solid #EEF0F4; padding: 10px 20px; min-height: 0; }
.apd-ui .swagger-ui .opblock .opblock-section-header h4 { font-size: 13px; font-weight: 800; color: #0F172A; }
.apd-ui .swagger-ui .opblock-description-wrapper, .apd-ui .swagger-ui .opblock-body .parameters-container, .apd-ui .swagger-ui .responses-wrapper .responses-inner { padding: 14px 20px; }
.apd-ui .swagger-ui .opblock-description-wrapper p { font-size: 13.5px; color: #334155; line-height: 1.6; }
/* Tables, parameters, inputs */
.apd-ui .swagger-ui table thead tr td, .apd-ui .swagger-ui table thead tr th { font-size: 11px; font-weight: 800; color: #64748B; text-transform: uppercase; letter-spacing: .05em; border-bottom: 1px solid #E2E8F0; padding: 8px 0; }
.apd-ui .swagger-ui .parameter__name { font-size: 13.5px; font-weight: 700; color: #0F172A; }
.apd-ui .swagger-ui .parameter__name.required span, .apd-ui .swagger-ui .parameter__name.required::after { color: #B91C1C; }
.apd-ui .swagger-ui .parameter__type, .apd-ui .swagger-ui .parameter__in { font-size: 11.5px; color: #64748B; font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; }
.apd-ui .swagger-ui input[type=text], .apd-ui .swagger-ui input[type=password], .apd-ui .swagger-ui input[type=email], .apd-ui .swagger-ui input[type=file], .apd-ui .swagger-ui textarea, .apd-ui .swagger-ui select { border: 1px solid #CBD5E1; border-radius: 10px; background-color: #FFFFFF; color: #0F172A; font-size: 13.5px; padding: 9px 12px; box-shadow: none; max-width: 100%; box-sizing: border-box; transition: border-color .15s ease, box-shadow .15s ease; }
.apd-ui .swagger-ui select { padding-right: 32px; background-position: right 10px center; }
.apd-ui .swagger-ui input:focus, .apd-ui .swagger-ui textarea:focus, .apd-ui .swagger-ui select:focus { outline: none; border-color: #4F48E6; box-shadow: 0 0 0 3px rgba(79, 72, 230, .15); }
.apd-ui .swagger-ui textarea.body-param__text { min-height: 160px; font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; font-size: 12.5px; background-color: #F8FAFC; }
.apd-ui .swagger-ui .tab li { font-size: 12.5px; font-weight: 700; color: #64748B; }
.apd-ui .swagger-ui .tab li.active, .apd-ui .swagger-ui .tab li button.tablinks.active { color: #4F48E6; }
/* Code */
.apd-ui .swagger-ui .highlight-code > .microlight, .apd-ui .swagger-ui .opblock-body pre.microlight, .apd-ui .swagger-ui .curl-command pre { background: #000623 !important; color: #E2E8F0 !important; padding: 14px 16px !important; font-size: 12.5px; line-height: 1.6; border: 0; border-radius: 12px; max-height: 380px; }
.apd-ui .swagger-ui .copy-to-clipboard { background: #4F48E6; border-radius: 8px; right: 10px; bottom: 10px; }
.apd-ui .swagger-ui .request-url pre { background: #F1F5F9 !important; color: #0F172A !important; border-radius: 10px; padding: 10px 12px !important; font-size: 12.5px; word-break: break-all; white-space: pre-wrap; }
/* Responses */
.apd-ui .swagger-ui .responses-table .response-col_status { font-size: 13px; font-weight: 800; color: #0F172A; }
.apd-ui .swagger-ui .response-col_links { color: #94A3B8; font-size: 12px; }
.apd-ui .swagger-ui .responses-inner h4, .apd-ui .swagger-ui .responses-inner h5 { font-size: 12.5px; color: #64748B; font-weight: 700; }
/* Models */
.apd-ui .swagger-ui section.models { border: 1px solid #E2E8F0; border-radius: 14px; margin-top: 24px; }
.apd-ui .swagger-ui section.models h4 { font-size: 15px; font-weight: 800; color: #000623; padding: 4px 6px; }
.apd-ui .swagger-ui section.models .model-container { background: #F8FAFC; border-radius: 10px; margin: 0 14px 10px; }
.apd-ui .swagger-ui section.models .model-container:hover { background: #F1F5F9; }
.apd-ui .swagger-ui .model, .apd-ui .swagger-ui .model-title { font-size: 13px; color: #0F172A; }
.apd-ui .swagger-ui .prop-type { color: #4F48E6; }
/* Authorize dialog */
.apd-ui .swagger-ui .dialog-ux .backdrop-ux { background: rgba(0, 6, 35, .55); backdrop-filter: blur(3px); }
.apd-ui .swagger-ui .dialog-ux .modal-ux { border-radius: 18px; border: 0; box-shadow: 0 30px 70px -20px rgba(0, 6, 35, .5); max-width: min(620px, calc(100vw - 32px)); }
.apd-ui .swagger-ui .dialog-ux .modal-ux-header { border-bottom: 1px solid #EEF0F4; padding: 16px 20px; }
.apd-ui .swagger-ui .dialog-ux .modal-ux-header h3 { font-size: 16px; font-weight: 800; color: #000623; font-family: inherit; }
.apd-ui .swagger-ui .dialog-ux .modal-ux-content { padding: 16px 20px; }
.apd-ui .swagger-ui .dialog-ux .modal-ux-content h4 { font-size: 14px; color: #0F172A; font-family: inherit; }
.apd-ui .swagger-ui .dialog-ux .modal-ux-content p { font-size: 13px; color: #475569; }
.apd-ui .swagger-ui .auth-container { border-bottom: 0; padding: 6px 0; }
.apd-ui .swagger-ui .auth-container input[type=text], .apd-ui .swagger-ui .auth-container input[type=password] { width: 100%; min-width: 0; }
/* Responsive */
@media (max-width: 760px) {
    .apd-ui { padding: 4px 12px 14px; border-radius: 14px; }
    .apd-ui .swagger-ui .opblock .opblock-summary { flex-wrap: wrap; row-gap: 6px; padding: 10px; }
    .apd-ui .swagger-ui .opblock .opblock-summary-control { flex-wrap: wrap; row-gap: 4px; }
    .apd-ui .swagger-ui .opblock .opblock-summary-path { font-size: 13px; max-width: calc(100% - 80px); }
    .apd-ui .swagger-ui .opblock .opblock-summary-description { flex-basis: 100%; font-size: 12.5px; }
    .apd-ui .swagger-ui .opblock-description-wrapper, .apd-ui .swagger-ui .opblock-body .parameters-container, .apd-ui .swagger-ui .responses-wrapper .responses-inner, .apd-ui .swagger-ui .opblock .opblock-section-header { padding-left: 12px; padding-right: 12px; }
    .apd-ui .swagger-ui table.parameters tr, .apd-ui .swagger-ui table.responses-table tr { display: flex; flex-direction: column; padding: 8px 0; }
    .apd-ui .swagger-ui table.parameters td, .apd-ui .swagger-ui table.responses-table td { width: 100% !important; max-width: 100%; padding: 2px 0 !important; }
    .apd-ui .swagger-ui table thead { display: none; }
    .apd-ui .swagger-ui .opblock-tag { font-size: 15.5px; }
    .apd-ui .swagger-ui .opblock-tag small { display: none; }
}
@media (max-width: 900px) { .apd-cards { grid-template-columns: minmax(0, 1fr); } }
</style>

<div class="admin-shell">
    <?php include dirname(__DIR__, 2) . '/shared/components/admin-sidebar.php'; ?>
    <div class="admin-main-wrap">
        <?php include dirname(__DIR__, 2) . '/shared/components/admin-topnav.php'; ?>
        <main class="admin-content-body">
            <div class="apd">
                <div class="apd-head">
                    <div>
                        <h1>API Reference</h1>
                        <p>REST API v1 for the InternConnect mobile app and partners. Sign in with <code>POST /auth/login</code>, then use Authorize with the access token to try endpoints here.</p>
                    </div>
                    <div class="apd-actions">
                        <a class="apd-btn" href="<?= htmlspecialchars($specUrl) ?>" download="internconnect-openapi.json">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            OpenAPI JSON
                        </a>
                    </div>
                </div>

                <?php if (!$apiReady): ?>
                <p class="apd-warn" role="status">API sign-in is off on this server: add <code>JWT_SECRET</code> (64 hex characters) and <code>API_DEMO_PASSWORD</code> to <code>.env</code>. Public endpoints still work.</p>
                <?php endif; ?>

                <div class="apd-cards">
                    <div class="apd-card"><strong>Base URL</strong><span><code><?= htmlspecialchars(rtrim(absolute_url('/'), '/')) ?>/api/v1</code></span></div>
                    <div class="apd-card"><strong>Authentication</strong><span>Bearer JWT, 15 minutes. Refresh tokens last 30 days and work once.</span></div>
                    <div class="apd-card"><strong>Limits</strong><span>120 requests per minute per account; 10 per minute for AI and room tokens. Over the limit: 429 with Retry-After.</span></div>
                </div>

                <div class="apd-ui"><div id="swagger-ui"></div></div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/swagger-ui-dist@5.17.14/swagger-ui-bundle.js"></script>
<script>
window.addEventListener('load', function () {
    if (typeof SwaggerUIBundle !== 'function') return;
    SwaggerUIBundle({
        url: <?= json_encode($specUrl) ?>,
        dom_id: '#swagger-ui',
        deepLinking: true,
        docExpansion: 'list',
        filter: true,
        displayRequestDuration: true,
        defaultModelsExpandDepth: 0,
        persistAuthorization: false,
        tryItOutEnabled: true,
    });
});
</script>
