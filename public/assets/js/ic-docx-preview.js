/**
 * Shared Word (DOCX) preview for InternConnect pages (application resume, employer candidate documents).
 * Same method as the admin document viewer: docx-preview + JSZip render the file to HTML in the page.
 * Requires assets/js/jszip.min.js and assets/js/docx-preview.min.js loaded first.
 */
// Render a Word file (File/Blob or URL) into host, scaled to fit the width (same approach as the admin viewer).
window.icRenderDocx = function (source, host, name) {
    if (!host) return;
    var key = typeof source === 'string' ? source : (source && source.name + ':' + source.size);
    if (host.dataset.docxKey === key && host.childElementCount) return;
    host.dataset.docxKey = key;
    var message = function (title, detail) {
        host.style.zoom = '';
        host.innerHTML = '<div class="ic-docx-msg"><strong></strong><span></span></div>';
        host.querySelector('strong').textContent = title;
        host.querySelector('span').textContent = detail;
    };
    if (typeof docx === 'undefined' || typeof JSZip === 'undefined') { message('Preview unavailable', 'The Word viewer could not load. Your file is still attached.'); return; }
    message('Preparing preview', 'Rendering ' + (name || 'your document') + '...');
    var load = typeof source === 'string'
        ? fetch(source, { credentials: 'same-origin' }).then(function (r) { if (!r.ok) throw new Error('HTTP ' + r.status); return r.blob(); })
        : Promise.resolve(source);
    load.then(function (blob) {
        var target = document.createElement('div');
        return docx.renderAsync(blob, target, null, { className: 'docx', inWrapper: true, breakPages: true, ignoreLastRenderedPageBreak: true }).then(function () {
            host.innerHTML = '';
            host.appendChild(target);
            var page = host.querySelector('section.docx');
            var available = (host.parentElement ? host.parentElement.clientWidth : host.clientWidth) - 28;
            host.style.zoom = page && available > 0 && page.offsetWidth > available ? (available / page.offsetWidth).toFixed(3) : '';
        });
    }).catch(function () {
        host.dataset.docxKey = '';
        message('This document could not be previewed', 'It may use features the viewer does not support. It will still be submitted.');
    });
};
