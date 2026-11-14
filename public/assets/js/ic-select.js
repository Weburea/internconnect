/**
 * InternConnect custom dropdown (replaces the browser's default <select> list).
 *
 * Usage: <select data-ic-select> ... </select>            light (default)
 *        <select data-ic-select="dark"> ... </select>     dark surfaces (video room)
 *
 * The real <select> stays in the DOM (hidden) so forms, x-model and @change keep working:
 * choosing an option sets select.value and dispatches "input" + "change". Options are read
 * from the select every time the list opens, so x-for / dynamic options are supported.
 * The trigger copies the select's classes, so it keeps each page's existing field styling.
 * Keyboard: Enter/Space/ArrowDown open, arrows move, Enter selects, Escape closes, type to jump.
 * Rule: .agents/rules/rules.md "Dropdown / Select Rule".
 */
(function () {
    if (window.IcSelect) return;

    var css = [
        '.ic-sel{position:relative;display:block;width:100%;min-width:0}',
        '.ic-sel>select{position:absolute!important;inset:0!important;width:100%!important;height:100%!important;opacity:0!important;pointer-events:none!important}',
        '.ic-sel-btn{display:flex!important;align-items:center;gap:8px;width:100%;text-align:left;cursor:pointer;font:inherit}',
        '.ic-sel-btn .ic-sel-label{flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}',
        '.ic-sel-btn .ic-sel-caret{width:14px;height:14px;flex-shrink:0;opacity:.7;transition:transform .15s ease}',
        '.ic-sel.is-open .ic-sel-caret{transform:rotate(180deg)}',
        '.ic-sel.has-own-caret .ic-sel-caret{display:none}',
        '.ic-sel-list{position:absolute;left:0;right:0;top:calc(100% + 6px);z-index:4000;margin:0;padding:6px;list-style:none;background:#FFFFFF;border:1px solid #E2E8F0;border-radius:12px;box-shadow:0 16px 36px -12px rgba(15,23,42,.22);max-height:260px;overflow-y:auto;min-width:180px}',
        '.ic-sel.drop-up .ic-sel-list{top:auto;bottom:calc(100% + 6px)}',
        '.ic-sel-opt{display:flex;align-items:center;gap:8px;padding:9px 10px;border-radius:8px;font-size:13px;font-weight:600;color:#334155;cursor:pointer;line-height:1.35}',
        '.ic-sel-opt span{flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}',
        '.ic-sel-opt svg{width:14px;height:14px;flex-shrink:0;color:#4F48E6;visibility:hidden}',
        '.ic-sel-opt.is-active{background:#F5F7FF;color:#0F172A}',
        '.ic-sel-opt.is-selected{color:#4F48E6}',
        '.ic-sel-opt.is-selected svg{visibility:visible}',
        '.ic-sel-opt.is-disabled{opacity:.45;cursor:not-allowed}',
        '.ic-sel.is-dark .ic-sel-list{background:#111832;border-color:rgba(255,255,255,.12);box-shadow:0 18px 40px -12px rgba(0,0,0,.6)}',
        '.ic-sel.is-dark .ic-sel-opt{color:#E2E8F0}',
        '.ic-sel.is-dark .ic-sel-opt.is-active{background:rgba(99,102,241,.18);color:#FFFFFF}',
        '.ic-sel.is-dark .ic-sel-opt.is-selected{color:#A5B4FC}',
        '.ic-sel.is-dark .ic-sel-opt svg{color:#A5B4FC}',
    ].join('');
    var style = document.createElement('style');
    style.setAttribute('data-ic-select-style', '');
    style.textContent = css;
    document.head.appendChild(style);

    var CHECK = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';
    var CARET = '<svg class="ic-sel-caret" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>';
    var uid = 0;
    var all = [];

    function enhance(select) {
        if (select.__icSel) return;
        var wrap = document.createElement('div');
        wrap.className = 'ic-sel' + (select.getAttribute('data-ic-select') === 'dark' ? ' is-dark' : '');
        // A sibling caret icon placed by the page (e.g. video room) is kept instead of ours.
        var parent = select.parentNode;
        if (parent && parent.querySelector(':scope > svg[class*="caret"], :scope > svg.vr-select-caret')) wrap.classList.add('has-own-caret');
        parent.insertBefore(wrap, select);
        wrap.appendChild(select);
        select.tabIndex = -1;
        select.setAttribute('aria-hidden', 'true');

        var id = 'ic-sel-' + (++uid);
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = (select.className ? select.className + ' ' : '') + 'ic-sel-btn';
        btn.setAttribute('aria-haspopup', 'listbox');
        btn.setAttribute('aria-expanded', 'false');
        btn.setAttribute('aria-controls', id);
        var label = select.getAttribute('aria-label') || (select.id && document.querySelector('label[for="' + select.id + '"]') ? document.querySelector('label[for="' + select.id + '"]').textContent.trim() : '');
        if (label) btn.setAttribute('aria-label', label);
        if (select.id) { btn.id = select.id + '-btn'; var lbl = document.querySelector('label[for="' + select.id + '"]'); if (lbl) lbl.setAttribute('for', btn.id); }
        btn.innerHTML = '<span class="ic-sel-label"></span>' + CARET;
        wrap.appendChild(btn);

        var list = document.createElement('ul');
        list.className = 'ic-sel-list';
        list.id = id;
        list.setAttribute('role', 'listbox');
        list.hidden = true;
        wrap.appendChild(list);

        var state = { select: select, wrap: wrap, btn: btn, list: list, open: false, active: -1, opts: [], lastValue: null, lastText: null, typed: '', typedAt: 0 };
        select.__icSel = state;
        all.push(state);

        function readOptions() {
            state.opts = Array.prototype.slice.call(select.options).map(function (o) { return { value: o.value, text: o.textContent.trim(), disabled: o.disabled }; });
        }
        function render() {
            list.innerHTML = '';
            state.opts.forEach(function (o, i) {
                var li = document.createElement('li');
                li.className = 'ic-sel-opt' + (o.value === select.value ? ' is-selected' : '') + (i === state.active ? ' is-active' : '') + (o.disabled ? ' is-disabled' : '');
                li.setAttribute('role', 'option');
                li.id = id + '-o' + i;
                li.setAttribute('aria-selected', o.value === select.value ? 'true' : 'false');
                li.innerHTML = '<span></span>' + CHECK;
                li.firstChild.textContent = o.text;
                li.addEventListener('mousedown', function (e) { e.preventDefault(); });
                li.addEventListener('click', function () { if (!o.disabled) choose(i); });
                li.addEventListener('mousemove', function () { if (state.active !== i) { state.active = i; render(); } });
                list.appendChild(li);
            });
            if (state.active >= 0) btn.setAttribute('aria-activedescendant', id + '-o' + state.active); else btn.removeAttribute('aria-activedescendant');
        }
        function openList() {
            if (select.disabled) return;
            all.forEach(function (s) { if (s !== state && s.open) s.close(); });
            readOptions();
            state.active = Math.max(0, state.opts.findIndex(function (o) { return o.value === select.value; }));
            state.open = true;
            wrap.classList.add('is-open');
            list.hidden = false;
            btn.setAttribute('aria-expanded', 'true');
            render();
            var rect = btn.getBoundingClientRect();
            wrap.classList.toggle('drop-up', window.innerHeight - rect.bottom < 280 && rect.top > 280);
            var act = list.children[state.active];
            if (act) act.scrollIntoView({ block: 'nearest' });
        }
        function close() {
            state.open = false;
            wrap.classList.remove('is-open');
            list.hidden = true;
            btn.setAttribute('aria-expanded', 'false');
        }
        state.close = close;
        function choose(i) {
            var o = state.opts[i];
            if (!o) return;
            var changed = select.value !== o.value;
            select.value = o.value;
            if (changed) {
                select.dispatchEvent(new Event('input', { bubbles: true }));
                select.dispatchEvent(new Event('change', { bubbles: true }));
            }
            sync();
            close();
            btn.focus();
        }
        function move(delta) {
            var n = state.opts.length;
            if (!n) return;
            var i = state.active;
            for (var k = 0; k < n; k++) {
                i = (i + delta + n) % n;
                if (!state.opts[i].disabled) break;
            }
            state.active = i;
            render();
            var el = list.children[i];
            if (el) el.scrollIntoView({ block: 'nearest' });
        }
        btn.addEventListener('click', function () { state.open ? close() : openList(); });
        btn.addEventListener('keydown', function (e) {
            if (!state.open && ['ArrowDown', 'ArrowUp', 'Enter', ' '].indexOf(e.key) !== -1) { e.preventDefault(); openList(); return; }
            if (!state.open) return;
            if (e.key === 'ArrowDown') { e.preventDefault(); move(1); }
            else if (e.key === 'ArrowUp') { e.preventDefault(); move(-1); }
            else if (e.key === 'Home') { e.preventDefault(); state.active = -1; move(1); }
            else if (e.key === 'End') { e.preventDefault(); state.active = 0; move(-1); }
            else if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); choose(state.active); }
            else if (e.key === 'Escape' || e.key === 'Tab') { if (e.key === 'Escape') { e.preventDefault(); e.stopPropagation(); } close(); }
            else if (e.key.length === 1) {
                var now = Date.now();
                state.typed = (now - state.typedAt < 700 ? state.typed : '') + e.key.toLowerCase();
                state.typedAt = now;
                var hit = state.opts.findIndex(function (o) { return o.text.toLowerCase().indexOf(state.typed) === 0; });
                if (hit >= 0) { state.active = hit; render(); }
            }
        });
        document.addEventListener('mousedown', function (e) { if (state.open && !wrap.contains(e.target)) close(); });
        select.addEventListener('change', sync);

        function sync() {
            var opt = select.options[select.selectedIndex];
            var text = opt ? opt.textContent.trim() : '';
            if (text === state.lastText && select.value === state.lastValue && btn.disabled === select.disabled) return;
            state.lastText = text;
            state.lastValue = select.value;
            btn.querySelector('.ic-sel-label').textContent = text || ' ';
            btn.disabled = select.disabled;
            if (state.open) { readOptions(); render(); }
        }
        state.sync = sync;
        sync();
    }

    function scan(root) {
        (root || document).querySelectorAll('select[data-ic-select]').forEach(enhance);
    }

    // x-model and x-for change the select without events: keep the trigger label in step.
    setInterval(function () {
        all = all.filter(function (s) { return document.contains(s.select); });
        all.forEach(function (s) { s.sync(); });
        scan();
    }, 300);

    window.IcSelect = { enhance: enhance, scan: scan };
    document.addEventListener('DOMContentLoaded', function () { scan(); });
    document.addEventListener('alpine:initialized', function () { scan(); });
})();
