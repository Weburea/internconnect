<?php
declare(strict_types=1);
/**
 * Global Admin Command Palette Modal (Ctrl + K Overlay)
 * Complies with Section 2.3 of docs/10-admin-system.md
 * 
 * Features:
 * - Instant system-wide search across Interns, Employers, Courses, and Quick Actions
 * - Keyboard navigation (Up/Down arrow keys, Enter to navigate, Escape to dismiss)
 * - Jenny AI natural language prompt detection
 * - Category filter tabs
 * - Mobile responsive with zero horizontal overflow
 */
?>
<div x-data="adminCommandPalette()" 
     x-cloak
     x-show="isOpen" 
     @open-admin-command-palette.window="openPalette()"
     @keydown.window="handleGlobalKeydown($event)"
     class="fixed inset-0 z-50 flex items-start justify-center p-3 sm:p-6 pt-12 sm:pt-20"
     style="display: none;"
     role="dialog"
     aria-modal="true"
     aria-label="Admin Command Palette">

    <!-- Modal Backdrop -->
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="closePalette()"
         class="fixed inset-0 bg-[#000623]/50 backdrop-blur-sm"
         aria-hidden="true"></div>

    <!-- Centered Floating Dialog -->
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
         @click.outside="closePalette()"
         class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col z-10"
         style="max-height: calc(100vh - 80px);">

        <!-- Header / Search Input -->
        <div class="flex items-center gap-3 px-4 py-3.5 border-b border-slate-100 bg-white">
            <!-- Jenny AI Chip -->
            <div class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 flex-shrink-0" title="Jenny AI Enabled">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
            </div>

            <!-- Input -->
            <input type="text"
                   x-ref="paletteInput"
                   x-model="searchQuery"
                   @input="selectedIndex = 0"
                   @keydown.down.prevent="navigateDown()"
                   @keydown.up.prevent="navigateUp()"
                   @keydown.enter.prevent="selectActive()"
                   @keydown.escape.prevent="closePalette()"
                   placeholder="Ask Jenny or search interns, employers, courses, actions..."
                   class="flex-1 min-w-0 bg-transparent text-sm sm:text-base text-slate-900 placeholder-slate-400 focus:outline-none font-medium border-0 p-0"
                   aria-autocomplete="list"
                   aria-controls="cmd-results-list">

            <!-- ESC badge -->
            <button type="button" 
                    @click="closePalette()" 
                    class="px-2 py-1 text-[11px] font-semibold text-slate-400 hover:text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-md transition-colors flex items-center gap-1 flex-shrink-0" 
                    title="Close palette (Esc)">
                <span>ESC</span>
            </button>
        </div>

        <!-- Category Filter Tabs -->
        <div class="flex items-center gap-1.5 px-4 py-2 border-b border-slate-100 bg-slate-50/80 overflow-x-auto text-xs font-semibold scrollbar-none">
            <template x-for="cat in categories" :key="cat.id">
                <button type="button"
                        @click="activeCategory = cat.id; selectedIndex = 0;"
                        :class="activeCategory === cat.id ? 'bg-white text-indigo-700 shadow-sm border border-slate-200' : 'text-slate-500 hover:text-slate-800 border border-transparent'"
                        class="px-2.5 py-1 rounded-md transition-all whitespace-nowrap flex-shrink-0 flex items-center gap-1.5">
                    <span x-text="cat.label"></span>
                    <span class="text-[10px] px-1.5 py-0.2 rounded-full font-bold"
                          :class="activeCategory === cat.id ? 'bg-indigo-50 text-indigo-600' : 'bg-slate-200/60 text-slate-500'"
                          x-text="getCategoryCount(cat.id)"></span>
                </button>
            </template>
        </div>

        <!-- Scrollable Results Container -->
        <div id="cmd-results-list" class="flex-1 overflow-y-auto p-2 divide-y divide-slate-100" style="max-height: 420px;" role="listbox">
            
            <!-- Jenny AI Natural Language Assistant Prompter (when typing questions) -->
            <template x-if="searchQuery.trim().length > 2 && (searchQuery.toLowerCase().startsWith('ask') || searchQuery.toLowerCase().startsWith('how') || searchQuery.toLowerCase().startsWith('what') || searchQuery.toLowerCase().startsWith('show') || searchQuery.toLowerCase().startsWith('who'))">
                <div class="p-2 mb-2 rounded-xl bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-100 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-7 h-7 rounded-lg bg-indigo-600 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs font-bold text-indigo-950 truncate">Ask Jenny AI Assistant</div>
                            <div class="text-[11px] text-indigo-700 truncate" x-text="'&ldquo;' + searchQuery + '&rdquo;'"></div>
                        </div>
                    </div>
                    <button type="button" 
                            @click="executeJennyQuery()"
                            class="px-2.5 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-xs font-bold transition-colors flex-shrink-0 whitespace-nowrap">
                        Execute &rarr;
                    </button>
                </div>
            </template>

            <!-- Empty State -->
            <template x-if="flattenedResults.length === 0">
                <div class="py-12 px-4 text-center">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">No matching records found</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">No candidate, employer, course, or route matched "<span class="font-semibold text-slate-700" x-text="searchQuery"></span>". Try another search keyword or ask Jenny.</p>
                </div>
            </template>

            <!-- Results List -->
            <template x-for="(item, idx) in flattenedResults" :key="item.id || item.title + idx">
                <div @click="activateItem(item)"
                     @mouseenter="selectedIndex = idx"
                     :class="selectedIndex === idx ? 'bg-indigo-50/70 border-indigo-200 text-indigo-950' : 'hover:bg-slate-50 text-slate-800 border-transparent'"
                     class="group flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-xl cursor-pointer transition-all border my-0.5"
                     role="option"
                     :aria-selected="selectedIndex === idx">
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <!-- Icon Circle -->
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold flex-shrink-0"
                             :class="{
                                 'bg-blue-100 text-blue-700': item.type === 'intern',
                                 'bg-purple-100 text-purple-700': item.type === 'employer',
                                 'bg-emerald-100 text-emerald-700': item.type === 'course',
                                 'bg-slate-100 text-slate-700': item.type === 'action'
                             }">
                            <template x-if="item.type === 'intern'">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </template>
                            <template x-if="item.type === 'employer'">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </template>
                            <template x-if="item.type === 'course'">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                                </svg>
                            </template>
                            <template x-if="item.type === 'action'">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </template>
                        </div>

                        <!-- Labels -->
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <span class="text-xs sm:text-sm font-bold truncate" :class="selectedIndex === idx ? 'text-indigo-900' : 'text-slate-900'" x-text="item.title"></span>
                                <template x-if="item.meta">
                                    <span class="text-[10px] px-1.5 py-0.5 rounded font-semibold bg-slate-100 text-slate-600 whitespace-nowrap" x-text="item.meta"></span>
                                </template>
                            </div>
                            <div class="text-[11px] sm:text-xs text-slate-500 truncate" x-text="item.subtitle"></div>
                        </div>
                    </div>

                    <!-- Right Tag / Shortcut -->
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <span class="text-[10.5px] font-semibold px-2 py-0.5 rounded-full whitespace-nowrap"
                              :class="{
                                  'bg-blue-50 text-blue-700': item.category === 'interns',
                                  'bg-purple-50 text-purple-700': item.category === 'employers',
                                  'bg-emerald-50 text-emerald-700': item.category === 'courses',
                                  'bg-slate-100 text-slate-700': item.category === 'actions'
                              }"
                              x-text="item.tag"></span>
                        <svg class="w-4 h-4 text-slate-300 transition-colors"
                             :class="selectedIndex === idx ? 'text-indigo-600 translate-x-0.5' : ''"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </template>
        </div>

        <!-- Footer Shortcuts -->
        <div class="flex items-center justify-between px-4 py-2.5 bg-slate-50 border-t border-slate-100 text-[11px] text-slate-500">
            <div class="flex items-center gap-3">
                <span class="flex items-center gap-1">
                    <kbd class="px-1.5 py-0.5 rounded bg-white border border-slate-200 text-[10px] font-mono text-slate-600 shadow-2xs">&uarr;&darr;</kbd>
                    <span class="hidden sm:inline">Navigate</span>
                </span>
                <span class="flex items-center gap-1">
                    <kbd class="px-1.5 py-0.5 rounded bg-white border border-slate-200 text-[10px] font-mono text-slate-600 shadow-2xs">&crarr;</kbd>
                    <span class="hidden sm:inline">Select</span>
                </span>
                <span class="flex items-center gap-1">
                    <kbd class="px-1.5 py-0.5 rounded bg-white border border-slate-200 text-[10px] font-mono text-slate-600 shadow-2xs">Esc</kbd>
                    <span class="hidden sm:inline">Close</span>
                </span>
            </div>
            <div class="flex items-center gap-1.5 text-indigo-600 font-semibold">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <span>Jenny AI Mission Control</span>
            </div>
        </div>
    </div>
</div>

<script>
function adminCommandPalette() {
    return {
        isOpen: false,
        searchQuery: '',
        selectedIndex: 0,
        activeCategory: 'all',
        categories: [
            { id: 'all', label: 'All Items' },
            { id: 'interns', label: 'Interns' },
            { id: 'employers', label: 'Employers' },
            { id: 'courses', label: 'Courses' },
            { id: 'actions', label: 'Quick Actions' }
        ],
        masterItems: [
            // Group 1: Interns
            { id: 'int-1', type: 'intern', category: 'interns', title: 'Sarah Peters', subtitle: 'University of Lagos · UX/UI & Product Design', meta: '92 Match', tag: 'Candidate', url: '<?= url('/admin/interns') ?>' },
            { id: 'int-2', type: 'intern', category: 'interns', title: 'Tunde Adeyemi', subtitle: 'University of Ibadan · Backend Engineering', meta: '88 Match', tag: 'Candidate', url: '<?= url('/admin/interns') ?>' },
            { id: 'int-3', type: 'intern', category: 'interns', title: 'Amara Nwosu', subtitle: 'Covenant University · Product Management', meta: '90 Match', tag: 'Candidate', url: '<?= url('/admin/interns') ?>' },
            { id: 'int-4', type: 'intern', category: 'interns', title: 'David Okon', subtitle: 'FUTA · Data Science & AI Systems', meta: '84 Match', tag: 'Candidate', url: '<?= url('/admin/interns') ?>' },
            { id: 'int-5', type: 'intern', category: 'interns', title: 'Fatima Bello', subtitle: 'Ahmadu Bello University · Frontend Engineering', meta: '91 Match', tag: 'Candidate', url: '<?= url('/admin/interns') ?>' },
            
            // Group 2: Employers
            { id: 'emp-1', type: 'employer', category: 'employers', title: 'Nexura Technologies', subtitle: 'Fintech · Lagos · 14 Open Roles', meta: 'CAC Pending', tag: 'Company', url: '<?= url('/admin/employers?tab=pending') ?>' },
            { id: 'emp-2', type: 'employer', category: 'employers', title: 'Kiro Labs', subtitle: 'Design & AI · Remote / Lagos · 4 Roles', meta: 'Rechecking API', tag: 'Company', url: '<?= url('/admin/employers?tab=pending') ?>' },
            { id: 'emp-3', type: 'employer', category: 'employers', title: 'Moniepoint Microfinance', subtitle: 'Banking & Fintech · 12 Open Roles', meta: 'Verified', tag: 'Company', url: '<?= url('/admin/employers') ?>' },
            { id: 'emp-4', type: 'employer', category: 'employers', title: 'PaySprint Global', subtitle: 'Financial Services · Abuja · CAC Under Audit', meta: 'Unreadable RC', tag: 'Company', url: '<?= url('/admin/employers?tab=pending') ?>' },
            { id: 'emp-5', type: 'employer', category: 'employers', title: 'DesignHub Creative', subtitle: 'Product Design · Lagos · 3 Roles', meta: 'Verified', tag: 'Company', url: '<?= url('/admin/employers') ?>' },
            
            // Group 3: Courses
            { id: 'crs-1', type: 'course', category: 'courses', title: 'UX Research & Prototyping Basics', subtitle: 'E-Learning · 320 enrolled · Mentor Syllabus Submission', meta: '3 Pending', tag: 'Course', url: '<?= url('/admin/elearning?tab=pending') ?>' },
            { id: 'crs-2', type: 'course', category: 'courses', title: 'Python for Data Analysis & APIs', subtitle: 'E-Learning · 540 enrolled · Published Curriculum', meta: 'Live', tag: 'Course', url: '<?= url('/admin/elearning') ?>' },
            { id: 'crs-3', type: 'course', category: 'courses', title: 'Design Handoff & Developer Collaboration', subtitle: 'E-Learning · 1,240 enrolled · Top Rated', meta: 'Published', tag: 'Course', url: '<?= url('/admin/elearning') ?>' },
            
            // Group 4: Quick Actions & Navigation
            { id: 'act-1', type: 'action', category: 'actions', title: 'Mission Control Overview', subtitle: 'High-level platform KPI metrics, charts & attention triage', meta: '/admin', tag: 'Route', url: '<?= url('/admin/overview') ?>' },
            { id: 'act-2', type: 'action', category: 'actions', title: 'Verify 14 Pending Employers (CAC Desk)', subtitle: 'Open Corporate Affairs Commission registration verification queue', meta: '14 Queued', tag: 'Action', url: '<?= url('/admin/employers?tab=pending') ?>' },
            { id: 'act-3', type: 'action', category: 'actions', title: 'Review 6 Flagged AI Matches', subtitle: 'Inspect algorithmic score anomalies and tune Gemini weights', meta: '6 Flags', tag: 'AI Ops', url: '<?= url('/admin/ai') ?>' },
            { id: 'act-4', type: 'action', category: 'actions', title: 'Review 3 Mentor Course Submissions', subtitle: 'Inspect submitted modules, video syllabuses, and publish', meta: '3 Courses', tag: 'Moderation', url: '<?= url('/admin/elearning') ?>' },
            { id: 'act-5', type: 'action', category: 'actions', title: 'Subscriptions & Paystack Ledger', subtitle: 'Monitor monthly recurring revenue (MRR) and transaction logs', meta: 'Paystack', tag: 'Finance', url: '<?= url('/admin/subscriptions') ?>' },
            { id: 'act-6', type: 'action', category: 'actions', title: 'Watching Staff & Role Permissions', subtitle: 'Configure support representative roles and view audit logs', meta: 'RBAC', tag: 'Settings', url: '<?= url('/admin/settings/team') ?>' },
            { id: 'act-7', type: 'action', category: 'actions', title: 'Sign Out of Staff Console', subtitle: 'End administrative session securely', meta: 'Auth', tag: 'Logout', url: '<?= url('/admin/logout') ?>' }
        ],
        getCategoryCount(catId) {
            if (catId === 'all') return this.masterItems.length;
            return this.masterItems.filter(i => i.category === catId).length;
        },
        get flattenedResults() {
            let list = this.masterItems;
            if (this.activeCategory !== 'all') {
                list = list.filter(i => i.category === this.activeCategory);
            }
            if (!this.searchQuery.trim()) {
                return list;
            }
            const q = this.searchQuery.toLowerCase().trim();
            return list.filter(item => {
                return item.title.toLowerCase().includes(q) ||
                       item.subtitle.toLowerCase().includes(q) ||
                       (item.meta && item.meta.toLowerCase().includes(q)) ||
                       item.tag.toLowerCase().includes(q);
            });
        },
        openPalette() {
            this.isOpen = true;
            this.searchQuery = '';
            this.selectedIndex = 0;
            document.body.style.overflow = 'hidden';
            this.$nextTick(() => {
                if (this.$refs.paletteInput) {
                    this.$refs.paletteInput.focus();
                }
            });
        },
        closePalette() {
            this.isOpen = false;
            document.body.style.overflow = '';
        },
        handleGlobalKeydown(e) {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                if (this.isOpen) {
                    this.closePalette();
                } else {
                    this.openPalette();
                }
            } else if (e.key === 'Escape' && this.isOpen) {
                this.closePalette();
            }
        },
        navigateDown() {
            const total = this.flattenedResults.length;
            if (total === 0) return;
            this.selectedIndex = (this.selectedIndex + 1) % total;
            this.scrollActiveIntoView();
        },
        navigateUp() {
            const total = this.flattenedResults.length;
            if (total === 0) return;
            this.selectedIndex = (this.selectedIndex - 1 + total) % total;
            this.scrollActiveIntoView();
        },
        scrollActiveIntoView() {
            this.$nextTick(() => {
                const list = document.getElementById('cmd-results-list');
                const active = list ? list.querySelector('[aria-selected="true"]') : null;
                if (list && active) {
                    const activeRect = active.getBoundingClientRect();
                    const listRect = list.getBoundingClientRect();
                    if (activeRect.bottom > listRect.bottom) {
                        list.scrollTop += (activeRect.bottom - listRect.bottom) + 8;
                    } else if (activeRect.top < listRect.top) {
                        list.scrollTop -= (listRect.top - activeRect.top) + 8;
                    }
                }
            });
        },
        selectActive() {
            const items = this.flattenedResults;
            if (items.length > 0 && items[this.selectedIndex]) {
                this.activateItem(items[this.selectedIndex]);
            } else if (this.searchQuery.trim().length > 0) {
                this.executeJennyQuery();
            }
        },
        activateItem(item) {
            this.closePalette();
            if (item.url && item.url !== '#') {
                window.location.href = item.url;
            } else {
                if (typeof window.adminToast === 'function') {
                    window.adminToast('Executing action: ' + item.title, item.subtitle, null, 'info');
                }
            }
        },
        executeJennyQuery() {
            const query = this.searchQuery;
            this.closePalette();
            if (typeof window.adminToast === 'function') {
                window.adminToast('Jenny AI analyzing query...', '"' + query + '"', null, 'info');
            }
            setTimeout(() => {
                window.location.href = '<?= url('/admin/ai/chat') ?>?query=' + encodeURIComponent(query);
            }, 500);
        }
    };
}
</script>

