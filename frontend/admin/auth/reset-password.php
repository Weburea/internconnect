<?php
declare(strict_types=1);
/**
 * Admin Console Authentication View — Screen 1.4: Password Reset Execution
 * 
 * @var string|null $error Optional validation error message
 * @var string|null $token Cryptographically signed token from query string / email
 * @var string|null $email Staff email address
 * @var string|null $csrf_token Security CSRF token
 */
$safeEmail = htmlspecialchars($email ?? 'admin@internconnect.ng', ENT_QUOTES, 'UTF-8');
$safeToken = htmlspecialchars($token ?? '', ENT_QUOTES, 'UTF-8');
?>
<div class="admin-auth-wrapper" x-data="{
    password: '',
    passwordConfirmation: '',
    showPassword: false,
    showConfirmPassword: false,
    isLoading: false,
    get hasMinLength() { return this.password.length >= 10; },
    get hasUppercase() { return /[A-Z]/.test(this.password); },
    get hasNumber() { return /[0-9]/.test(this.password); },
    get hasSymbol() { return /[^A-Za-z0-9]/.test(this.password); },
    get score() {
        let s = 0;
        if (this.hasMinLength) s++;
        if (this.hasUppercase) s++;
        if (this.hasNumber) s++;
        if (this.hasSymbol) s++;
        return s;
    },
    get isStrong() { return this.score === 4; },
    get passwordsMatch() { return this.password.length > 0 && this.password === this.passwordConfirmation; },
    get canSubmit() { return this.isStrong && this.passwordsMatch; },
    generateStrongPassword() {
        let pass = 'P@ss' + Math.floor(1000 + Math.random() * 9000) + 'X#';
        this.password = pass;
        this.passwordConfirmation = pass;
        const p1 = document.getElementById('new-password');
        const p2 = document.getElementById('confirm-password');
        if (p1) { p1.value = pass; p1.dispatchEvent(new Event('input')); }
        if (p2) { p2.value = pass; p2.dispatchEvent(new Event('input')); }
        if (typeof window.adminToast === 'function') {
            window.adminToast('Strong administrative password generated', '', null, 'info');
        }
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
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>

        <h1 class="admin-auth-title">Set New Password</h1>
        <p class="admin-auth-sub">Create a strong, verified password for your administrative staff account</p>

        <!-- Immediate Revocation Notice -->
        <div class="admin-auth-notice warning">
            <svg class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <strong>Immediate Session Revocation:</strong> Resetting your password will immediately terminate all active sessions across all devices.
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

        <!-- Form -->
        <form action="<?= url('/admin/reset-password') ?>" method="POST" @submit="isLoading = true" class="space-y-4">
            <!-- CSRF Token & Signed Recovery Token -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
            <input type="hidden" name="token" value="<?= $safeToken ?>">
            <input type="hidden" name="email" value="<?= $safeEmail ?>">

            <!-- New Security Password -->
            <div>
                <label for="new-password" class="admin-auth-label">New Security Password</label>
                <div class="admin-auth-input-wrap">
                    <div class="admin-auth-input-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input :type="showPassword ? 'text' : 'password'" 
                           id="new-password" 
                           name="password" 
                           x-model="password" 
                           required 
                           autofocus
                           placeholder="Enter at least 10 strong characters"
                           class="admin-auth-input"
                           style="padding-right: 42px;">
                    <button type="button" 
                            @click="showPassword = !showPassword" 
                            class="admin-auth-input-toggle" 
                            aria-label="Toggle password visibility">
                        <svg x-show="!showPassword" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg x-show="showPassword" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Dynamic Live Password Strength Meter -->
            <div class="admin-strength-wrap">
                <!-- 4 Segmented Progress Bars -->
                <div class="admin-strength-bars">
                    <div class="admin-strength-bar" 
                         :class="{
                             'bg-emerald-500': score === 4,
                             'bg-indigo-500': score === 3,
                             'bg-amber-500': score === 2,
                             'bg-rose-500': score === 1,
                             'bg-slate-200': score === 0
                         }"></div>
                    <div class="admin-strength-bar" 
                         :class="{
                             'bg-emerald-500': score === 4,
                             'bg-indigo-500': score === 3,
                             'bg-amber-500': score >= 2,
                             'bg-slate-200': score < 2
                         }"></div>
                    <div class="admin-strength-bar" 
                         :class="{
                             'bg-emerald-500': score === 4,
                             'bg-indigo-500': score >= 3,
                             'bg-slate-200': score < 3
                         }"></div>
                    <div class="admin-strength-bar" 
                         :class="{
                             'bg-emerald-500': score === 4,
                             'bg-slate-200': score < 4
                         }"></div>
                </div>

                <!-- 4 Rule Check Items -->
                <div class="admin-strength-rules">
                    <div class="admin-strength-rule" :class="{ 'met': hasMinLength }">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Min 10 characters</span>
                    </div>
                    <div class="admin-strength-rule" :class="{ 'met': hasUppercase }">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Uppercase letter</span>
                    </div>
                    <div class="admin-strength-rule" :class="{ 'met': hasNumber }">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>At least 1 number</span>
                    </div>
                    <div class="admin-strength-rule" :class="{ 'met': hasSymbol }">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Special symbol (@#$%&*)</span>
                    </div>
                </div>
            </div>

            <!-- Confirm Security Password -->
            <div>
                <label for="confirm-password" class="admin-auth-label">Confirm Security Password</label>
                <div class="admin-auth-input-wrap">
                    <div class="admin-auth-input-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <input :type="showConfirmPassword ? 'text' : 'password'" 
                           id="confirm-password" 
                           name="password_confirmation" 
                           x-model="passwordConfirmation" 
                           required 
                           placeholder="Re-type your new security password"
                           class="admin-auth-input"
                           style="padding-right: 42px;">
                    <button type="button" 
                            @click="showConfirmPassword = !showConfirmPassword" 
                            class="admin-auth-input-toggle" 
                            aria-label="Toggle password visibility">
                        <svg x-show="!showConfirmPassword" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg x-show="showConfirmPassword" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                        </svg>
                    </button>
                </div>
                <div x-show="passwordConfirmation.length > 0" class="mt-1.5 text-[11.5px] font-medium" style="display: none;">
                    <span x-show="passwordsMatch" class="text-emerald-600 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Passwords match
                    </span>
                    <span x-show="!passwordsMatch" class="text-rose-600 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Passwords do not match yet
                    </span>
                </div>
            </div>

            <!-- Submit Button (Always Has Visible Text) -->
            <div class="pt-2">
                <button type="submit" 
                        :disabled="isLoading || !canSubmit" 
                        class="admin-auth-btn"
                        id="admin-reset-submit">
                    <svg x-show="isLoading" class="animate-spin w-4 h-4 text-white flex-shrink-0" fill="none" viewBox="0 0 24 24" style="display: none;">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-show="!isLoading">Update Password & Sign In</span>
                    <span x-show="isLoading" style="display: none;">Updating password...</span>
                    <svg x-show="!isLoading" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>
        </form>

        <!-- Dev Helper Pill: Generate Compliant Password -->
        <div class="mt-6 p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-3 text-xs">
            <div class="flex items-center gap-2 text-slate-700 min-w-0">
                <img src="<?= asset('assets/images/jenny_ai/jenny_icon.png') ?>" alt="Jenny AI" class="w-4 h-4 rounded-full flex-shrink-0">
                <span class="truncate">Generate Compliant Admin Password</span>
            </div>
            <button type="button" @click="generateStrongPassword()" class="text-indigo-600 hover:text-indigo-800 font-bold whitespace-nowrap text-[11px] underline flex-shrink-0 cursor-pointer">
                Generate
            </button>
        </div>

        <!-- Back to Staff Login (With Generous Top Margin & Padding Separator) -->
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
        <p class="text-[11px] text-slate-400">Password change will be broadcasted to all logged-in devices for immediate session termination.</p>
    </div>
</div>
