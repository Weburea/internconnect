<?php
declare(strict_types=1);
/**
 * Shared Component: Toast Notifications
 * Accessible globally via Alpine.js event dispatching.
 */
?>
<div x-data="{
    show: false,
    type: 'success',
    text: '',
    timer: null,
    init() {
        // Listen to global 'show-toast' browser event
        window.addEventListener('show-toast', (e) => {
            this.trigger(e.detail.type || 'success', e.detail.text || e.detail.message || '');
        });
        // Also support backward compatibility with 'toast' events
        window.addEventListener('toast', (e) => {
            this.trigger(e.detail.type || 'success', e.detail.message || e.detail.text || '');
        });
    },
    trigger(type, text) {
        this.type = type;
        this.text = text;
        this.show = true;
        
        if (this.timer) {
            clearTimeout(this.timer);
        }
        
        this.timer = setTimeout(() => {
            this.show = false;
        }, 4000);
    }
}"
class="db-toast-container">
    <div x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-[-20px] scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-[-20px] scale-95"
         class="db-toast-card"
         :class="'db-toast-' + type"
         @click="show = false"
         role="alert"
         aria-live="assertive">
         
        <!-- Icon Wrapper based on Toast Type -->
        <div class="db-toast-icon-box">
            <!-- Success Icon -->
            <template x-if="type === 'success'">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="9" />
                    <path class="animated-check-path" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />
                </svg>
            </template>
            <!-- Warning Icon -->
            <template x-if="type === 'warning'">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </template>
            <!-- Danger/Error Icon -->
            <template x-if="type === 'danger' || type === 'error'">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </template>
            <!-- Info Icon -->
            <template x-if="type === 'info'">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </template>
        </div>

        <!-- Toast Text Content -->
        <div class="db-toast-text" x-text="text"></div>

        <!-- Close Button -->
        <button class="db-toast-close-btn" aria-label="Close notification">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>
