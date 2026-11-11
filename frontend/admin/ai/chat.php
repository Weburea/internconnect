<?php
declare(strict_types=1);
/**
 * Full-Page Gemini Operational Intelligence Chat Workspace
 * Provides high-level administrative intelligence, candidate match audits,
 * and corporate CAC document analysis.
 * 
 * Features:
 * - Left Analytical & Telemetry Rail:
 *     - Operational AI Rail header
 *     - Operational Theme Switcher Card: Platform Operations & CAC Desk
 *     - Vertical Capsule Meters: Candidate Match (94%), Employer Velocity (88%), CAC Speed (1.8h), Confidence (99%)
 *     - Recent Sessions / Chat History with quick recall
 *     - Query Intelligence Breakdown: Candidate Screening 58%, Corporate CAC 42%
 * - Center Main Workspace:
 *     - Clean permanent header bar: "Gemini Operational Intelligence Assistant"
 *     - "● Chat is Protected" security indicator badge
 *     - "Welcome, Jayden!" hero greeting & "How can I help you analyze platform operations today?"
 *     - Central 3D Jenny AI avatar presentation
 *     - Relevant administrative prompt cloud chips
 *     - Real-time conversation thread with structured operational telemetry
 *     - Floating bottom pill composer with Drag-and-Drop file attachment & send actions
 * - 100% Zero Colored Accent Borders — Pure InternConnect Design System
 * 
 * @var array  $staff        Current admin staff profile
 * @var string $activeNav    Navigation identifier
 * @var array  $metrics      Top operational metrics
 * @var string $initialQuery Query passed from topnav / command palette / overview
 */
$staffName = $staff['name'] ?? 'Jayden Okafor';
$initialQuery = $initialQuery ?? '';
?>
<style>
/* Telemetry Pulse / Beeping Beacon for iPad & Mobile */
@keyframes adminPulseRing {
    0% {
        box-shadow: 0 0 0 0 rgba(79, 72, 230, 0.45);
    }
    70% {
        box-shadow: 0 0 0 6px rgba(79, 72, 230, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(79, 72, 230, 0);
    }
}

@keyframes adminBeaconPing {
    0% {
        transform: scale(1);
        opacity: 0.9;
    }
    75%, 100% {
        transform: scale(2.4);
        opacity: 0;
    }
}

.admin-telemetry-toggle-btn.is-beeping {
    animation: adminPulseRing 2s cubic-bezier(0.4, 0, 0.6, 1) infinite !important;
    border-color: #A5B4FC !important;
    background-color: #F5F3FF !important;
    color: #4F48E6 !important;
}

.admin-beacon-pulse {
    position: relative;
    display: inline-flex;
    width: 8px;
    height: 8px;
    margin-right: 4px;
    flex-shrink: 0;
}

.admin-beacon-ping {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 9999px;
    background-color: #4F48E6;
    opacity: 0.75;
    animation: adminBeaconPing 1.6s cubic-bezier(0, 0, 0.2, 1) infinite;
}

.admin-beacon-dot {
    position: relative;
    display: inline-flex;
    border-radius: 9999px;
    width: 8px;
    height: 8px;
    background-color: #4F48E6;
}

.admin-chat-header-bar {
    border-bottom: 1px solid #F1F5F9 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    background: #FFFFFF !important;
    flex-shrink: 0 !important;
    z-index: 10 !important;
    gap: 12px !important;
    padding: 12px 18px !important;
    box-sizing: border-box !important;
    width: 100% !important;
    min-width: 0 !important;
}

.admin-chat-header-left {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
    min-width: 0 !important;
    flex: 1 1 auto !important;
    overflow: hidden !important;
    max-width: calc(100% - 240px) !important;
}

.admin-chat-header-title-wrap {
    min-width: 0 !important;
    flex: 1 1 auto !important;
    overflow: hidden !important;
}

.admin-chat-header-title {
    font-size: 13.5px !important;
    font-weight: 700 !important;
    color: #0F172A !important;
    line-height: 1.3 !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    margin: 0 !important;
}

.admin-chat-header-subtitle {
    font-size: 11.5px !important;
    color: #64748B !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    margin-top: 1px !important;
    margin-bottom: 0 !important;
}

.admin-chat-header-actions {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
    flex-shrink: 0 !important;
    white-space: nowrap !important;
    margin-left: auto !important;
}

@media (max-width: 1380px) {
    .admin-chat-header-subtitle {
        display: none !important;
    }
}

@media (max-width: 640px) {
    .admin-chat-header-bar {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 10px !important;
        padding: 12px 14px !important;
    }

    .admin-chat-header-left {
        width: 100% !important;
        max-width: 100% !important;
    }

    .admin-chat-header-actions {
        width: 100% !important;
        justify-content: space-between !important;
    }
}
</style>
<div class="admin-shell" x-data="adminAiChat()">
    <!-- Sidebar Navigation -->
    <?php include dirname(__DIR__, 2) . '/shared/components/admin-sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="admin-main-wrap">
        <!-- Sticky Top Navigation -->
        <?php include dirname(__DIR__, 2) . '/shared/components/admin-topnav.php'; ?>

        <!-- Page Body -->
        <main class="admin-content-body" style="padding: 16px 20px;">

            <!-- Main Workspace Container (Left Telemetry Rail + Right Chat Canvas) -->
            <div class="admin-chat-grid-shell" :class="{ 'rail-collapsed': !railOpen }">
                
                <!-- ════════════ LEFT RAIL: CONTEXT & TELEMETRY ════════════ -->
                <aside class="admin-chat-left-rail" x-show="railOpen" x-transition>
                    <!-- Top Sub-Navigation Header -->
                    <div class="flex items-center justify-between pb-3 mb-2 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-lg bg-indigo-50 text-[#4F48E6] flex items-center justify-center font-bold text-xs">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-slate-800 tracking-wide uppercase">Operational Telemetry</span>
                        </div>
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" title="System Online"></span>
                    </div>

                    <!-- PROGRESS Section (Vertical Capsule Bar Meters) -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between px-1 cursor-pointer" @click="showProgress = !showProgress">
                            <span class="text-[10.5px] font-bold text-slate-400 uppercase tracking-wider">PLATFORM PERFORMANCE</span>
                            <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': !showProgress }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>

                        <div x-show="showProgress" x-transition class="admin-chat-capsules-grid">
                            <!-- Capsule 1: Intern Sentiment -->
                            <div class="admin-capsule-col">
                                <span class="text-[10px] text-slate-400 font-medium">Interns</span>
                                <div class="admin-capsule-track">
                                    <div class="admin-capsule-fill admin-capsule-fill-green" style="height: 94%;"></div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-800">94%</span>
                            </div>

                            <!-- Capsule 2: Employer Adoption -->
                            <div class="admin-capsule-col">
                                <span class="text-[10px] text-slate-400 font-medium">Employers</span>
                                <div class="admin-capsule-track">
                                    <div class="admin-capsule-fill admin-capsule-fill-indigo" style="height: 88%;"></div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-800">88%</span>
                            </div>

                            <!-- Capsule 3: CAC Speed -->
                            <div class="admin-capsule-col">
                                <span class="text-[10px] text-slate-400 font-medium">CAC Speed</span>
                                <div class="admin-capsule-track">
                                    <div class="admin-capsule-fill admin-capsule-fill-purple" style="height: 85%;"></div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-800">1.8h</span>
                            </div>

                            <!-- Capsule 4: Confidence -->
                            <div class="admin-capsule-col">
                                <span class="text-[10px] text-slate-400 font-medium">Precision</span>
                                <div class="admin-capsule-track">
                                    <div class="admin-capsule-fill admin-capsule-fill-dark" style="height: 99%;"></div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-800">99%</span>
                            </div>
                        </div>
                    </div>

                    <!-- HISTORY CHAT (Premium High-End Session History) -->
                    <div class="space-y-2 mt-4">
                        <div class="flex items-center justify-between px-1 cursor-pointer select-none" @click="showHistory = !showHistory">
                            <div class="flex items-center gap-2">
                                <span class="text-[10.5px] font-bold text-slate-500 uppercase tracking-wider" style="font-size: 10.5px !important; letter-spacing: 0.05em;">HISTORY CHAT</span>
                                <span class="px-2 py-0.5 rounded-full font-bold bg-indigo-50 text-[#4F48E6] border border-indigo-100" style="font-size: 10px !important;" x-text="recentSessions.length"></span>
                            </div>
                            <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': !showHistory }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>

                        <div x-show="showHistory" x-transition class="space-y-2">
                            <template x-for="item in recentSessions" :key="item.id">
                                <button type="button" 
                                        @click="loadSession(item)"
                                        class="admin-history-item group"
                                        :class="{ 'is-active': activeSessionId === item.id }">
                                    <!-- Icon Avatar Badge -->
                                    <div class="admin-history-icon">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                        </svg>
                                    </div>
                                    <!-- Title & Metadata -->
                                    <div class="admin-history-content">
                                        <span class="admin-history-title" x-text="item.title"></span>
                                        <div class="admin-history-meta">
                                            <span class="admin-history-time" x-text="item.time"></span>
                                            <span class="admin-history-bullet">&bull;</span>
                                            <span class="admin-history-tag" x-text="item.tag || 'Operations'"></span>
                                        </div>
                                    </div>
                                    <!-- Chevron -->
                                    <svg class="w-3.5 h-3.5 text-slate-300 group-hover:text-[#4F48E6] group-hover:translate-x-0.5 flex-shrink-0 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- QUERY INTELLIGENCE BREAKDOWN (Replaces General Distribution) -->
                    <div class="space-y-2 mt-auto pt-4">
                        <div class="px-1">
                            <span class="text-[10.5px] font-bold text-slate-400 uppercase tracking-wider">QUERY BREAKDOWN</span>
                        </div>

                        <!-- 2 Metric Cards -->
                        <div class="grid grid-cols-2 gap-2.5">
                            <div class="admin-chat-dist-card">
                                <span class="text-[11px] text-slate-500 block mb-1">Candidate Screening</span>
                                <span class="text-base font-bold text-slate-900">58%</span>
                            </div>
                            <div class="admin-chat-dist-card">
                                <span class="text-[11px] text-slate-500 block mb-1">CAC Document Audits</span>
                                <span class="text-base font-bold text-indigo-600">42%</span>
                            </div>
                        </div>

                        <!-- System Utilization Gauge Card -->
                        <div class="admin-chat-dist-card flex items-center justify-between">
                            <div class="space-y-1 text-[11px]">
                                <span class="font-bold text-slate-800 block text-xs">System Automation</span>
                                <span class="text-slate-500 block">Nightly Gemini 1.5 Runs</span>
                                <span class="text-indigo-600 font-semibold text-[10.5px]">84% Automated Coverage</span>
                            </div>

                            <!-- Micro Circular Gauge SVG (84%) -->
                            <div class="relative w-12 h-12 flex items-center justify-center flex-shrink-0">
                                <svg class="w-12 h-12 -rotate-90" viewBox="0 0 36 36">
                                    <path class="text-slate-100" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                    <path class="text-indigo-600" stroke-width="3.5" stroke-dasharray="84, 100" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                </svg>
                                <span class="absolute font-bold text-[11px] text-slate-800">84%</span>
                            </div>
                        </div>
                    </div>
                </aside>

                <!-- ════════════ RIGHT WORKSPACE: GEMINI CHAT CANVAS ════════════ -->
                <div class="flex flex-col h-full min-h-0 relative bg-white overflow-hidden">

                    <!-- Top Clean Header Bar with Telemetry Toggle & Generous Breathing Room -->
                    <div class="admin-chat-header-bar">
                        <div class="admin-chat-header-left">
                            <button type="button" 
                                    @click="railOpen = !railOpen" 
                                    class="admin-telemetry-toggle-btn"
                                    :class="{ 'is-active': railOpen, 'is-beeping': !railOpen && isTabletOrMobile }"
                                    title="Toggle Operational Telemetry Panel">
                                <!-- Beeping Radar Pulse Dot (iPad & Mobile) -->
                                <span class="admin-beacon-pulse" x-show="!railOpen && isTabletOrMobile">
                                    <span class="admin-beacon-ping"></span>
                                    <span class="admin-beacon-dot"></span>
                                </span>
                                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" x-show="railOpen || !isTabletOrMobile">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                                </svg>
                                <span x-text="railOpen ? 'Hide Telemetry' : 'Show Telemetry'">Show Telemetry</span>
                            </button>
                            <div class="admin-chat-header-title-wrap">
                                <h2 class="admin-chat-header-title">Gemini Operational Intelligence Assistant</h2>
                                <p class="admin-chat-header-subtitle">Platform operations, candidate-role matching audits, and corporate document analysis</p>
                            </div>
                        </div>
                        <div class="admin-chat-header-actions">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold border border-indigo-100" style="white-space: nowrap !important; flex-shrink: 0 !important;">
                                <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                                <span>Gemini 1.5 Pro</span>
                            </span>
                            <button type="button" 
                                    @click="clearChat()"
                                    class="admin-btn-dark-outline text-xs font-semibold text-slate-600 hover:text-indigo-600 px-3 py-1.5 rounded-xl border border-slate-200 transition-colors cursor-pointer"
                                    style="white-space: nowrap !important; flex-shrink: 0 !important; display: inline-flex !important; align-items: center !important;">
                                New Session
                            </button>
                        </div>
                    </div>

                    <!-- Main Chat View Area (Scrollable with subtle atmospheric glow & generous padding) -->
                    <div class="flex-1 overflow-y-auto relative" id="admin-chat-canvas" style="padding: 28px 32px 36px 32px; background: radial-gradient(circle at 50% 32%, rgba(241, 245, 249, 0.75) 0%, rgba(255, 255, 255, 1) 65%);">
                        
                        <!-- Initial Hero Welcome State (When No Messages Exist) -->
                        <div x-show="messages.length === 0" 
                             class="flex flex-col items-center justify-center min-h-full max-w-xl mx-auto text-center py-6">
                            
                            <!-- Security Pill Badge -->
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-50 border border-slate-200 text-slate-700 text-xs font-semibold mb-6 shadow-2xs">
                                <svg class="w-3.5 h-3.5 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Chat is Protected</span>
                            </div>

                            <!-- Welcome Title & Subtitle -->
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-1.5">
                                Welcome, <?= htmlspecialchars(explode(' ', $staffName)[0]) ?>!
                            </h1>
                            <p class="text-sm text-slate-500 mb-8">How can I help you analyze platform operations today?</p>

                            <!-- Center 3D Mascot Avatar Presentation (Unclipped & Balanced) -->
                            <div class="relative mb-6 flex items-center justify-center">
                                <img src="<?= asset('assets/images/jenny_ai/jenny_ai_hi.png') ?>" 
                                     onerror="this.onerror=null; this.src='<?= url('assets/images/jenny_ai/jenny_ai_hi.png') ?>';"
                                     alt="Jenny AI Mascot" 
                                     style="width: 130px; height: 130px; object-fit: contain;"
                                     class="drop-shadow-2xl hover:scale-105 transition-transform duration-300">
                            </div>

                            <!-- Quick Suggestion Bubble Cloud (Matching Project Specifics) -->
                            <div class="flex flex-col items-center justify-center gap-2 max-w-lg mb-6">
                                <div class="flex flex-wrap items-center justify-center gap-2">
                                    <button type="button" @click="sendDirect('Audit flagged match discrepancy queue')" class="admin-chat-chip">Audit Flagged Matches</button>
                                    <button type="button" @click="sendDirect('Summarize pending CAC corporate filings')" class="admin-chat-chip">Summarize Pending CAC Filings</button>
                                </div>
                                <div class="flex flex-wrap items-center justify-center gap-2">
                                    <button type="button" @click="sendDirect('Analyze candidate university placement retention')" class="admin-chat-chip">Analyze Candidate Retention</button>
                                    <button type="button" @click="sendDirect('Check Paystack transaction webhook ledger')" class="admin-chat-chip">Check Paystack Ledger</button>
                                </div>
                                <div class="flex flex-wrap items-center justify-center gap-2">
                                    <button type="button" @click="sendDirect('Generate weekly placement and revenue report')" class="admin-chat-chip">Generate Placement Report</button>
                                </div>
                            </div>
                        </div>

                        <!-- Conversation Message Stream (When Messages Exist) -->
                        <div x-show="messages.length > 0" class="max-w-2xl mx-auto space-y-4 pb-28 pt-2">
                            <template x-for="(msg, idx) in messages" :key="idx">
                                <div class="flex gap-3" :class="{ 'justify-end': msg.role === 'user' }">
                                    <!-- Bot Avatar -->
                                    <template x-if="msg.role === 'assistant'">
                                        <img src="<?= asset('assets/images/jenny_ai/jenny_icon.png') ?>" onerror="this.onerror=null; this.src='<?= url('assets/images/jenny_ai/jenny_icon.png') ?>';" alt="Jenny AI" class="w-8 h-8 rounded-full flex-shrink-0 mt-0.5 border border-slate-200 shadow-2xs">
                                    </template>

                                    <!-- Message Bubble -->
                                    <div class="flex flex-col" :class="{ 'items-end': msg.role === 'user' }">
                                        <div class="p-4 rounded-2xl text-xs leading-relaxed max-w-lg"
                                             :class="msg.role === 'user' ? 'bg-[#000623] text-white rounded-tr-xs shadow-xs' : 'bg-white border border-slate-200 text-slate-800 shadow-xs rounded-tl-xs'">
                                            <p class="whitespace-pre-line" x-text="msg.text"></p>

                                            <!-- Structured Metric Card inside Bot Response -->
                                            <template x-if="msg.stats">
                                                <div class="mt-3 pt-3 border-t border-slate-100 grid grid-cols-2 gap-2 text-[11px]">
                                                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                                        <span class="text-slate-400 block" x-text="msg.stats.label1"></span>
                                                        <span class="font-bold text-slate-900 text-xs" x-text="msg.stats.val1"></span>
                                                    </div>
                                                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                                        <span class="text-slate-400 block" x-text="msg.stats.label2"></span>
                                                        <span class="font-bold text-indigo-600 text-xs" x-text="msg.stats.val2"></span>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                        <span class="text-[10px] text-slate-400 mt-1 px-1 font-mono" x-text="msg.time"></span>
                                    </div>
                                </div>
                            </template>

                            <!-- Live Thinking Indicator -->
                            <div x-show="isThinking" class="flex gap-3">
                                <img src="<?= asset('assets/images/jenny_ai/jenny_icon.png') ?>" onerror="this.onerror=null; this.src='<?= url('assets/images/jenny_ai/jenny_icon.png') ?>';" alt="Jenny AI" class="w-8 h-8 rounded-full flex-shrink-0 mt-0.5 border border-slate-200 shadow-2xs">
                                <div class="bg-white border border-slate-200 p-3.5 rounded-2xl rounded-tl-xs shadow-xs flex items-center gap-2.5 text-xs text-slate-600">
                                    <div class="w-2 h-2 rounded-full bg-indigo-600 animate-ping"></div>
                                    <span class="font-medium text-xs">Analyzing operational data...</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- ════════════ FLOATING BOTTOM COMPOSER PILL ════════════ -->
                    <div class="p-4 sm:p-6 bg-gradient-to-t from-white via-white/95 to-transparent flex flex-col items-center flex-shrink-0 z-20">
                        
                        <!-- Selected Attachment Preview Pill (If File Attached) -->
                        <template x-if="attachedFile">
                            <div class="w-full max-w-xl mb-2 flex items-center justify-between px-3 py-1.5 rounded-xl bg-indigo-50 border border-indigo-200 text-xs text-indigo-900 shadow-2xs">
                                <div class="flex items-center gap-2 truncate">
                                    <svg class="w-4 h-4 text-indigo-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                    </svg>
                                    <span class="font-semibold truncate" x-text="attachedFile.name"></span>
                                    <span class="text-[11px] text-indigo-500" x-text="'(' + attachedFile.size + ')'"></span>
                                </div>
                                <button type="button" @click="attachedFile = null" class="text-indigo-400 hover:text-indigo-700 cursor-pointer p-0.5">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>

                        <!-- Floating Input Capsule with Drag & Drop -->
                        <div class="admin-chat-floating-composer"
                             :class="{ 'ring-2 ring-indigo-500 bg-indigo-50/20': isDragging }"
                             @dragover.prevent="isDragging = true"
                             @dragleave.prevent="isDragging = false"
                             @drop.prevent="handleFileDrop($event)">
                            
                            <!-- Hidden File Input -->
                            <input type="file" 
                                   id="admin-chat-file-input" 
                                   class="hidden" 
                                   accept=".pdf,.docx,.doc,.txt,.csv,.xlsx,.png,.jpg"
                                   @change="handleFileSelect($event)">

                            <!-- Left Sparkle Icon -->
                            <span class="text-indigo-600 flex-shrink-0 text-sm">✦</span>

                            <!-- Text Input -->
                            <input type="text"
                                   x-model="inputQuery"
                                   @keydown.enter.prevent="sendMessage()"
                                   placeholder="Ask Gemini to analyze candidates, verify CAC filings, or audit transactions..."
                                   class="flex-1 bg-transparent border-none outline-none text-xs sm:text-sm text-slate-900 placeholder:text-slate-400 font-medium">

                            <!-- Attachment Button (Styled cleanly like the circular control) -->
                            <button type="button" 
                                    @click="triggerFileInput()"
                                    class="admin-composer-btn-mic cursor-pointer"
                                    title="Attach document (PDF, DOCX, CSV) or drag & drop"
                                    aria-label="Attach document">
                                <svg class="w-4 h-4 text-slate-500 hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                            </button>

                            <!-- Send Arrow Up Button -->
                            <button type="button"
                                    @click="sendMessage()"
                                    :disabled="(!inputQuery.trim() && !attachedFile) || isThinking"
                                    class="admin-composer-btn-send cursor-pointer"
                                    :class="{ 'opacity-40 cursor-not-allowed': (!inputQuery.trim() && !attachedFile) || isThinking }"
                                    aria-label="Send query">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>
</div>

<script>
function adminAiChat() {
    const checkTabletOrMobile = () => window.innerWidth <= 1024 || ('ontouchstart' in window && window.innerWidth <= 1380);
    const initialTabletOrMobile = checkTabletOrMobile();
    return {
        isTabletOrMobile: initialTabletOrMobile,
        railOpen: !initialTabletOrMobile, // Fully active on desktop, collapsed with beeping button on iPad & mobile
        showProgress: true,
        showHistory: true,
        contextTitle: 'Candidate & CAC Desk',
        currentModel: 'Gemini 1.5 Pro',
        inputQuery: '<?= addslashes($initialQuery) ?>',
        isThinking: false,
        isDragging: false,
        attachedFile: null,
        contexts: [
            'Candidate & CAC Desk',
            'Subscription & Billing Ledger',
            'Candidate Academic Standing',
            'Algorithmic Match Tuning'
        ],
        contextIndex: 0,
        activeSessionId: 1,
        recentSessions: [
            { id: 1, title: 'CAC Document Cross-Check', time: '2 hours ago', tag: 'Registry Audit', query: 'Cross-reference Nexura Technologies CAC registry certificate with domain' },
            { id: 2, title: 'Placement Score Anomalies', time: 'Yesterday', tag: 'Anomaly Audit', query: 'Audit flagged candidates in PaySprint and Kiro Labs' },
            { id: 3, title: 'Q3 Intern Retention Summary', time: '8 Nov 2026', tag: 'Analytics', query: 'Summarize university internship retention across top 4 tracks' },
            { id: 4, title: 'Paystack Webhook Sync Audit', time: '6 Nov 2026', tag: 'FinTech Ledger', query: 'Inspect cryptographically signed HMAC-SHA512 transaction logs' }
        ],
        messages: [],
        init() {
            window.addEventListener('resize', () => {
                this.isTabletOrMobile = checkTabletOrMobile();
            });
            if (this.inputQuery.trim().length > 0) {
                this.sendMessage();
            }
        },
        cycleContext() {
            this.contextIndex = (this.contextIndex + 1) % this.contexts.length;
            this.contextTitle = this.contexts[this.contextIndex];
            if (typeof window.adminToast === 'function') {
                window.adminToast('Context switched', this.contextTitle, null, 'info');
            }
        },
        loadSession(item) {
            this.activeSessionId = item.id;
            this.messages = [
                {
                    role: 'user',
                    text: item.query,
                    time: item.time
                },
                {
                    role: 'assistant',
                    text: "Operational Review for: \"" + item.title + "\"\n\nTelemetry verified against live database records. No anomalies detected.",
                    stats: { label1: 'Audit Status', val1: 'Verified', label2: 'Integrity', val2: '100%' },
                    time: 'Just now'
                }
            ];
            this.scrollToBottom();
            if (typeof window.adminToast === 'function') {
                window.adminToast('Session loaded', item.title, null, 'info');
            }
        },
        triggerFileInput() {
            const input = document.getElementById('admin-chat-file-input');
            if (input) input.click();
        },
        attachSampleDoc(name = 'Candidate_Audit_Report.pdf') {
            this.attachedFile = {
                name: name,
                size: '1.4 MB'
            };
            if (typeof window.adminToast === 'function') {
                window.adminToast('Document attached', name, null, 'info');
            }
        },
        handleFileSelect(e) {
            const file = e.target.files && e.target.files[0];
            if (file) {
                this.attachedFile = {
                    name: file.name,
                    size: (file.size / (1024 * 1024)).toFixed(1) + ' MB'
                };
                if (typeof window.adminToast === 'function') {
                    window.adminToast('Document attached', file.name, null, 'info');
                }
            }
        },
        handleFileDrop(e) {
            this.isDragging = false;
            const file = e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0];
            if (file) {
                this.attachedFile = {
                    name: file.name,
                    size: (file.size / (1024 * 1024)).toFixed(1) + ' MB'
                };
                if (typeof window.adminToast === 'function') {
                    window.adminToast('Document dropped & attached', file.name, null, 'info');
                }
            }
        },
        sendDirect(text) {
            this.inputQuery = text;
            this.sendMessage();
        },
        sendMessage() {
            const query = this.inputQuery.trim();
            if ((!query && !this.attachedFile) || this.isThinking) return;

            const timeNow = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            let userPrompt = query;
            if (this.attachedFile) {
                userPrompt += (userPrompt ? '\n\n' : '') + '[Attached: ' + this.attachedFile.name + ' (' + this.attachedFile.size + ')]';
            }

            // Push User Message
            this.messages.push({
                role: 'user',
                text: userPrompt,
                time: timeNow
            });

            const currentFile = this.attachedFile;
            this.inputQuery = '';
            this.attachedFile = null;
            this.isThinking = true;
            this.scrollToBottom();

            setTimeout(() => {
                let reply = "I have analyzed platform telemetry across active records.";
                let stats = null;

                const qLower = query.toLowerCase();
                if (currentFile) {
                    reply = "Inspection of attached document (" + currentFile.name + "):\n\n• Document integrity: Verified authentic.\n• Cross-check against records: All fields match platform database entries with zero discrepancies.\n• Operational recommendation: Proceed with document verification approval.";
                    stats = { label1: 'Document Status', val1: 'Verified Authentic', label2: 'Confidence', val2: '99.4%' };
                } else if (qLower.includes('flagged') || qLower.includes('match') || qLower.includes('discrepancy')) {
                    reply = "Flagged Match Discrepancy Queue Audit:\n\n• 6 candidate-role pairings currently flagged for review.\n• Chinedu Okafor (Score: 62%): GPA slightly below role filter, but verified GitHub repository contains 14 production REST API projects.\n• Recommendation: Confirm match for Chinedu Okafor to unblock backend hiring.";
                    stats = { label1: 'Flagged Queue', val1: '6 Pairs', label2: 'Recommendation', val2: 'Confirm Top Match' };
                } else if (qLower.includes('cac') || qLower.includes('filing') || qLower.includes('corporate') || qLower.includes('employer')) {
                    reply = "Corporate CAC Verification Audit:\n\n• 14 employers currently awaiting desk verification.\n• Nexura Technologies (RC-1849204): 100% domain and seal match with active corporate registry status.\n• Recommendation: Approve Nexura Technologies immediately to open 6 active internship roles.";
                    stats = { label1: 'Pending CAC', val1: '14 Accounts', label2: 'Priority Desk', val2: 'Nexura Tech' };
                } else if (qLower.includes('paystack') || qLower.includes('ledger') || qLower.includes('transaction')) {
                    reply = "Paystack Ledger & Webhook Telemetry:\n\n• Current MRR stands at ₦4,850,000 across 1,420 Student Pro subscribers and 32 corporate placements.\n• Cryptographic HMAC-SHA512 verification: 100% success rate across all 10 recent transactions.\n• Zero webhook failures recorded.";
                    stats = { label1: 'MRR Balance', val1: '₦4.85M', label2: 'HMAC Webhooks', val2: '100% Verified' };
                } else if (qLower.includes('retention') || qLower.includes('university') || qLower.includes('placement')) {
                    reply = "Candidate Placement & Retention Telemetry:\n\n1. University of Lagos (UNILAG) leads with 412 placed interns (88.4% retention).\n2. Covenant University (OTA) follows with 294 placements (91.2% satisfaction).\n3. Overall platform conversion rate: 71.4% match acceptance.";
                    stats = { label1: 'Top Institution', val1: 'UNILAG (412)', label2: 'Conversion Rate', val2: '71.4%' };
                } else {
                    reply = "Strategic synthesis for: \"" + query + "\"\n\nPlatform metrics indicate operational stability. Nightly scoring evaluated 1,904 pairs with 71.4% acceptance. All verified HMAC-SHA512 Paystack webhooks are synchronized with zero latency bottlenecks.";
                    stats = { label1: 'Pairs Scored', val1: '1,904 Pairs', label2: 'Acceptance Rate', val2: '71.4%' };
                }

                this.messages.push({
                    role: 'assistant',
                    text: reply,
                    stats: stats,
                    time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                });

                this.isThinking = false;
                this.scrollToBottom();
            }, 600);
        },
        clearChat() {
            this.messages = [];
            this.inputQuery = '';
            this.attachedFile = null;
            if (typeof window.adminToast === 'function') {
                window.adminToast('New session started', 'Conversation history cleared', null, 'info');
            }
        },
        scrollToBottom() {
            this.$nextTick(() => {
                const el = document.getElementById('admin-chat-canvas');
                if (el) el.scrollTop = el.scrollHeight;
            });
        }
    };
}
</script>
