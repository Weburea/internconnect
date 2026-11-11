<?php
declare(strict_types=1);
/**
 * Admin Console Authentication View — Screen 1.2: Two-Factor Verification Challenge
 * 
 * @var string|null $error Optional validation error message
 * @var string|null $success Optional flash success message
 * @var string|null $email Staff email address
 * @var string|null $csrf_token Security CSRF token
 */
$safeEmail = htmlspecialchars($email ?? 'admin@internconnect.ng', ENT_QUOTES, 'UTF-8');
?>
<div class="admin-auth-wrapper" x-data="{
    digits: ['', '', '', '', '', ''],
    rememberDevice: true,
    isLoading: false,
    emailDispatched: false,
    demoCode: '849201',
    handleInput(index, event) {
        const val = event.target.value.replace(/\D/g, '');
        if (val.length > 0) {
            this.digits[index] = val.slice(-1);
            if (index < 5) {
                const nextEl = document.getElementById('totp-' + (index + 1));
                if (nextEl) nextEl.focus();
            }
        } else {
            this.digits[index] = '';
        }
    },
    handleKeyDown(index, event) {
        if (event.key === 'Backspace' && !this.digits[index] && index > 0) {
            const prevEl = document.getElementById('totp-' + (index - 1));
            if (prevEl) {
                prevEl.focus();
                this.digits[index - 1] = '';
            }
        }
    },
    handlePaste(event) {
        event.preventDefault();
        const text = (event.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
        if (text) {
            for (let i = 0; i < 6; i++) {
                this.digits[i] = text[i] || '';
            }
            const targetIndex = Math.min(text.length, 5);
            const targetEl = document.getElementById('totp-' + targetIndex);
            if (targetEl) targetEl.focus();
        }
    },
    dispatchEmailCode() {
        this.emailDispatched = true;
        for (let i = 0; i < 6; i++) {
            this.digits[i] = this.demoCode[i] || '';
        }
        if (typeof window.adminToast === 'function') {
            window.adminToast('One-time code dispatched to email', 'Demo Code: 849201', null, 'info');
        }
    },
    fillDemo() {
        for (let i = 0; i < 6; i++) {
            this.digits[i] = this.demoCode[i] || '';
        }
        const lastEl = document.getElementById('totp-5');
        if (lastEl) lastEl.focus();
    }
}">
    <!-- Brand Header -->
    <div class="text-center mb-6">
        <a href="<?= url('/') ?>" class="inline-flex items-center gap-2.5 mb-3 group">
            <img src="<?= asset('assets/images/logos/Logo_single_dark_dj6vhf.png') ?>" alt="InternConnect" class="h-9 w-auto">
            <span class="text-xl font-extrabold text-[#000623] tracking-tight">InternConnect</span>
            <span class="ml-1 px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-indigo-50 text-indigo-600 border border-indigo-100 uppercase tracking-wider">Console</span>
        </a>
    </div>

    <!-- Main Auth Card -->
    <div class="admin-auth-card">
        <!-- Shield Badge Icon -->
        <div class="admin-auth-badge">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 004 11a7.962 7.962 0 001.077 4.022M9 11a3 3 0 106 0 3 3 0 00-6 0z"/>
            </svg>
        </div>

        <h1 class="admin-auth-title">Two-Factor Challenge</h1>
        <p class="admin-auth-sub">Enter the 6-digit TOTP security code from your authenticator application</p>

        <!-- Target Account Notice -->
        <div class="admin-auth-notice info">
            <svg class="w-4 h-4 text-indigo-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <div class="truncate">
                Authenticating staff account: <strong><?= $safeEmail ?></strong>
            </div>
        </div>

        <!-- Dispatched Email Code Alert -->
        <div x-show="emailDispatched" class="admin-auth-notice success" style="display: none;">
            <svg class="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <div>
                One-time code dispatched to work inbox. (Demo code: <strong>849201</strong>)
            </div>
        </div>

        <!-- Optional Error Banner -->
        <?php if (!empty($error)): ?>
            <div class="admin-auth-notice danger">
                <svg class="w-4 h-4 text-rose-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div><?= htmlspecialchars($error) ?></div>
            </div>
        <?php endif; ?>

        <!-- 2FA Form -->
        <form action="<?= url('/admin/login/2fa') ?>" method="POST" @submit="isLoading = true">
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
            <input type="hidden" name="totp_code" :value="digits.join('')">

            <!-- 6-Digit TOTP Input Grid -->
            <div class="admin-totp-grid" @paste="handlePaste($event)">
                <template x-for="(digit, index) in digits" :key="index">
                    <input type="text" 
                           :id="'totp-' + index" 
                           :name="'digit_' + index"
                           x-model="digits[index]" 
                           maxlength="1" 
                           inputmode="numeric" 
                           autocomplete="off" 
                           :autofocus="index === 0"
                           @input="handleInput(index, $event)" 
                           @keydown="handleKeyDown(index, $event)" 
                           class="admin-totp-input"
                           aria-label="'Verification digit ' + (index + 1)">
                </template>
            </div>

            <!-- Fallback Email Dispatch Trigger -->
            <div class="text-center mb-5">
                <button type="button" 
                        @click="dispatchEmailCode()" 
                        class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors inline-flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span>Dispatch one-time code via verified staff email</span>
                </button>
            </div>

            <!-- Modern Rounded Checklist / Remember Device Card -->
            <div class="mb-5">
                <div class="admin-checkbox-card" @click="rememberDevice = !rememberDevice">
                    <input type="checkbox" name="remember_device" x-model="rememberDevice" @click.stop class="admin-checkbox">
                    <span class="text-xs text-slate-700 font-medium">Remember this device for 30 days (Trusted Session Token)</span>
                </div>
            </div>

            <!-- Submit Button (Always Has Visible Text) -->
            <div>
                <button type="submit" 
                        :disabled="isLoading || digits.join('').length !== 6" 
                        class="admin-auth-btn"
                        id="admin-2fa-submit">
                    <svg x-show="isLoading" class="animate-spin w-4 h-4 text-white flex-shrink-0" fill="none" viewBox="0 0 24 24" style="display: none;">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-show="!isLoading">Verify & Enter Console</span>
                    <span x-show="isLoading" style="display: none;">Validating 2FA token...</span>
                    <svg x-show="!isLoading" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>
        </form>

        <!-- Dev Test Access Helper Pill -->
        <div class="mt-6 p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-3 text-xs">
            <div class="flex items-center gap-2 text-slate-700 min-w-0">
                <img src="<?= asset('assets/images/jenny_ai/jenny_icon.png') ?>" alt="Jenny AI" class="w-4 h-4 rounded-full flex-shrink-0">
                <span class="truncate">Demo TOTP Code: <strong>849201</strong></span>
            </div>
            <button type="button" @click="fillDemo()" class="text-indigo-600 hover:text-indigo-800 font-bold whitespace-nowrap text-[11px] underline flex-shrink-0 cursor-pointer">
                Autofill
            </button>
        </div>

        <!-- Back to Sign In Link (With Generous Top Margin & Padding Separator) -->
        <div class="text-center mt-7 pt-4 border-t border-slate-100">
            <a href="<?= url('/admin/login') ?>" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Back to Staff Login</span>
            </a>
        </div>
    </div>

    <!-- Security Audit Footer -->
    <div class="text-center mt-6 text-slate-400 text-xs">
        <p class="text-[11px] text-slate-400">Two-Factor Authentication is mandatory for all administrative access pursuant to Section 4.2 Security Standards.</p>
    </div>
</div>
