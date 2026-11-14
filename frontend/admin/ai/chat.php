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
$mySessions = $mySessions ?? [];
$userSessions = $userSessions ?? [];
$jennyStats = $jennyStats ?? ['conversations_24h' => 0, 'messages_24h' => 0, 'gemini_share' => 0, 'visitors_24h' => 0];
$geminiModel = $geminiModel ?? 'Gemini';
$capPct = static fn (int $v, int $max) => max(6, min(100, (int)round($v / max(1, $max) * 100)));
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
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" aria-label="Online"></span>
                    </div>

                    <!-- PROGRESS Section (Vertical Capsule Bar Meters) -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between px-1 cursor-pointer" @click="showProgress = !showProgress">
                            <span class="text-[10.5px] font-bold text-slate-400 uppercase tracking-wider">JENNY, LAST 24 HOURS</span>
                            <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': !showProgress }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>

                        <div x-show="showProgress" x-transition class="admin-chat-capsules-grid">
                            <!-- Capsule 1: Intern Sentiment -->
                            <div class="admin-capsule-col">
                                <span class="text-[10px] text-slate-400 font-medium">Chats</span>
                                <div class="admin-capsule-track">
                                    <div class="admin-capsule-fill admin-capsule-fill-green" style="height: <?= $capPct((int)$jennyStats['conversations_24h'], 50) ?>%;"></div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-800"><?= (int)$jennyStats['conversations_24h'] ?></span>
                            </div>

                            <!-- Capsule 2: Employer Adoption -->
                            <div class="admin-capsule-col">
                                <span class="text-[10px] text-slate-400 font-medium">Messages</span>
                                <div class="admin-capsule-track">
                                    <div class="admin-capsule-fill admin-capsule-fill-indigo" style="height: <?= $capPct((int)$jennyStats['messages_24h'], 300) ?>%;"></div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-800"><?= (int)$jennyStats['messages_24h'] ?></span>
                            </div>

                            <!-- Capsule 3: CAC Speed -->
                            <div class="admin-capsule-col">
                                <span class="text-[10px] text-slate-400 font-medium">Visitors</span>
                                <div class="admin-capsule-track">
                                    <div class="admin-capsule-fill admin-capsule-fill-purple" style="height: <?= $capPct((int)$jennyStats['visitors_24h'], 50) ?>%;"></div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-800"><?= (int)$jennyStats['visitors_24h'] ?></span>
                            </div>

                            <!-- Capsule 4: Confidence -->
                            <div class="admin-capsule-col">
                                <span class="text-[10px] text-slate-400 font-medium">Gemini</span>
                                <div class="admin-capsule-track">
                                    <div class="admin-capsule-fill admin-capsule-fill-dark" style="height: <?= max(6, (int)$jennyStats['gemini_share']) ?>%;"></div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-800"><?= (int)$jennyStats['gemini_share'] ?>%</span>
                            </div>
                        </div>
                    </div>

                    <!-- HISTORY CHAT (Premium High-End Session History) -->
                    <div class="space-y-2 mt-4">
                        <div class="flex items-center justify-between px-1 cursor-pointer select-none" @click="showHistory = !showHistory">
                            <div class="flex items-center gap-2">
                                <span class="text-[10.5px] font-bold text-slate-500 uppercase tracking-wider" style="font-size: 10.5px !important; letter-spacing: 0.05em;">HISTORY CHAT</span>
                                <span class="px-2 py-0.5 rounded-full font-bold bg-indigo-50 text-[#4F48E6] border border-indigo-100" style="font-size: 10px !important;" x-text="(historyTab === 'mine' ? recentSessions : userSessions).length"></span>
                            </div>
                            <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': !showHistory }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>

                        <div x-show="showHistory" x-transition class="space-y-2">
                            <!-- My chats / conversations from users (read-only review) -->
                            <div style="display: flex; gap: 4px; padding: 3px; background: #F1F5F9; border-radius: 10px;" role="tablist" aria-label="Chat history">
                                <button type="button" role="tab" @click="historyTab = 'mine'" :aria-selected="historyTab === 'mine'" class="adm-htab" :class="{ 'is-on': historyTab === 'mine' }">My chats</button>
                                <button type="button" role="tab" @click="historyTab = 'users'" :aria-selected="historyTab === 'users'" class="adm-htab" :class="{ 'is-on': historyTab === 'users' }">User chats</button>
                            </div>
                            <div x-show="(historyTab === 'mine' ? recentSessions : userSessions).length === 0" style="font-size: 12px; color: #94A3B8; padding: 10px 4px;" x-text="historyTab === 'mine' ? 'No chats yet. Ask Jenny anything to start.' : 'No user conversations yet.'"></div>
                            <template x-for="item in (historyTab === 'mine' ? recentSessions : userSessions)" :key="item.id">
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
                                            <span class="admin-history-tag" x-text="historyTab === 'users' ? (item.who + (item.place ? ' \u00B7 ' + item.place : '')) : (item.count + ' messages')"></span>
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
                                <span class="text-[11px] text-slate-500 block mb-1">Chats today</span>
                                <span class="text-base font-bold text-slate-900"><?= (int)$jennyStats['conversations_24h'] ?></span>
                            </div>
                            <div class="admin-chat-dist-card">
                                <span class="text-[11px] text-slate-500 block mb-1">Visitor chats</span>
                                <span class="text-base font-bold text-indigo-600"><?= (int)$jennyStats['visitors_24h'] ?></span>
                            </div>
                        </div>

                        <!-- System Utilization Gauge Card -->
                        <div class="admin-chat-dist-card flex items-center justify-between">
                            <div class="space-y-1 text-[11px]">
                                <span class="font-bold text-slate-800 block text-xs">Answered by Gemini</span>
                                <span class="text-slate-500 block"><?= htmlspecialchars($geminiModel) ?></span>
                                <span class="text-indigo-600 font-semibold text-[10.5px]">Rest: rule-based fallback</span>
                            </div>

                            <!-- Micro Circular Gauge SVG (84%) -->
                            <div class="relative w-12 h-12 flex items-center justify-center flex-shrink-0">
                                <svg class="w-12 h-12 -rotate-90" viewBox="0 0 36 36">
                                    <path class="text-slate-100" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                    <path class="text-indigo-600" stroke-width="3.5" stroke-dasharray="<?= (int)$jennyStats['gemini_share'] ?>, 100" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                </svg>
                                <span class="absolute font-bold text-[11px] text-slate-800"><?= (int)$jennyStats['gemini_share'] ?>%</span>
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
                                    aria-label="Toggle the telemetry panel">
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
                                <span><?= htmlspecialchars($geminiModel) ?></span>
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
                            <div x-show="readOnly" style="display: none; font-size: 12px; color: #475569; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 10px 12px;">
                                <strong style="color: #0F172A;" x-text="readOnlyTitle"></strong>
                                <span> Read-only view of a user conversation. Start a new session to chat with Jenny yourself.</span>
                            </div>
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
                                            <template x-if="msg.role === 'assistant'"><div class="adm-md" x-html="format(msg.text)"></div></template>
                                            <template x-if="msg.role !== 'assistant'"><p class="whitespace-pre-line" x-text="msg.text"></p></template>
                                            <template x-if="msg.file">
                                                <a :href="msg.file.url || null" target="_blank" rel="noopener" style="display: inline-flex; align-items: center; gap: 6px; margin-top: 8px; padding: 5px 9px; border-radius: 8px; background: rgba(255,255,255,.14); color: inherit; font-size: 11px; font-weight: 700; text-decoration: none;">
                                                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                                    <span x-text="msg.file.name"></span>
                                                </a>
                                            </template>
                                            <template x-if="msg.links && msg.links.length">
                                                <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-top: 10px;">
                                                    <template x-for="lk in msg.links" :key="lk.url + lk.label">
                                                        <a :href="lk.url" style="display: inline-flex; padding: 5px 10px; border-radius: 999px; background: #EEF0FF; color: #4F48E6; font-size: 11px; font-weight: 700; text-decoration: none;" x-text="lk.label"></a>
                                                    </template>
                                                </div>
                                            </template>

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
                                   accept=".pdf,.docx,.txt,.png,.jpg,.jpeg,.webp"
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

<script type="application/json" id="admin-jenny-data"><?= json_encode(['sessions' => $mySessions, 'userSessions' => $userSessions, 'send' => url('/admin/ai/chat/send'), 'session' => url('/admin/ai/chat/sessions/')], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
<style>
.adm-md ul { margin: 6px 0; padding-left: 18px; list-style: disc; }
.adm-md li { margin: 2px 0; }
.adm-md strong { font-weight: 700; color: #0F172A; }
.adm-md .gap { display: block; height: 6px; }
.adm-htab { flex: 1; border: 0; border-radius: 8px; padding: 6px 8px; font-size: 11.5px; font-weight: 700; line-height: 1.2; cursor: pointer; background: transparent; color: #64748B; font-family: inherit; }
.adm-htab.is-on { background: #FFFFFF; color: #0F172A; box-shadow: 0 1px 2px rgba(15, 23, 42, .08); }
</style>
<script>
/** Admin Jenny console on Gemini (docs/55-jenny-ai-chat.md). */
function adminAiChat() {
    const checkTabletOrMobile = () => window.innerWidth <= 1024 || ('ontouchstart' in window && window.innerWidth <= 1380);
    const initialTabletOrMobile = checkTabletOrMobile();
    const data = JSON.parse(document.getElementById('admin-jenny-data').textContent);
    const esc = (s) => String(s).replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    const now = () => new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    const toast = (t, d, type) => { if (typeof window.adminToast === 'function') window.adminToast(t, d || '', null, type || 'info'); };
    return {
        isTabletOrMobile: initialTabletOrMobile,
        railOpen: !initialTabletOrMobile,
        showProgress: true,
        showHistory: true,
        historyTab: 'mine',
        inputQuery: <?= json_encode((string)$initialQuery, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>,
        isThinking: false,
        isDragging: false,
        attachedFile: null,
        file: null,
        activeSessionId: null,
        conversation: null,
        readOnly: false,
        readOnlyTitle: '',
        recentSessions: data.sessions,
        userSessions: data.userSessions,
        messages: [],
        init() {
            window.addEventListener('resize', () => { this.isTabletOrMobile = checkTabletOrMobile(); });
            if (this.inputQuery.trim().length > 0) this.sendMessage();
        },
        format(text) {
            const lines = esc(text || '').split(/\n/);
            let html = '', inList = false;
            lines.forEach((line) => {
                const t = line.trim();
                if (/^([-*]|\d+[.)])\s+/.test(t)) {
                    if (!inList) { html += '<ul>'; inList = true; }
                    html += '<li>' + t.replace(/^([-*]|\d+[.)])\s+/, '') + '</li>';
                } else {
                    if (inList) { html += '</ul>'; inList = false; }
                    html += t === '' ? '<span class="gap"></span>' : t + '<br>';
                }
            });
            if (inList) html += '</ul>';
            return html.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>').replace(/(<br>)+$/, '');
        },
        async loadSession(item) {
            this.activeSessionId = item.id;
            this.isThinking = true;
            try {
                const res = await fetch(data.session + encodeURIComponent(item.id), { headers: { 'Accept': 'application/json' } });
                const d = await res.json();
                if (!d.success) throw new Error(d.error || 'Not found');
                this.messages = d.messages.map((m) => ({ role: m.role, text: m.content, time: m.time, links: m.links || [], file: m.attachment }));
                const mine = this.historyTab === 'mine';
                this.readOnly = !mine;
                this.readOnlyTitle = mine ? '' : (item.who + (item.place ? ' (' + item.place + ')' : '') + ', ' + item.tag + ' chat.');
                this.conversation = mine ? item.id : null;
                if (this.isTabletOrMobile) this.railOpen = false;
            } catch (e) {
                toast('Could not open that conversation', String(e.message || e), 'error');
            }
            this.isThinking = false;
            this.scrollToBottom();
        },
        triggerFileInput() { const input = document.getElementById('admin-chat-file-input'); if (input) input.click(); },
        setFile(file) {
            if (!file) return;
            if (file.size > 8 * 1024 * 1024) { toast('File too large', 'Attach a file under 8 MB.', 'error'); return; }
            this.file = file;
            this.attachedFile = { name: file.name, size: (file.size / (1024 * 1024)).toFixed(1) + ' MB' };
        },
        handleFileSelect(e) { this.setFile(e.target.files && e.target.files[0]); e.target.value = ''; },
        handleFileDrop(e) { this.isDragging = false; this.setFile(e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0]); },
        sendDirect(text) { this.inputQuery = text; this.sendMessage(); },
        async sendMessage() {
            const query = this.inputQuery.trim();
            if ((!query && !this.file) || this.isThinking) return;
            if (this.readOnly) { this.clearChat(); }
            const file = this.file;
            this.messages.push({ role: 'user', text: query || 'Please review the attached file.', time: now(), file: file ? { name: file.name } : null });
            this.inputQuery = '';
            this.file = null;
            this.attachedFile = null;
            this.isThinking = true;
            this.scrollToBottom();
            let body, headers = { 'Accept': 'application/json' };
            if (file) {
                body = new FormData();
                body.append('message', query);
                if (this.conversation) body.append('conversation', this.conversation);
                body.append('page', location.pathname);
                body.append('file', file);
            } else {
                headers['Content-Type'] = 'application/json';
                body = JSON.stringify({ message: query, conversation: this.conversation, page: location.pathname });
            }
            try {
                const res = await fetch(data.send, { method: 'POST', headers: headers, body: body });
                const d = await res.json().catch(() => ({}));
                if (d.success) {
                    const isNew = !this.conversation;
                    this.conversation = d.conversation;
                    this.activeSessionId = d.conversation;
                    this.messages.push({ role: 'assistant', text: d.reply, links: (d.links || []).concat((d.listings || []).map((l) => ({ label: l.title + ' at ' + l.company, url: l.url }))), time: now() });
                    if (isNew) this.recentSessions.unshift({ id: d.conversation, title: (query || 'File review').slice(0, 80), time: 'Just now', tag: 'Admin', who: '', place: '', count: 2 });
                    if (d.source === 'rules') toast('Answered without Gemini', 'Gemini was busy, so Jenny used the built-in answers.', 'info');
                } else {
                    this.messages.push({ role: 'assistant', text: d.error || 'Jenny could not answer just now. Please try again.', time: now() });
                }
            } catch (e) {
                this.messages.push({ role: 'assistant', text: 'Network error. Check your connection and try again.', time: now() });
            }
            this.isThinking = false;
            this.scrollToBottom();
        },
        clearChat() {
            this.messages = [];
            this.inputQuery = '';
            this.attachedFile = null;
            this.file = null;
            this.conversation = null;
            this.activeSessionId = null;
            this.readOnly = false;
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
