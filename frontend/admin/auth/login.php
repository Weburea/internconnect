<?php
declare(strict_types=1);
/**
 * Admin Console Authentication View — Screen 1.1: Staff Login
 * 
 * @var string|null $error Optional validation or lockout error message
 * @var string|null $success Optional flash success message
 * @var string|null $email Prefilled email
 * @var string|null $csrf_token Security CSRF token
 * @var bool $isLockedOut Whether the account/IP is locked out
 * @var int $lockoutRemaining Minutes remaining for lockout
 */
$safeEmail = htmlspecialchars($email ?? 'admin@internconnect.ng', ENT_QUOTES, 'UTF-8');
?>
<div class="admin-auth-wrapper" x-data="{
    email: '<?= $safeEmail ?>',
    password: '',
    showPassword: false,
    remember: true,
    isLoading: false,
    useDemo() {
        this.email = 'admin@internconnect.ng';
        this.password = 'password123';
        const elEmail = document.getElementById('admin-email');
        const elPass = document.getElementById('admin-password');
        if (elEmail) { elEmail.value = 'admin@internconnect.ng'; elEmail.dispatchEvent(new Event('input')); }
        if (elPass) { elPass.value = 'password123'; elPass.dispatchEvent(new Event('input')); }
        if (typeof window.adminToast === 'function') {
            window.adminToast('Demo credentials populated', 'admin@internconnect.ng', null, 'info');
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

        <h1 class="admin-auth-title">Staff Login</h1>
        <p class="admin-auth-sub">Enter your verified administrative credentials</p>

        <!-- Rate Limiting Notice -->
        <div class="admin-auth-notice info">
            <svg class="w-4 h-4 text-indigo-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <div>
                <strong>Rate Limiting Protection:</strong> 5 attempts maximum before a strict 15-minute administrative lockout.
            </div>
        </div>

        <!-- Optional Error / Lockout Banner -->
        <?php if (!empty($error)): ?>
            <div class="admin-auth-notice danger">
                <svg class="w-4 h-4 text-rose-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div><?= htmlspecialchars($error) ?></div>
            </div>
        <?php endif; ?>

        <!-- Optional Success Banner -->
        <?php if (!empty($success)): ?>
            <div class="admin-auth-notice success">
                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <div><?= htmlspecialchars($success) ?></div>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="<?= url('/admin/login') ?>" method="POST" @submit="isLoading = true" class="space-y-4">
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">

            <!-- Staff Email Address -->
            <div>
                <label for="admin-email" class="admin-auth-label">Staff Work Email</label>
                <div class="admin-auth-input-wrap">
                    <div class="admin-auth-input-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <input type="email" 
                           id="admin-email" 
                           name="email" 
                           x-model="email" 
                           value="<?= $safeEmail ?>"
                           required 
                           autofocus
                           placeholder="staff.name@internconnect.ng"
                           class="admin-auth-input">
                </div>
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="admin-password" class="admin-auth-label mb-0">Security Password</label>
                    <a href="<?= url('/admin/forgot-password') ?>" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">Forgot password?</a>
                </div>
                <div class="admin-auth-input-wrap">
                    <div class="admin-auth-input-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input :type="showPassword ? 'text' : 'password'" 
                           id="admin-password" 
                           name="password" 
                           x-model="password" 
                           required 
                           placeholder="Enter your security password"
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

            <!-- Modern Rounded Checklist / Remember Device Card -->
            <div class="pt-1">
                <div class="admin-checkbox-card" @click="remember = !remember">
                    <input type="checkbox" name="remember" x-model="remember" @click.stop class="admin-checkbox">
                    <span class="text-xs text-slate-700 font-medium">Keep session active on this device (30 Days)</span>
                </div>
            </div>

            <!-- Submit Button (Always Has Visible Name) -->
            <div class="pt-2">
                <button type="submit" 
                        :disabled="isLoading <?= (!empty($isLockedOut) ? '|| true' : '') ?>" 
                        class="admin-auth-btn"
                        id="admin-login-submit">
                    <svg x-show="isLoading" class="animate-spin w-4 h-4 text-white flex-shrink-0" fill="none" viewBox="0 0 24 24" style="display: none;">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-show="!isLoading">Sign In to Admin Console</span>
                    <span x-show="isLoading" style="display: none;">Authenticating credentials...</span>
                    <svg x-show="!isLoading" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>
        </form>

        <!-- Dev Test Access Helper Pill with Direct Autofill -->
        <div class="mt-6 p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-3 text-xs">
            <div class="flex items-center gap-2 text-slate-700 min-w-0">
                <img src="<?= asset('assets/images/jenny_ai/jenny_icon.png') ?>" alt="Jenny AI" class="w-4 h-4 rounded-full flex-shrink-0">
                <span class="truncate">Dev Test Access: <strong>admin@internconnect.ng</strong></span>
            </div>
            <button type="button" @click="useDemo()" class="text-indigo-600 hover:text-indigo-800 font-bold whitespace-nowrap text-[11px] underline flex-shrink-0 cursor-pointer">
                Autofill
            </button>
        </div>
    </div>

    <!-- Security Audit Footer -->
    <div class="text-center mt-6 text-slate-400 text-xs space-y-1">
        <div class="flex items-center justify-center gap-1.5 font-medium">
            <svg class="w-3.5 h-3.5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="text-slate-600 font-semibold">Encrypted administrative session (TLS 1.3 · CSRF Protected)</span>
        </div>
        <p class="text-[11px] text-slate-400">All sign-in attempts and administrative operations are audited and cryptographically signed.</p>
    </div>
</div>
