<?php
declare(strict_types=1);

/**
 * View: Candidate Direct Messaging Workspace
 * Route: /candidate/messages, /candidate/messages/{id}, /messages
 * Layout: dashboard
 * 
 * @var array $candidate      Candidate profile information
 * @var array $conversations  List of recruiter conversation threads
 * @var int   $activeThreadId Active conversation ID
 */
?>
<style>
/* ==========================================================================
   CANDIDATE MESSAGING WORKSPACE — DESIGN SYSTEM COMPLIANT STYLES
   ========================================================================== */

.msg-workspace-shell {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 18px;
    display: grid;
    grid-template-columns: 340px minmax(0, 1fr);
    height: 680px;
    max-height: calc(100vh - 160px);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    overflow: hidden;
    margin-bottom: 24px;
    box-sizing: border-box;
}

/* ── LEFT COLUMN: THREADS PANE ── */
.msg-threads-pane {
    border-right: 1px solid #E2E8F0;
    display: flex;
    flex-direction: column;
    background: #FFFFFF;
    height: 100%;
    min-height: 0;
    min-width: 0;
    overflow: hidden;
}

.msg-threads-header {
    padding: 20px 20px 14px;
    border-bottom: 1px solid #F1F5F9;
}

.msg-threads-title-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
}

.msg-threads-title {
    font-size: 18px;
    font-weight: 800;
    color: #000623;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.msg-unread-badge {
    background: #EEF2FF;
    color: #4F48E6;
    font-size: 11px;
    font-weight: 800;
    padding: 2px 8px;
    border-radius: 99px;
    border: 1px solid #C7D2FE;
}

.msg-search-box {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 8px 12px;
    transition: all 0.15s ease;
}

.msg-search-box:focus-within {
    border-color: #4F48E6;
    background: #FFFFFF;
    box-shadow: 0 0 0 3px rgba(79, 72, 230, 0.1);
}

.msg-search-input {
    border: none;
    outline: none;
    background: transparent;
    font-size: 13px;
    color: #0F172A;
    width: 100%;
    min-width: 0;
}

.msg-filter-tabs {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 10px 16px;
    border-bottom: 1px solid #F1F5F9;
    background: #FAFAFC;
}

.msg-tab-btn {
    padding: 5px 12px;
    border-radius: 99px;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid transparent;
    background: transparent;
    color: #64748B;
    cursor: pointer;
    transition: all 0.15s ease;
    white-space: nowrap !important;
}

.msg-tab-btn:hover {
    color: #0F172A;
    background: #F1F5F9;
}

.msg-tab-btn.is-active {
    background: #FFFFFF;
    color: #4F48E6;
    border-color: #E2E8F0;
    box-shadow: 0 1px 2px rgba(0,0,0,0.04);
}

.msg-threads-scroll {
    flex: 1;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
}

.msg-thread-item {
    padding: 14px 18px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    border-bottom: 1px solid #F8FAFC;
    cursor: pointer;
    transition: background 0.15s ease;
    text-decoration: none;
    position: relative;
}

.msg-thread-item:hover {
    background: #F8FAFC;
}

.msg-thread-item.is-active {
    background: #F1F5F9;
}

.msg-avatar-wrap {
    position: relative;
    width: 44px;
    height: 44px;
    flex-shrink: 0;
}

.msg-avatar-img {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    object-fit: cover;
    background: #E2E8F0;
}

.msg-status-dot {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 11px;
    height: 11px;
    border-radius: 50%;
    border: 2px solid #FFFFFF;
    background: #94A3B8;
}

.msg-status-dot.online {
    background: #10B981;
}

.msg-thread-body {
    flex: 1;
    min-width: 0;
}

.msg-thread-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 6px;
    margin-bottom: 3px;
}

.msg-company-name {
    font-size: 14px;
    font-weight: 700;
    color: #000623;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.msg-thread-time {
    font-size: 11px;
    color: #94A3B8;
    white-space: nowrap !important;
    flex-shrink: 0;
}

.msg-candidate-role {
    font-size: 11.5px;
    color: #4F48E6;
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 3px;
}

.msg-snippet-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}

.msg-snippet-text {
    font-size: 12.5px;
    color: #64748B;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
}

.msg-thread-item.has-unread .msg-snippet-text {
    color: #0F172A;
    font-weight: 600;
}

.msg-unread-pill-sm {
    background: #4F48E6;
    color: #FFFFFF;
    font-size: 10px;
    font-weight: 800;
    padding: 1px 6px;
    border-radius: 99px;
    flex-shrink: 0;
}

/* ── RIGHT COLUMN: ACTIVE CHAT PANE ── */
.msg-chat-pane {
    display: flex;
    flex-direction: column;
    height: 100%;
    min-height: 0;
    background: #FFFFFF;
    min-width: 0;
    overflow: hidden;
}

.msg-chat-header {
    padding: 16px 24px;
    border-bottom: 1px solid #E2E8F0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    background: #FFFFFF;
    flex-shrink: 0;
}

.msg-chat-candidate-info {
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 0;
}

.msg-chat-candidate-text {
    min-width: 0;
}

.msg-chat-header-name {
    font-size: 16px;
    font-weight: 800;
    color: #000623;
    line-height: 1.25;
    margin: 0 0 2px;
}

.msg-chat-header-sub {
    font-size: 12px;
    color: #64748B;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.msg-chat-header-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

.msg-header-btn {
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
    padding: 8px 14px;
    font-size: 12.5px;
    font-weight: 600;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.15s ease;
    border: 1px solid #E2E8F0;
    background: #FFFFFF;
    color: #334155;
    cursor: pointer;
}

.msg-header-btn:hover {
    background: #F8FAFC;
    border-color: #CBD5E1;
    color: #0F172A;
}

.msg-header-btn.is-primary {
    background: #4F48E6;
    color: #FFFFFF !important;
    border-color: #4F48E6;
}

.msg-header-btn.is-primary:hover {
    background: #4338CA;
}

/* ── CHAT STREAM ── */
.msg-stream-wrap {
    flex: 1 1 0%;
    min-height: 0;
    overflow-y: auto;
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 16px;
    background: #F8FAFC;
    scroll-behavior: smooth;
}

.msg-date-divider {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 8px 0;
}

.msg-date-pill {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    padding: 4px 12px;
    border-radius: 99px;
    font-size: 11px;
    font-weight: 700;
    color: #64748B;
    box-shadow: 0 1px 2px rgba(0,0,0,0.02);
}

.msg-bubble-group {
    display: flex;
    flex-direction: column;
    max-width: 72%;
}

.msg-bubble-group.is-incoming {
    align-self: flex-start;
}

.msg-bubble-group.is-outgoing {
    align-self: flex-end;
}

.msg-bubble {
    padding: 12px 16px;
    border-radius: 16px;
    font-size: 13.5px;
    line-height: 1.55;
    word-break: break-word;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}

.msg-bubble-group.is-incoming .msg-bubble {
    background: #FFFFFF;
    color: #0F172A;
    border: 1px solid #E2E8F0;
    border-top-left-radius: 4px;
}

.msg-bubble-group.is-outgoing .msg-bubble {
    background: #000623;
    color: #FFFFFF;
    border: 1px solid #000623;
    border-top-right-radius: 4px;
}

.msg-bubble-meta {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 4px;
    font-size: 11px;
    color: #94A3B8;
}

.msg-bubble-group.is-outgoing .msg-bubble-meta {
    justify-content: flex-end;
}

/* ── COMPOSER ── */
.msg-composer-wrap {
    padding: 16px 20px;
    border-top: 1px solid #E2E8F0;
    background: #FFFFFF;
    flex-shrink: 0;
}

.msg-quick-replies {
    display: flex;
    align-items: center;
    gap: 8px;
    overflow-x: auto;
    padding-bottom: 10px;
    margin-bottom: 10px;
}

.msg-quick-chip {
    padding: 5px 12px;
    border-radius: 99px;
    background: #F1F5F9;
    border: 1px solid #E2E8F0;
    color: #475569;
    font-size: 11.5px;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap !important;
    flex-shrink: 0;
    transition: all 0.15s ease;
}

.msg-quick-chip:hover {
    background: #EEF2FF;
    color: #4F48E6;
    border-color: #C7D2FE;
}

.msg-input-bar {
    display: flex;
    align-items: flex-end;
    gap: 10px;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    padding: 10px 14px;
    transition: all 0.15s ease;
}

.msg-input-bar:focus-within {
    background: #FFFFFF;
    border-color: #4F48E6;
    box-shadow: 0 0 0 3px rgba(79, 72, 230, 0.1);
}

.msg-textarea {
    flex: 1;
    border: none;
    outline: none;
    background: transparent;
    font-size: 13.5px;
    color: #0F172A;
    line-height: 1.5;
    resize: none;
    max-height: 100px;
    min-height: 24px;
    min-width: 0;
    font-family: inherit;
    padding: 0;
}

.msg-attach-btn {
    border: none;
    background: transparent;
    color: #64748B;
    cursor: pointer;
    padding: 4px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    transition: color 0.15s ease;
    flex-shrink: 0;
}

.msg-attach-btn:hover {
    color: #4F48E6;
    background: #EEF2FF;
}

.msg-send-btn {
    border: none;
    background: #4F48E6;
    color: #FFFFFF;
    border-radius: 10px;
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 700;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    cursor: pointer;
    transition: background 0.15s ease;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
}

.msg-send-btn:hover {
    background: #4338CA;
}

.msg-mobile-back-btn {
    display: none;
}

/* ── RESPONSIVE ── */
@media (max-width: 900px) {
    .msg-workspace-shell {
        grid-template-columns: minmax(0, 1fr);
        height: auto;
        max-height: none;
    }
    .msg-threads-pane {
        border-right: none;
        display: flex;
    }
    .msg-chat-pane {
        display: none;
    }
    .msg-workspace-shell.show-chat .msg-threads-pane {
        display: none;
    }
    .msg-workspace-shell.show-chat .msg-chat-pane {
        display: flex;
        min-height: 600px;
    }
    .msg-mobile-back-btn {
        display: inline-flex !important;
        align-items: center;
        gap: 6px;
        background: none;
        border: none;
        color: #4F48E6;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        padding: 4px 0;
        margin-bottom: 8px;
    }
    .msg-chat-header-actions {
        display: none;
    }
}
</style>

<script>
window.__CANDIDATE_MSG_INIT__ = {
    conversations: <?= json_encode($conversations, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) ?>,
    activeId: <?= (int)$activeThreadId ?>
};

function candidateMessagingWorkspace() {
    return {
        conversations: window.__CANDIDATE_MSG_INIT__.conversations,
        activeId: window.__CANDIDATE_MSG_INIT__.activeId,
        searchQuery: '',
        filterTab: 'all',
        mobileShowChat: false,
        newMessage: '',
        
        get activeThread() {
            return this.conversations.find(c => c.id === this.activeId) || this.conversations[0];
        },
        
        filteredConversations() {
            return this.conversations.filter(c => {
                const q = this.searchQuery.toLowerCase().trim();
                const matchesQuery = !q || 
                                     c.company_name.toLowerCase().includes(q) ||
                                     c.recruiter_name.toLowerCase().includes(q) ||
                                     c.role.toLowerCase().includes(q);
                if (this.filterTab === 'unread') return matchesQuery && c.unread > 0;
                return matchesQuery;
            });
        },

        selectThread(id) {
            this.activeId = id;
            const threadIdx = this.conversations.findIndex(c => c.id === id);
            if (threadIdx !== -1) {
                this.conversations[threadIdx].unread = 0;
                this.conversations = [...this.conversations];
            }
            this.mobileShowChat = true;
            this.scrollToBottom();
        },

        sendMessage() {
            const text = this.newMessage.trim();
            if (!text) return;

            const currentThreadId = this.activeId;
            const threadIdx = this.conversations.findIndex(c => c.id === currentThreadId);
            if (threadIdx === -1) return;

            const tempId = 'client_' + Date.now() + '_' + Math.random().toString(36).substr(2, 6);
            const newMsgObj = {
                id: tempId,
                sender: 'candidate',
                text: text,
                time: 'Just now',
                status: 'delivered'
            };

            if (!Array.isArray(this.conversations[threadIdx].messages)) {
                this.conversations[threadIdx].messages = [];
            }

            this.conversations[threadIdx].messages.push(newMsgObj);
            this.conversations[threadIdx].last_message = text;
            this.conversations[threadIdx].last_time = 'Just now';
            this.newMessage = '';

            // Force instant Alpine reactivity
            this.conversations = [...this.conversations];

            // Immediate scroll to bottom
            this.scrollToBottom();

            fetch('<?= url('/candidate/messages/send') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({
                    thread_id: currentThreadId,
                    content: text
                })
            }).then(res => res.json())
              .then(data => {
                  if (data && data.message) {
                      const currentThread = this.conversations.find(c => c.id === currentThreadId);
                      if (currentThread && Array.isArray(currentThread.messages)) {
                          const mIdx = currentThread.messages.findIndex(m => m.id === tempId);
                          if (mIdx !== -1) {
                              currentThread.messages[mIdx] = data.message;
                              this.conversations = [...this.conversations];
                          }
                      }
                  }
              })
              .catch(err => console.error('Failed to persist message:', err));
        },

        insertQuickReply(text) {
            this.newMessage = text;
            this.$nextTick(() => {
                const textarea = document.getElementById('cand-composer-textarea');
                if (textarea) textarea.focus();
            });
        },

        syncActiveThread() {
            if (document.hidden) return;
            const targetId = this.activeId;
            const threadIdx = this.conversations.findIndex(c => c.id === targetId);
            if (threadIdx === -1) return;

            fetch('<?= url('/candidate/messages/thread/') ?>' + targetId)
                .then(res => res.json())
                .then(data => {
                    if (this.activeId !== targetId) return;

                    if (data && data.success && Array.isArray(data.messages)) {
                        const currentMsgs = this.conversations[threadIdx].messages || [];
                        const serverLast = data.messages[data.messages.length - 1];
                        const clientLast = currentMsgs[currentMsgs.length - 1];

                        const countDiff = data.messages.length !== currentMsgs.length;
                        const lastMsgDiff = serverLast && clientLast && (serverLast.id !== clientLast.id || serverLast.text !== clientLast.text);

                        if (countDiff || lastMsgDiff) {
                            const pendingClientMsgs = currentMsgs.filter(m => typeof m.id === 'string' && m.id.startsWith('client_'));
                            const merged = [...data.messages];
                            pendingClientMsgs.forEach(pm => {
                                if (!merged.some(m => m.text === pm.text && m.sender === pm.sender)) {
                                    merged.push(pm);
                                }
                            });

                            this.conversations[threadIdx].messages = merged;
                            const finalLast = merged[merged.length - 1];
                            if (finalLast) {
                                this.conversations[threadIdx].last_message = finalLast.text;
                                this.conversations[threadIdx].last_time = finalLast.time;
                            }
                            this.conversations = [...this.conversations];
                            this.scrollToBottom();
                        }
                    }
                })
                .catch(() => {});
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const el = document.getElementById('cand-chat-stream');
                if (el) {
                    el.scrollTop = el.scrollHeight;
                    setTimeout(() => { el.scrollTop = el.scrollHeight; }, 60);
                    setTimeout(() => { el.scrollTop = el.scrollHeight; }, 180);
                }
            });
        }
    };
}
</script>

<div class="db-shell" x-data="candidateMessagingWorkspace()" x-init="
    $nextTick(() => {
        const threadIdx = conversations.findIndex(c => c.id === activeId);
        if (threadIdx !== -1) {
            conversations[threadIdx].unread = 0;
            conversations = [...conversations];
        }
        scrollToBottom();
    });
    setInterval(() => syncActiveThread(), 3500);
">

    <!-- Sidebar Overlay (mobile) -->
    <div class="db-sidebar-overlay" id="db-sidebar-overlay"></div>

    <!-- ══ SIDEBAR ══ -->
    <?php 
    $activeNav = 'messages';
    include dirname(__DIR__, 2) . '/shared/components/candidate-sidebar.php'; 
    ?>

    <!-- ══ MAIN ══ -->
    <div class="db-main">

        <!-- ══ TOP NAVBAR ══ -->
        <?php include dirname(__DIR__, 2) . '/shared/components/candidate-topnav.php'; ?>

        <!-- Scrollable Content -->
        <div class="db-content" id="db-content" style="padding-bottom: 24px;">

            <!-- Header Section -->
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; margin-bottom: 20px;">
                <div>
                    <h1 style="font-size: 24px; font-weight: 800; color: #000623; margin: 0 0 4px; letter-spacing: -0.02em;">Messages &amp; Inquiries</h1>
                    <p style="font-size: 13.5px; color: #64748B; margin: 0;">Direct 1-on-1 communication with hiring managers and recruiters.</p>
                </div>
            </div>

            <!-- Unified Messaging Workspace Shell -->
            <div class="msg-workspace-shell" :class="{ 'show-chat': mobileShowChat }">
                
                <!-- ── THREADS COLUMN (LEFT) ── -->
                <aside class="msg-threads-pane">
                    <div class="msg-threads-header">
                        <div class="msg-threads-title-row">
                            <h2 class="msg-threads-title">
                                <span>Conversations</span>
                                <span class="msg-unread-badge" x-text="conversations.filter(c => c.unread > 0).length + ' New'"></span>
                            </h2>
                        </div>
                        <div class="msg-search-box">
                            <svg style="width: 14px; height: 14px; color: #94A3B8; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" x-model="searchQuery" class="msg-search-input" placeholder="Search company, recruiter, or role..." aria-label="Search conversation threads">
                        </div>
                    </div>

                    <div class="msg-filter-tabs">
                        <button type="button" @click="filterTab = 'all'" class="msg-tab-btn" :class="{ 'is-active': filterTab === 'all' }">All</button>
                        <button type="button" @click="filterTab = 'unread'" class="msg-tab-btn" :class="{ 'is-active': filterTab === 'unread' }">Unread</button>
                    </div>

                    <div class="msg-threads-scroll">
                        <template x-for="thread in filteredConversations()" :key="thread.id">
                            <div class="msg-thread-item" 
                                 :class="{ 'is-active': thread.id === activeId, 'has-unread': thread.unread > 0 }" 
                                 @click="selectThread(thread.id)">
                                <div class="msg-avatar-wrap">
                                    <img :src="thread.recruiter_avatar" :alt="thread.recruiter_name" class="msg-avatar-img">
                                    <span class="msg-status-dot" :class="{ 'online': thread.status === 'online' }"></span>
                                </div>
                                <div class="msg-thread-body">
                                    <div class="msg-thread-top">
                                        <span class="msg-company-name" x-text="thread.company_name"></span>
                                        <span class="msg-thread-time" x-text="thread.last_time"></span>
                                    </div>
                                    <div class="msg-candidate-role" x-text="thread.role"></div>
                                    <div class="msg-snippet-row">
                                        <span class="msg-snippet-text" x-text="thread.last_message"></span>
                                        <span x-show="thread.unread > 0" class="msg-unread-pill-sm" x-text="thread.unread"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </aside>

                <!-- ── ACTIVE CHAT COLUMN (RIGHT) ── -->
                <main class="msg-chat-pane">
                    
                    <!-- Chat Header -->
                    <div class="msg-chat-header">
                        <div class="msg-chat-candidate-info">
                            <button type="button" class="msg-mobile-back-btn" @click="mobileShowChat = false">
                                <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                </svg>
                                <span>Back</span>
                            </button>
                            <div class="msg-avatar-wrap">
                                <img :src="activeThread.recruiter_avatar" :alt="activeThread.recruiter_name" class="msg-avatar-img">
                                <span class="msg-status-dot" :class="{ 'online': activeThread.status === 'online' }"></span>
                            </div>
                            <div class="msg-chat-candidate-text">
                                <h2 class="msg-chat-header-name" x-text="activeThread.recruiter_name"></h2>
                                <div class="msg-chat-header-sub">
                                    <span style="color: #4F48E6; font-weight: 700;" x-text="activeThread.company_name"></span>
                                    <span>&bull;</span>
                                    <span x-text="activeThread.role"></span>
                                    <span>&bull;</span>
                                    <span style="color: #059669; font-weight: 600;" x-text="activeThread.last_active"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Top Quick Action Links -->
                        <div class="msg-chat-header-actions">
                            <a :href="'<?= url('/internships') ?>'" class="msg-header-btn is-primary">
                                <svg style="width: 13px; height: 13px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span>View Opportunity</span>
                            </a>
                            <a :href="'<?= url('/candidate/applications') ?>'" class="msg-header-btn">
                                <svg style="width: 13px; height: 13px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span>My Application</span>
                            </a>
                        </div>
                    </div>

                    <!-- Stream -->
                    <div class="msg-stream-wrap" id="cand-chat-stream">
                        <div class="msg-date-divider">
                            <span class="msg-date-pill">Conversation with recruiter</span>
                        </div>

                        <template x-for="(msg, msgIdx) in (activeThread && activeThread.messages ? activeThread.messages : [])" :key="msg.id || ('cand_msg_idx_' + msgIdx)">
                            <div class="msg-bubble-group" :class="msg.sender === 'candidate' ? 'is-outgoing' : 'is-incoming'">
                                <div class="msg-bubble">
                                    <div x-text="msg.text"></div>
                                </div>
                                <div class="msg-bubble-meta">
                                    <span x-text="msg.time"></span>
                                    <template x-if="msg.sender === 'candidate'">
                                        <svg style="width: 12px; height: 12px; color: #4F48E6;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Composer -->
                    <div class="msg-composer-wrap">
                        
                        <!-- Quick Response Chips -->
                        <div class="msg-quick-replies">
                            <button type="button" @click="insertQuickReply('I am available for an interview anytime this week. Please let me know what times work best!')" class="msg-quick-chip">
                                <span>Interview availability?</span>
                            </button>
                            <button type="button" @click="insertQuickReply('Here is the link to my live portfolio case study and design artifacts: https://portfolio.internconnect.ng/sarah')" class="msg-quick-chip">
                                <span>Here is my portfolio link</span>
                            </button>
                            <button type="button" @click="insertQuickReply('Thank you so much for the update! I look forward to the next steps.')" class="msg-quick-chip">
                                <span>Thank you for the update!</span>
                            </button>
                            <button type="button" @click="insertQuickReply('I have reviewed the offer details and am excited to accept the position!')" class="msg-quick-chip">
                                <span>Accept offer details</span>
                            </button>
                        </div>

                        <!-- Input Bar -->
                        <div class="msg-input-bar">
                            <textarea id="cand-composer-textarea"
                                      x-model="newMessage" 
                                      class="msg-textarea" 
                                      rows="1" 
                                      placeholder="Type your message to recruiter... (Press Enter to send)"
                                      @keydown.enter.prevent="sendMessage()"
                                      aria-label="Message content"></textarea>
                            
                            <button type="button" class="msg-attach-btn" title="Attach Document" aria-label="Attach File">
                                <svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                            </button>

                            <button type="button" @click="sendMessage()" class="msg-send-btn">
                                <span>Send</span>
                                <svg style="width: 13px; height: 13px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                </main>
            </div>

        </div><!-- /.db-content -->

        <!-- Dashboard Footer -->
        <?php include dirname(__DIR__, 2) . '/shared/components/db-footer.php'; ?>

    </div><!-- /.db-main -->
</div><!-- /.db-shell -->

