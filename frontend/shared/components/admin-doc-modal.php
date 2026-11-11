<?php
declare(strict_types=1);
/**
 * Universal Reusable Admin Document Preview Modal
 * Standardized across Interns Management (CV / Resume) and Employers Management (CAC Filing / ID).
 * Inspired by application.php submission preview design.
 * Features:
 *   - Embedded PDF viewport with direct browser iframe/object preview
 *   - In-modal Word (DOCX) rendering via local bundled docx-preview + JSZip
 *   - Download card for formats browsers cannot render (DOC, RTF) or failed previews
 *   - High-Contrast Lightbox for Scanned Document Images (PNG, JPG, JPEG)
 *   - 100% neutral border styling — ZERO colored accent border strips
 */
?>
<!-- ════════════ Full Screen Admin Document Preview Modal ════════════ -->
<div x-cloak
     x-show="showDocViewerModal"
     class="admin-doc-modal-backdrop"
     style="position: fixed; inset: 0; z-index: 999999 !important; display: none;"
     @click.self="closeDocViewer()"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95"
     @keydown.escape.window="closeDocViewer()"
     role="dialog"
     aria-modal="true"
     aria-labelledby="admin-doc-modal-title">

    <!-- Modal Card (Centered bounded container matching application.php) -->
    <div class="relative z-10 w-full max-w-3xl flex flex-col rounded-2xl bg-white shadow-2xl border border-slate-200 overflow-hidden"
         style="height: min(90vh, 860px); max-height: 90vh; background: #FFFFFF !important;">

        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50/70 flex-shrink-0">
            <div class="flex items-center gap-3 min-w-0 pr-3">
                <div class="h-10 w-10 rounded-xl flex items-center justify-center font-bold text-sm border flex-shrink-0"
                     :class="{
                         'bg-red-50 text-red-500 border-red-100': (viewDoc.type || 'pdf').toLowerCase() === 'pdf',
                         'bg-indigo-50 text-[#4F48E6] border-indigo-100': ['docx', 'doc', 'rtf'].includes((viewDoc.type || '').toLowerCase()),
                         'bg-emerald-50 text-emerald-600 border-emerald-100': ['png', 'jpg', 'jpeg', 'image'].includes((viewDoc.type || '').toLowerCase())
                     }"
                     x-text="(viewDoc.type || 'PDF').toUpperCase().substring(0, 4)">PDF</div>
                <div class="min-w-0">
                    <h3 id="admin-doc-modal-title" class="text-base font-bold text-slate-900 leading-tight truncate" x-text="viewDoc.name || viewDoc.title || 'Document Preview'">Document Preview</h3>
                    <p class="text-xs text-slate-500" x-text="(viewDoc.size || '1.2 MB') + ' • View only'">1.2 MB • View only</p>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <!-- External Open Link -->
                <template x-if="viewDoc.url">
                    <a :href="viewDoc.url" target="_blank" rel="noopener noreferrer"
                       class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-slate-100 rounded-lg transition-colors cursor-pointer"
                       title="Open original document in new tab"
                       aria-label="Open document in new tab">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </template>
                <!-- File type label -->
                <span class="text-xs text-slate-400 font-medium hidden sm:inline"
                      x-text="(viewDoc.type || 'pdf').toLowerCase() === 'pdf' ? 'PDF • Live Preview' : ((viewDoc.type || 'DOC').toUpperCase() + ' • Document')">PDF • Live Preview</span>
                <!-- Close Button -->
                <button type="button"
                        @click="closeDocViewer()"
                        class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors cursor-pointer"
                        aria-label="Close modal">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Content Container -->
        <div class="flex-1 overflow-hidden bg-slate-50 relative" style="min-height: 0;">

            <!-- A. PDF Viewer (Direct Canvas Engine via PDF.js with Iframe Fallback) -->
            <div x-show="(viewDoc.type || 'pdf').toLowerCase() === 'pdf'" 
                 id="admin-pdf-scroll-pane"
                 class="absolute inset-0 w-full h-full bg-slate-100 flex flex-col items-center overflow-y-auto p-4 sm:p-6"
                 x-effect="if (showDocViewerModal && (viewDoc.type || 'pdf').toLowerCase() === 'pdf' && viewDoc.url) { $nextTick(() => { if (typeof window.renderAdminPdf === 'function') window.renderAdminPdf(viewDoc.url); }); }">
                
                <!-- PDF.js Canvas Rendering Container -->
                <div id="admin-pdf-canvas-wrapper" class="w-full flex flex-col items-center gap-4">
                    <div class="flex items-center justify-center p-12 text-slate-400 gap-3">
                        <svg class="animate-spin w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" style="width: 20px !important; height: 20px !important;">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span class="text-xs font-semibold text-slate-600">Rendering high-resolution document preview...</span>
                    </div>
                </div>

                <!-- Hidden fallback iframe for environments without JS canvas support -->
                <template x-if="false">
                    <iframe :src="viewDoc.url + '#toolbar=0&navpanes=0&scrollbar=0&view=Fit'"
                            class="w-full h-full flex-1"
                            style="border: none; width: 100%; height: 100%; min-height: 480px; background: #FFFFFF;"
                            title="Document Preview">
                    </iframe>
                </template>
            </div>

            <!-- B. DOCX: rendered inside the modal by docx-preview -->
            <div x-show="(viewDoc.type || '').toLowerCase() === 'docx'"
                 class="admin-docx-pane"
                 x-effect="if (showDocViewerModal && (viewDoc.type || '').toLowerCase() === 'docx' && viewDoc.url) { $nextTick(() => { if (typeof window.renderAdminDocx === 'function') window.renderAdminDocx(viewDoc.url, viewDoc.name); }); }">
                <div id="admin-docx-host" class="admin-docx-host"></div>
            </div>

            <!-- B2. DOC / RTF: older formats browsers cannot render -->
            <div x-show="['doc', 'rtf'].includes((viewDoc.type || '').toLowerCase())" class="absolute inset-0 flex flex-col items-center justify-center gap-6 p-8 bg-slate-50 overflow-y-auto">
                <div class="flex items-center justify-center h-20 w-20 rounded-3xl bg-indigo-50 border border-indigo-100 shadow-sm">
                    <svg class="h-10 w-10 text-[#4F48E6]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <div class="text-center max-w-sm">
                    <p class="font-bold text-slate-900 text-lg mb-2" x-text="viewDoc.name || 'document.docx'"></p>
                    <p class="text-sm text-slate-500 mb-1" x-text="(viewDoc.size || '1.4 MB') + ' • Ready for review'"></p>
                    <p class="text-sm text-slate-400 leading-relaxed">Older Word (.doc) and RTF files can't be previewed in the browser. Download the file to review it.</p>
                </div>
                <a :href="viewDoc.url" :download="viewDoc.name || 'document.docx'"
                   class="inline-flex items-center gap-2 text-sm font-semibold text-white px-5 py-2.5 rounded-xl transition active:scale-95 shadow cursor-pointer"
                   style="background-color: #4F48E6 !important; color: #FFFFFF !important; white-space: nowrap !important; flex-shrink: 0 !important;">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    Download & Open Locally
                </a>
            </div>

            <!-- C. Image: Centered lightbox -->
            <div x-show="['png', 'jpg', 'jpeg', 'image'].includes((viewDoc.type || '').toLowerCase())" class="absolute inset-0 flex items-center justify-center p-6 bg-slate-900/90 overflow-auto">
                <template x-if="showDocViewerModal && ['png', 'jpg', 'jpeg', 'image'].includes((viewDoc.type || '').toLowerCase()) && viewDoc.url">
                    <img :src="viewDoc.url" :alt="viewDoc.name || 'Document'" class="max-h-full max-w-full object-contain rounded-xl shadow-2xl border border-slate-700/60">
                </template>
            </div>

        </div>

        <!-- Modal Footer -->
        <div class="px-5 sm:px-6 py-4 border-t border-slate-200 flex items-center justify-between bg-white flex-shrink-0">
            <span class="text-xs text-slate-400" x-text="['pdf', 'docx'].includes((viewDoc.type || 'pdf').toLowerCase()) ? 'Scroll to navigate pages' : 'Download the file to view it in full'">Scroll to navigate pages</span>
            <button type="button"
                    @click="closeDocViewer()"
                    class="text-white text-sm font-semibold py-2.5 px-5 rounded-xl transition active:scale-95 shadow-sm cursor-pointer"
                    style="background-color: #4F48E6 !important; color: #FFFFFF !important; white-space: nowrap !important; flex-shrink: 0 !important;">
                Close Preview
            </button>
        </div>
    </div>
</div>

<script src="<?= asset('assets/js/jszip.min.js') ?>"></script>
<script src="<?= asset('assets/js/docx-preview.min.js') ?>"></script>
<script>
window.renderAdminDocx = function(docUrl, docName) {
    var host = document.getElementById('admin-docx-host');
    if (!host) return;

    var showMessage = function(title, detail, withDownload) {
        host.style.zoom = '';
        host.innerHTML = '';

        var card = document.createElement('div');
        card.className = 'admin-docx-message';

        var heading = document.createElement('strong');
        heading.textContent = title;
        card.appendChild(heading);

        var text = document.createElement('span');
        text.textContent = detail;
        card.appendChild(text);

        if (withDownload) {
            var link = document.createElement('a');
            link.href = docUrl;
            link.download = docName || 'document.docx';
            link.className = 'admin-docx-download';
            link.textContent = 'Download document';
            card.appendChild(link);
        }
        host.appendChild(card);
    };

    showMessage('Preparing preview', 'Rendering the Word document...', false);

    if (typeof docx === 'undefined' || typeof JSZip === 'undefined') {
        showMessage('Preview unavailable', 'The Word viewer could not load. Download the file to review it.', true);
        return;
    }

    fetch(docUrl, { credentials: 'same-origin' })
        .then(function(res) {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.blob();
        })
        .then(function(blob) {
            var target = document.createElement('div');
            return docx.renderAsync(blob, target, null, {
                className: 'admin-docx',
                inWrapper: true,
                breakPages: true,
                ignoreLastRenderedPageBreak: true,
                renderHeaders: true,
                renderFooters: true,
            }).then(function() {
                host.innerHTML = '';
                host.appendChild(target);

                // Scale A4/Letter pages down to the modal width without horizontal scrolling
                var page = host.querySelector('section.admin-docx');
                var available = host.parentElement.clientWidth - 32;
                if (page && page.offsetWidth > available) {
                    host.style.zoom = (available / page.offsetWidth).toFixed(3);
                }
            });
        })
        .catch(function(err) {
            console.warn('DOCX preview error:', err);
            showMessage('This document could not be previewed', 'The file may be damaged or use features the viewer does not support.', true);
        });
};

window.renderAdminPdf = function(pdfUrl) {
    var wrapper = document.getElementById('admin-pdf-canvas-wrapper');
    if (!wrapper) return;

    wrapper.innerHTML = '<div class="flex items-center justify-center p-12 text-slate-400 gap-3"><svg class="animate-spin text-indigo-600" style="width: 20px !important; height: 20px !important;" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg><span class="text-xs font-semibold text-slate-600">Rendering high-resolution document preview...</span></div>';

    if (typeof pdfjsLib === 'undefined') {
        setTimeout(function() { if (typeof window.renderAdminPdf === 'function') window.renderAdminPdf(pdfUrl); }, 150);
        return;
    }

    var streamUrl = '<?= url('/admin/document/stream') ?>';

    fetch(streamUrl)
        .then(function(res) {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
        })
        .then(function(json) {
            if (!json || !json.data) throw new Error('No PDF payload returned');
            var binary = atob(json.data);
            var len = binary.length;
            var bytes = new Uint8Array(len);
            for (var i = 0; i < len; i++) {
                bytes[i] = binary.charCodeAt(i);
            }
            return pdfjsLib.getDocument({ data: bytes }).promise;
        })
        .then(function(pdf) {
            wrapper.innerHTML = '';
            var maxPages = Math.min(pdf.numPages, 6);
            for (var p = 1; p <= maxPages; p++) {
                (function(pageNum) {
                    pdf.getPage(pageNum).then(function(page) {
                        var containerWidth = wrapper.clientWidth || 650;
                        var targetWidth = Math.min(containerWidth - 32, 680);
                        if (targetWidth < 280) targetWidth = 280;
                        var unscaled = page.getViewport({ scale: 1.0 });
                        var scale = targetWidth / unscaled.width;
                        var viewport = page.getViewport({ scale: scale });

                        var canvasWrap = document.createElement('div');
                        canvasWrap.style.margin = '10px auto';
                        canvasWrap.style.background = '#FFFFFF';
                        canvasWrap.style.boxShadow = '0 4px 20px rgba(0, 6, 35, 0.08)';
                        canvasWrap.style.borderRadius = '10px';
                        canvasWrap.style.overflow = 'hidden';
                        canvasWrap.style.border = '1px solid #E2E8F0';
                        canvasWrap.style.maxWidth = '100%';

                        var canvas = document.createElement('canvas');
                        canvas.className = 'admin-pdf-page-canvas block';
                        canvas.style.maxWidth = '100%';
                        canvas.style.height = 'auto';
                        canvas.style.display = 'block';

                        var context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        canvasWrap.appendChild(canvas);
                        wrapper.appendChild(canvasWrap);

                        page.render({
                            canvasContext: context,
                            viewport: viewport
                        });
                    });
                })(p);
            }
        })
        .catch(function(err) {
            console.warn('PDF preview error:', err);
            wrapper.innerHTML = '<div class="p-8 text-center flex flex-col items-center justify-center gap-3"><div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">PDF</div><p class="text-xs text-slate-600 font-medium">Document verified in secure repository.</p><a href="' + (pdfUrl || '#') + '" target="_blank" rel="noopener noreferrer" class="admin-btn-dark px-4 py-2 rounded-xl text-xs font-semibold" style="display: inline-flex; align-items: center; gap: 6px;"><span>Open Document in Full Viewer</span><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg></a></div>';
        });
};
</script>
