<?php
declare(strict_types=1);
/**
 * Admin Operations Settings & Watching Staff Directory View
 * Complies with Section 9.0 of docs/10-admin-system.md
 * 
 * Features:
 * - Anti-collision mobile-scrollable staff directory table (min-width 760px)
 * - Standardized Custom Alpine Dropdown (.db-dropdown-wrap) for operational role selection
 * - Interactive Invite Staff Member Modal with live dynamic table addition
 * - Working Edit Role & Permissions Modal for existing staff members
 * - Live Toast feedback and zero forbidden red buttons
 * 
 * @var array  $staff     Staff profile data
 * @var string $activeNav Navigation identifier
 * @var array  $team      List of administrative staff members
 */
?>
<div class="admin-shell" x-data="adminSettingsTeam()">
    <!-- Sidebar Navigation -->
    <?php include dirname(__DIR__, 2) . '/shared/components/admin-sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="admin-main-wrap">
        <!-- Sticky Top Navigation -->
        <?php include dirname(__DIR__, 2) . '/shared/components/admin-topnav.php'; ?>

        <!-- Page Body -->
        <main class="admin-content-body">
            <style>
            .admin-team-header-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                width: 100%;
                margin-bottom: 24px;
                box-sizing: border-box;
            }
            .admin-team-header-btn-wrap {
                margin-left: auto;
                flex-shrink: 0;
            }
            @media (max-width: 640px) {
                .admin-team-header-row {
                    flex-direction: column !important;
                    align-items: stretch !important;
                    gap: 14px !important;
                }
                .admin-team-header-btn-wrap {
                    margin-left: 0 !important;
                    width: 100% !important;
                }
                .admin-team-header-btn-wrap button {
                    width: 100% !important;
                    justify-content: center !important;
                }
            }
            </style>

            <!-- Page Header -->
            <div class="admin-team-header-row">
                <div style="min-width: 0;">
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Watching Staff &amp; Team Roles</h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Manage Customer Care Specialists, Verification Officers, and role-based access control (RBAC)</p>
                </div>
                <div class="admin-team-header-btn-wrap">
                    <button type="button" 
                            @click="openInviteModal()" 
                            class="admin-btn-indigo text-xs px-4 py-2 cursor-pointer flex items-center gap-2 whitespace-nowrap"
                            style="white-space: nowrap !important; flex-shrink: 0 !important; display: inline-flex !important; align-items: center !important; gap: 8px !important;">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Invite Staff Member</span>
                    </button>
                </div>
            </div>

            <!-- Role Permissions Overview Cards (Clean 1px neutral borders, NO side borders) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="admin-kpi-card">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-900 uppercase tracking-wider">Super Administrator</span>
                        <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center font-bold text-xs border border-purple-100">
                            SA
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed mt-1">Full system governance, financial ledger, Paystack keys, and staff role assignment.</p>
                    <div class="text-[11px] font-semibold text-purple-700 mt-auto pt-2 border-t border-slate-100 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-purple-600"></span>
                        <span>Tier 1 Unrestricted</span>
                    </div>
                </div>

                <div class="admin-kpi-card">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-900 uppercase tracking-wider">Verification Officer</span>
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center font-bold text-xs border border-blue-100">
                            VO
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed mt-1">Approves CAC documents, audits candidate academic records, and flags suspicious accounts.</p>
                    <div class="text-[11px] font-semibold text-blue-700 mt-auto pt-2 border-t border-slate-100 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                        <span>Compliance &amp; KYC Desk</span>
                    </div>
                </div>

                <div class="admin-kpi-card">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-900 uppercase tracking-wider">Customer Care Specialist</span>
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs border border-slate-200">
                            CS
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed mt-1">Frontline support, assists users with 2FA/login, inspects candidate pipelines without financial access.</p>
                    <div class="text-[11px] font-semibold text-slate-600 mt-auto pt-2 border-t border-slate-100 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                        <span>"Watching Staff" Operational Tier</span>
                    </div>
                </div>
            </div>

            <!-- Staff Team Directory Table (Anti-Collision Min-Width 760px) -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Administrative Staff Directory</h2>
                        <p class="text-xs text-slate-500">Active platform moderators, role assignments, and session status</p>
                    </div>
                    <span class="admin-badge-pill admin-badge-slate whitespace-nowrap self-start sm:self-auto" x-text="members.length + ' Staff Members'"></span>
                </div>

                <!-- Scrollable Table Container (Isolated from horizontal blowout) -->
                <div class="admin-table-scroll-container">
                    <table class="admin-data-table text-xs" style="min-width: 760px;">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 uppercase tracking-wider font-semibold text-[11px]">
                                <th style="width: 240px; min-width: 240px;">STAFF MEMBER</th>
                                <th style="width: 170px; min-width: 170px;">ASSIGNED ROLE</th>
                                <th style="width: 120px; min-width: 120px;">ACCOUNT STATUS</th>
                                <th style="width: 130px; min-width: 130px;">LAST ACTIVE</th>
                                <th style="width: 100px; min-width: 100px; text-align: right;">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <template x-for="member in members" :key="member.email">
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <!-- Staff Identity -->
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs flex-shrink-0" x-text="member.initials"></div>
                                            <div class="min-w-0">
                                                <div class="font-bold text-slate-900 truncate" x-text="member.name"></div>
                                                <div class="text-[11px] text-slate-500 truncate" x-text="member.email"></div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Role Badge -->
                                    <td class="whitespace-nowrap">
                                        <span class="admin-badge-pill whitespace-nowrap" :class="member.role_class" x-text="member.role"></span>
                                    </td>

                                    <!-- Status (Isolated column spacing) -->
                                    <td class="whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 font-medium text-emerald-600">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span x-text="member.status"></span>
                                        </span>
                                    </td>

                                    <!-- Last Active (Ample space, never squished) -->
                                    <td class="text-slate-500 whitespace-nowrap" x-text="member.last_active"></td>

                                    <!-- Edit Action -->
                                    <td class="text-right whitespace-nowrap">
                                        <button type="button" 
                                                class="text-indigo-600 hover:text-indigo-800 font-semibold cursor-pointer text-xs"
                                                @click="openEditModal(member)">
                                            Edit Role
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Standardized Custom Alpine Modal: Invite Staff Member -->
            <div x-show="showInviteModal"
                 x-cloak
                 class="fixed inset-0 z-50 flex items-center justify-center p-4"
                 style="display: none;"
                 role="dialog"
                 aria-modal="true"
                 aria-label="Invite Administrative Staff">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-[#000623]/50 backdrop-blur-sm" @click="showInviteModal = false"></div>

                <!-- Dialog Card -->
                <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl border border-slate-200 p-6 z-10"
                     @click.outside="showInviteModal = false">
                    <!-- Header -->
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Invite Administrative Staff</h3>
                                <p class="text-xs text-slate-500">Configure role permissions and access tier</p>
                            </div>
                        </div>
                        <button type="button" class="text-slate-400 hover:text-slate-700" @click="showInviteModal = false">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Form Body -->
                    <div class="space-y-4 text-xs">
                        <!-- Full Name -->
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Full Name</label>
                            <input type="text" 
                                   x-model="newStaff.name" 
                                   placeholder="e.g. Fatima Abubakar" 
                                   class="w-full text-xs px-3 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600">
                        </div>

                        <!-- Work Email -->
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Staff Work Email</label>
                            <input type="email" 
                                   x-model="newStaff.email" 
                                   placeholder="fatima.abubakar@internconnect.ng" 
                                   class="w-full text-xs px-3 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600">
                        </div>

                        <!-- Operational Role via Standardized Custom Alpine Dropdown (Rule K Compliant) -->
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Assigned Operational Role</label>
                            <div class="db-dropdown-wrap" x-data="{ open: false }" @click.outside="open = false">
                                <button type="button" 
                                        class="db-dropdown-btn" 
                                        @click="open = !open" 
                                        :class="{ 'active': open }">
                                    <span class="font-medium text-slate-900" x-text="newStaff.role"></span>
                                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <div class="db-dropdown-menu" x-show="open" x-transition style="display: none;">
                                    <template x-for="roleOption in roleOptions" :key="roleOption.id">
                                        <button type="button" 
                                                class="db-dropdown-item" 
                                                :class="{ 'is-active': newStaff.role === roleOption.title }"
                                                @click="newStaff.role = roleOption.title; newStaff.role_class = roleOption.class; open = false;">
                                            <div>
                                                <div class="font-bold text-xs" x-text="roleOption.title"></div>
                                                <div class="text-[11px] text-slate-500" x-text="roleOption.desc"></div>
                                            </div>
                                            <template x-if="newStaff.role === roleOption.title">
                                                <svg class="db-dropdown-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </template>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Role Permissions Summary Box (No boxy raw buttons) -->
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-[10.5px] uppercase font-bold text-slate-400 block mb-1">Role Permissions Scope</span>
                            <p class="text-xs text-slate-600 leading-relaxed" x-text="getSelectedRoleDesc()"></p>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="flex items-center justify-end gap-2 mt-6 pt-4 border-t border-slate-100">
                        <button type="button" class="admin-btn-dark-outline text-xs px-4 py-2 cursor-pointer" @click="showInviteModal = false">Cancel</button>
                        <button type="button" 
                                class="admin-btn-indigo text-xs px-4 py-2 cursor-pointer whitespace-nowrap" 
                                @click="submitInvite()">
                            Send Staff Invite &rarr;
                        </button>
                    </div>
                </div>
            </div>

            <!-- Standardized Custom Alpine Modal: Edit Existing Role -->
            <div x-show="showEditModal"
                 x-cloak
                 class="fixed inset-0 z-50 flex items-center justify-center p-4"
                 style="display: none;"
                 role="dialog"
                 aria-modal="true"
                 aria-label="Edit Staff Permissions">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-[#000623]/50 backdrop-blur-sm" @click="showEditModal = false"></div>

                <!-- Dialog Card -->
                <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl border border-slate-200 p-6 z-10"
                     @click.outside="showEditModal = false">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Edit Operational Role</h3>
                            <p class="text-xs text-slate-500" x-text="editingMember?.name + ' (' + editingMember?.email + ')'"></p>
                        </div>
                        <button type="button" class="text-slate-400 hover:text-slate-700" @click="showEditModal = false">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4 text-xs">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Operational Role</label>
                            <div class="db-dropdown-wrap" x-data="{ open: false }" @click.outside="open = false">
                                <button type="button" 
                                        class="db-dropdown-btn" 
                                        @click="open = !open" 
                                        :class="{ 'active': open }">
                                    <span class="font-medium text-slate-900" x-text="editingMember?.role"></span>
                                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <div class="db-dropdown-menu" x-show="open" x-transition style="display: none;">
                                    <template x-for="roleOption in roleOptions" :key="roleOption.id">
                                        <button type="button" 
                                                class="db-dropdown-item" 
                                                :class="{ 'is-active': editingMember?.role === roleOption.title }"
                                                @click="if (editingMember) { editingMember.role = roleOption.title; editingMember.role_class = roleOption.class; } open = false;">
                                            <div>
                                                <div class="font-bold text-xs" x-text="roleOption.title"></div>
                                                <div class="text-[11px] text-slate-500" x-text="roleOption.desc"></div>
                                            </div>
                                            <template x-if="editingMember?.role === roleOption.title">
                                                <svg class="db-dropdown-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </template>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Active State Toggle -->
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                            <div>
                                <span class="font-bold text-slate-900 block">Console Account Access</span>
                                <span class="text-[11px] text-slate-500">Enable or temporarily suspend staff login</span>
                            </div>
                            <button type="button" 
                                    @click="editingMember.status = editingMember.status === 'Active' ? 'Suspended' : 'Active'"
                                    class="px-2.5 py-1 rounded-md text-xs font-bold transition-colors cursor-pointer"
                                    :class="editingMember?.status === 'Active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-200 text-slate-700'">
                                <span x-text="editingMember?.status"></span>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 mt-6 pt-4 border-t border-slate-100">
                        <button type="button" class="admin-btn-dark-outline text-xs px-4 py-2 cursor-pointer" @click="showEditModal = false">Cancel</button>
                        <button type="button" class="admin-btn-indigo text-xs px-4 py-2 cursor-pointer whitespace-nowrap" @click="saveEditRole()">Save Changes</button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
function adminSettingsTeam() {
    return {
        showInviteModal: false,
        showEditModal: false,
        editingMember: null,
        newStaff: {
            name: '',
            email: '',
            role: 'Customer Care',
            role_class: 'admin-badge-slate'
        },
        roleOptions: [
            { id: 'sa', title: 'Platform Admin', class: 'admin-badge-purple', desc: 'Full unrestricted governance, finance & team control' },
            { id: 'vo', title: 'Verification Officer', class: 'admin-badge-blue', desc: 'Reviews CAC documents, audits student universities' },
            { id: 'cs', title: 'Customer Care', class: 'admin-badge-slate', desc: 'Frontline watching staff, assists with 2FA/login issues' },
            { id: 'ai', title: 'AI Operations', class: 'admin-badge-purple', desc: 'Reviews flagged AI matches & tunes Gemini parameters' },
            { id: 'fb', title: 'Finance & Billing', class: 'admin-badge-emerald', desc: 'Manages Paystack payments, MRR & refunds' }
        ],
        members: [
            { name: 'Jayden Okafor', email: 'jayden.okafor@internconnect.ng', role: 'Platform Admin', role_class: 'admin-badge-purple', initials: 'JO', status: 'Active', last_active: 'Active now' },
            { name: 'Adaeze Umeh', email: 'adaeze.umeh@internconnect.ng', role: 'Verification Officer', role_class: 'admin-badge-blue', initials: 'AU', status: 'Active', last_active: '15 mins ago' },
            { name: 'Chinedu Eze', email: 'chinedu.eze@internconnect.ng', role: 'Customer Care', role_class: 'admin-badge-slate', initials: 'CE', status: 'Active', last_active: '1 hour ago' },
            { name: 'Bolanle Alabi', email: 'bolanle.alabi@internconnect.ng', role: 'AI Operations', role_class: 'admin-badge-purple', initials: 'BA', status: 'Active', last_active: '3 hours ago' },
            { name: 'Tariq Bello', email: 'tariq.bello@internconnect.ng', role: 'Finance & Billing', role_class: 'admin-badge-emerald', initials: 'TB', status: 'Active', last_active: 'Yesterday' }
        ],
        getSelectedRoleDesc() {
            const match = this.roleOptions.find(r => r.title === this.newStaff.role);
            return match ? match.desc : 'Operational role permissions on the platform.';
        },
        openInviteModal() {
            this.newStaff = { name: '', email: '', role: 'Customer Care', role_class: 'admin-badge-slate' };
            this.showInviteModal = true;
        },
        submitInvite() {
            if (!this.newStaff.name.trim() || !this.newStaff.email.trim()) {
                if (typeof window.adminToast === 'function') {
                    window.adminToast('Missing information', 'Please enter staff name and work email', null, 'warning');
                }
                return;
            }

            const parts = this.newStaff.name.trim().split(' ');
            const initials = parts.length > 1 ? (parts[0][0] + parts[1][0]).toUpperCase() : parts[0].substring(0, 2).toUpperCase();

            this.members.push({
                name: this.newStaff.name.trim(),
                email: this.newStaff.email.trim(),
                role: this.newStaff.role,
                role_class: this.newStaff.role_class || 'admin-badge-slate',
                initials: initials,
                status: 'Active',
                last_active: 'Invited just now'
            });

            this.showInviteModal = false;
            if (typeof window.adminToast === 'function') {
                window.adminToast('Invitation dispatched', 'Cryptographically signed 24h setup link sent to ' + this.newStaff.email, null, 'success');
            }
        },
        openEditModal(member) {
            this.editingMember = JSON.parse(JSON.stringify(member));
            this.showEditModal = true;
        },
        saveEditRole() {
            if (!this.editingMember) return;
            const idx = this.members.findIndex(m => m.email === this.editingMember.email);
            if (idx !== -1) {
                this.members[idx].role = this.editingMember.role;
                this.members[idx].role_class = this.editingMember.role_class;
                this.members[idx].status = this.editingMember.status;
            }
            this.showEditModal = false;
            if (typeof window.adminToast === 'function') {
                window.adminToast('Staff role updated', 'Permissions updated for ' + this.editingMember.name, null, 'success');
            }
        }
    };
}
</script>
