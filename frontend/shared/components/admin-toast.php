<?php
declare(strict_types=1);
/**
 * Admin Toast Notification Component
 * Bottom-center dark pill with icon, message, detail line, optional Undo, auto-dismiss 4s.
 * Matches Reference Image 1.
 */
?>
<div x-data="{
    visible: false,
    message: '',
    detail: '',
    type: 'success',
    hasUndo: false,
    undoCallback: null,
    timer: null,
    show(msg, det = '', undo = null, type = 'success') {
        if (this.timer) clearTimeout(this.timer);
        this.message = msg;
        this.detail = det;
        this.type = type;
        this.hasUndo = typeof undo === 'function';
        this.undoCallback = undo;
        this.visible = true;
        this.timer = setTimeout(() => { this.visible = false; }, 4000);
    },
    executeUndo() {
        if (this.undoCallback) this.undoCallback();
        this.visible = false;
    }
}" 
x-init="
    $el.addEventListener('show-admin-toast', (e) => show(e.detail.message, e.detail.detail || '', e.detail.undo || null, e.detail.type || 'success'));
    window.addEventListener('show-toast', (e) => show(e.detail.text || e.detail.message || '', '', null, e.detail.type || 'success'));
    window.addEventListener('toast', (e) => show(e.detail.message || e.detail.text || '', '', null, e.detail.type || 'success'));
    window.adminToast = (msg, det = '', undo = null, type = 'success') => show(msg, det, undo, type);
"
class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 pointer-events-none transition-all duration-300"
style="display: none;"
x-show="visible"
x-transition:enter="transition ease-out duration-300"
x-transition:enter-start="opacity-0 translate-y-4 scale-95"
x-transition:enter-end="opacity-100 translate-y-0 scale-100"
x-transition:leave="transition ease-in duration-200"
x-transition:leave-start="opacity-100 translate-y-0 scale-100"
x-transition:leave-end="opacity-0 translate-y-4 scale-95">

    <div class="pointer-events-auto flex items-center gap-3 px-4 py-3 rounded-full shadow-2xl text-white text-xs font-medium"
         style="background: #000623; border: 1px solid rgba(255, 255, 255, 0.12); box-shadow: 0 10px 30px rgba(0, 6, 35, 0.5); min-width: 320px; max-width: 90vw;">
        
        <!-- Status Icon -->
        <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0"
             :class="{
                 'bg-emerald-500/20 text-emerald-400': type === 'success',
                 'bg-indigo-500/20 text-indigo-400': type === 'info',
                 'bg-amber-500/20 text-amber-400': type === 'warning',
                 'bg-rose-500/20 text-rose-400': type === 'danger'
             }">
            <template x-if="type === 'success'">
                <svg class="w-3.5 h-3.5" style="width: 14px !important; height: 14px !important; max-width: 14px !important; max-height: 14px !important; flex-shrink: 0 !important;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </template>
            <template x-if="type === 'info'">
                <svg class="w-3.5 h-3.5" style="width: 14px !important; height: 14px !important; max-width: 14px !important; max-height: 14px !important; flex-shrink: 0 !important;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </template>
            <template x-if="type === 'warning' || type === 'danger'">
                <svg class="w-3.5 h-3.5" style="width: 14px !important; height: 14px !important; max-width: 14px !important; max-height: 14px !important; flex-shrink: 0 !important;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </template>
        </div>

        <!-- Text Block -->
        <div class="flex-1 min-w-0 pr-2">
            <div class="font-semibold text-white truncate" x-text="message"></div>
            <div class="text-[11px] text-slate-400 truncate" x-show="detail" x-text="detail"></div>
        </div>

        <!-- Optional Undo Action -->
        <button type="button" 
                x-show="hasUndo" 
                @click="executeUndo()"
                class="px-2.5 py-1 text-xs font-bold text-indigo-400 hover:text-indigo-300 bg-white/5 hover:bg-white/10 rounded-md transition-colors flex-shrink-0"
                style="white-space: nowrap !important;">
            Undo
        </button>

        <!-- Dismiss button -->
        <button type="button" @click="visible = false" class="text-slate-500 hover:text-white p-1 transition-colors flex-shrink-0" aria-label="Dismiss notification">
            <svg class="w-4 h-4" style="width: 16px !important; height: 16px !important; max-width: 16px !important; max-height: 16px !important; flex-shrink: 0 !important;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>

