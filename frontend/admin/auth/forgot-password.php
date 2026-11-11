<?php
declare(strict_types=1);
/**
 * Admin Console Authentication View — Screen 1.3: Password Recovery Request
 * 
 * @var string|null $error Optional validation error message
 * @var string|null $success Optional flash success message
 * @var bool $sent Whether the recovery link has been sent
 * @var string|null $email Staff email address
 * @var string|null $token Simulated recovery token for dev environment
 * @var string|null $csrf_token Security CSRF token
 */
$safeEmail = htmlspecialchars($email ?? 'admin@internconnect.ng', ENT_QUOTES, 'UTF-8');
?>
<div class="admin-auth-wrapper" x-data="{
    email: '<?= $safeEmail ?>',
    isLoading: false,
    useDemo() {
        this.email = 'admin@internconnect.ng';
        const el = document.getElementById('admin-recovery-email');
        if (el) { el.value = 'admin@internconnect.ng'; el.dispatchEvent(new Event('input')); }
        if (typeof window.adminToast === 'function') {
            window.adminToast('Demo staff email populated', 'admin@internconnect.ng', null, 'info');
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
        <?php if (!empty($sent)): ?>
            <!-- Success State: Recovery Link Dispatched -->
            <div class="admin-auth-badge" style="background-color: #ECFDF5; border-color: #D1FAE5; color: #059669;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>

            <h1 class="admin-auth-title">Recovery Link Dispatched</h1>
            <p class="admin-auth-sub">A cryptographically signed password reset link has been dispatched to your verified staff email</p>

            <div class="admin-auth-notice success">
                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    Sent to: <strong><?= htmlspecialchars($email ?? 'admin@internconnect.ng') ?></strong>.<br>
                    This link expires in <strong>60 minutes</strong>.
                </div>
            </div>

            <!-- Demo Dev Direct Link Helper -->
            <?php if (!empty($token)): ?>
                <div class="mb-5 p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-1.5">Dev Environment Test Link</span>
                    <a href="<?= url('/admin/reset-password?token=' . urlencode($token) . '&email=' . urlencode($email ?? 'admin@internconnect.ng')) ?>" 
                       class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 break-all underline flex items-center gap-1.5">
                        <span>Simulate clicking reset link in staff inbox &rarr;</span>
                    </a>
                </div>
            <?php endif; ?>

            <div class="pt-2">
                <a href="<?= url('/admin/login') ?>" class="admin-auth-btn">
                    <span>Return to Staff Login</span>
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>

        <?php else: ?>
            <!-- Initial State: Work Email Request Form -->
            <div class="admin-auth-badge">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>

            <h1 class="admin-auth-title">Reset Admin Password</h1>
            <p class="admin-auth-sub">Enter your verified staff email and we'll send you a signed 1-hour recovery link</p>

            <!-- Rate Limiting Notice -->
            <div class="admin-auth-notice info">
                <svg class="w-4 h-4 text-indigo-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    Recovery links are cryptographically signed with HMAC-SHA256 and expire strictly after 1 hour.
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
            <form action="<?= url('/admin/forgot-password') ?>" method="POST" @submit="isLoading = true" class="space-y-4">
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">

                <!-- Staff Work Email -->
                <div>
                    <label for="admin-recovery-email" class="admin-auth-label">Staff Work Email</label>
                    <div class="admin-auth-input-wrap">
                        <div class="admin-auth-input-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input type="email" 
                               id="admin-recovery-email" 
                               name="email" 
                               x-model="email" 
                               value="<?= $safeEmail ?>"
                               required 
                               autofocus
                               placeholder="staff.name@internconnect.ng"
                               class="admin-auth-input">
                    </div>
                </div>

                <!-- Submit Button (Always Has Visible Text) -->
                <div class="pt-2">
                    <button type="submit" 
                            :disabled="isLoading" 
                            class="admin-auth-btn"
                            id="admin-forgot-submit">
                        <svg x-show="isLoading" class="animate-spin w-4 h-4 text-white flex-shrink-0" fill="none" viewBox="0 0 24 24" style="display: none;">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-show="!isLoading">Send Recovery Link</span>
                        <span x-show="isLoading" style="display: none;">Dispatching signed link...</span>
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
                    <span class="truncate">Dev Test Access: <strong>admin@internconnect.ng</strong></span>
                </div>
                <button type="button" @click="useDemo()" class="text-indigo-600 hover:text-indigo-800 font-bold whitespace-nowrap text-[11px] underline flex-shrink-0 cursor-pointer">
                    Autofill
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
        <?php endif; ?>
    </div>

    <!-- Security Audit Footer -->
    <div class="text-center mt-6 text-slate-400 text-xs">
        <p class="text-[11px] text-slate-400">All password reset requests are recorded in the security audit log with timestamp, IP, and user-agent.</p>
    </div>
</div>
