<?php
declare(strict_types=1);
/**
 * View: Candidate Review Offer
 * Route: /candidate/offer, /candidate/offer/{slug}
 * Layout: dashboard (Candidate Portal)
 * Matches latest visual design specs (Image 1 & Image 2)
 * 
 * @var array $candidate Candidate profile information
 * @var array $offer     Offer letter, summary and job description details
 */

$name      = $candidate['name'] ?? 'Sarah Peters';
$firstName = explode(' ', trim($name))[0];
$role      = $candidate['role'] ?? 'Student Applicant';
$avatar    = $candidate['avatar'] ?? null;
?>

<div class="db-shell" x-data="{
    statusState: 'pending', // 'pending' | 'accepted' | 'rejected'
    showAcceptModal: false,
    showRejectModal: false,
    showMessageModal: false,
    openReasonSelect: false,
    rejectReason: '',
    rejectReasonLabel: 'Select a reason',
    rejectNote: '',
    messageSubject: 'Question regarding Data Analyst Intern offer',
    messageBody: 'Hello Akinyele,\n\nThank you so much for extending this offer! I am reviewing the details and had a quick question regarding...',

    acceptOffer() {
        this.statusState = 'accepted';
        this.showAcceptModal = false;
        window.location.href = '<?= url('/candidate/offer/accepted') ?>';
    },

    rejectOffer() {
        this.statusState = 'rejected';
        this.showRejectModal = false;
        this.openReasonSelect = false;
        window.location.href = '<?= url('/candidate/offer/declined') ?>';
    },

    sendMessage() {
        this.showMessageModal = false;
        window.dispatchEvent(new CustomEvent('show-toast', { detail: { text: 'Your message has been sent to Akinyele Okoro.', message: 'Your message has been sent to Akinyele Okoro.', type: 'success' } }));
    },

    pdfBusy: false,
    // Real A4 offer letter PDF (jsPDF, vector text). The letter text is read from the page so both always match.
    async downloadPdf() {
        if (this.pdfBusy) return;
        this.pdfBusy = true;
        const toast = (text, type) => window.dispatchEvent(new CustomEvent('show-toast', { detail: { text: text, message: text, type: type } }));
        try {
            if (!window.jspdf) {
                await new Promise((resolve, reject) => { const s = document.createElement('script'); s.src = '<?= asset('assets/js/jspdf.umd.min.js') ?>'; s.onload = resolve; s.onerror = reject; document.head.appendChild(s); });
            }
            const o = JSON.parse(document.getElementById('offer-pdf-data').textContent);
            const clean = (el) => el.textContent.replace(/\s+/g, ' ').replace(/₦/g, 'NGN ').trim();
            const paragraphs = Array.from(document.querySelectorAll('.db-offer-letter-body .db-offer-letter-p:not(.db-offer-warm-regards)')).map(clean).filter(Boolean);
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ unit: 'mm', format: 'a4' });
            const L = 22, R = 188, W = R - L, navy = [0, 6, 35], indigo = [79, 72, 230], slate = [71, 85, 105], muted = [148, 163, 184];
            doc.setProperties({ title: o.company + ' offer letter - ' + o.name, author: o.company, subject: 'Internship offer', creator: 'InternConnect' });
            // Letterhead
            doc.setFillColor.apply(doc, indigo); doc.rect(0, 0, 210, 4, 'F');
            doc.setFont('helvetica', 'bold'); doc.setFontSize(20); doc.setTextColor.apply(doc, navy); doc.text(o.company, L, 24);
            doc.setFont('helvetica', 'normal'); doc.setFontSize(9.5); doc.setTextColor.apply(doc, slate); doc.text(o.location, L, 30);
            doc.setFontSize(9); doc.setTextColor.apply(doc, muted); doc.text('Offer reference ' + o.reference, R, 24, { align: 'right' }); doc.text(o.issued, R, 30, { align: 'right' });
            doc.setDrawColor(226, 232, 240); doc.setLineWidth(0.4); doc.line(L, 36, R, 36);
            // Title + letter
            doc.setFont('helvetica', 'bold'); doc.setFontSize(15); doc.setTextColor.apply(doc, navy); doc.text('Offer of Internship: ' + o.role, L, 48);
            let y = 60;
            const para = (text, opts) => {
                doc.setFont('helvetica', (opts && opts.bold) ? 'bold' : 'normal'); doc.setFontSize((opts && opts.size) || 10.5); doc.setTextColor.apply(doc, (opts && opts.color) || [30, 41, 59]);
                const lines = doc.splitTextToSize(text, W);
                if (y + lines.length * 5.4 > 272) { doc.addPage(); y = 24; }
                doc.text(lines, L, y, { lineHeightFactor: 1.55 }); y += lines.length * 5.4 + 4;
            };
            para('Dear ' + o.name + ',');
            paragraphs.forEach(p => para(p));
            para('Warm regards,');
            y += 6; para(o.signatory, { bold: true }); y -= 3; para(o.signatoryTitle + ', ' + o.company, { color: slate, size: 9.5 });
            // Offer summary
            y += 4; if (y > 220) { doc.addPage(); y = 24; }
            doc.setFillColor(248, 250, 252); const rows = [['Position', o.role], ['Location', o.location], ['Type', o.type], ['Duration', o.duration], ['Start date', o.start], ['Respond by', o.deadline]].filter(r => r[1]);
            doc.roundedRect(L, y, W, 10 + rows.length * 7, 3, 3, 'F');
            doc.setFont('helvetica', 'bold'); doc.setFontSize(8); doc.setTextColor.apply(doc, indigo); doc.text('OFFER SUMMARY', L + 6, y + 7, { charSpace: 0.4 });
            rows.forEach((r, i) => {
                doc.setFont('helvetica', 'normal'); doc.setFontSize(9.5); doc.setTextColor.apply(doc, slate); doc.text(r[0], L + 6, y + 14 + i * 7);
                doc.setFont('helvetica', 'bold'); doc.setTextColor.apply(doc, navy); doc.text(doc.splitTextToSize(String(r[1]), W - 52)[0], L + 46, y + 14 + i * 7);
            });
            y += 18 + rows.length * 7;
            if (o.responsibilities.length) {
                if (y > 240) { doc.addPage(); y = 24; }
                para('What you will do', { bold: true, size: 11, color: navy }); y -= 2;
                o.responsibilities.forEach(t => { doc.setFillColor.apply(doc, indigo); doc.circle(L + 1.5, y - 1.3, 0.9, 'F'); const lines = doc.splitTextToSize(t, W - 7); doc.setFont('helvetica', 'normal'); doc.setFontSize(10); doc.setTextColor(30, 41, 59); if (y + lines.length * 5 > 272) { doc.addPage(); y = 24; } doc.text(lines, L + 5, y, { lineHeightFactor: 1.5 }); y += lines.length * 5 + 2; });
            }
            // Footer on every page
            const pages = doc.getNumberOfPages();
            for (let i = 1; i <= pages; i++) {
                doc.setPage(i); doc.setDrawColor(226, 232, 240); doc.line(L, 282, R, 282);
                doc.setFont('helvetica', 'normal'); doc.setFontSize(8); doc.setTextColor.apply(doc, muted);
                doc.text('Delivered through InternConnect' + (o.verified ? ' by a verified employer' : '') + '. Accept or decline in your InternConnect account.', L, 287);
                doc.text('Page ' + i + ' of ' + pages, R, 287, { align: 'right' });
            }
            doc.save(o.file + '.pdf');
            toast('Offer letter downloaded as PDF.', 'success');
        } catch (e) {
            toast('The PDF could not be created. Please try again.', 'error');
        } finally {
            this.pdfBusy = false;
        }
    }
}">


    <!-- ══ SIDEBAR ══ -->
    <?php 
    $activeNav = 'applications';
    include dirname(__DIR__, 2) . '/shared/components/candidate-sidebar.php'; 
    ?>

    <!-- ══ MAIN ══ -->
    <div class="db-main">

        <!-- ══ TOP NAVBAR ══ -->
        <?php include dirname(__DIR__, 2) . '/shared/components/candidate-topnav.php'; ?>

        <!-- ══ CONTENT ══ -->
        <div class="db-content">

            <!-- Breadcrumb Navigation -->
            <div class="db-offer-breadcrumb">
                <a href="<?= url('/candidate/applications') ?>" class="db-offer-breadcrumb-link">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <span>Back to Applications</span>
                </a>
            </div>

            <!-- Page Title Header Block (Image 2) -->
            <div class="db-offer-header">
                <div class="db-offer-badge">
                    <svg style="width: 12px; height: 12px; margin-right: 4px;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    OFFER RECEIVED
                </div>
                <h1 class="db-offer-title">You've received an offer, <?= htmlspecialchars($firstName) ?>.</h1>
                <p class="db-offer-subtitle">Congratulations! Stellar Systems would like to invite you to join their team as a Data Analyst Intern.</p>
            </div>

            <!-- Application Summary Bar (Image 1 Top Bar, 3-dots removed) -->
            <div class="db-offer-summary-card">
                <!-- Col 1: Initial & Role -->
                <div class="db-offer-summary-company">
                    <div class="db-offer-summary-avatar" style="background-color: <?= htmlspecialchars($offer['letter_bg']) ?>; color: <?= htmlspecialchars($offer['letter_color']) ?>;">
                        <?= htmlspecialchars($offer['letter']) ?>
                    </div>
                    <div class="db-offer-summary-meta">
                        <div class="db-offer-company-name-row">
                            <span class="db-offer-company-name"><?= htmlspecialchars($offer['company']) ?></span>
                            <?php if (!empty($offer['verified'])): ?>
                                <span class="db-offer-verified-badge" aria-label="Verified Employer">
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="db-offer-role-title"><?= htmlspecialchars($offer['role']) ?></div>
                        <div class="db-offer-tags-row">
                            <?php foreach ($offer['tags'] as $tag): ?>
                                <span class="db-offer-tag-pill"><?= htmlspecialchars($tag) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Col 2: Status -->
                <div class="db-offer-summary-col">
                    <span class="db-offer-col-header">STATUS</span>
                    <span class="db-offer-status-badge status-applied" x-text="statusState === 'accepted' ? 'Offer Accepted' : (statusState === 'rejected' ? 'Offer Declined' : 'Applied')">
                        <?= htmlspecialchars($offer['status']) ?>
                    </span>
                </div>

                <!-- Col 3: Current Stage -->
                <div class="db-offer-summary-col">
                    <span class="db-offer-col-header">CURRENT STAGE</span>
                    <span class="db-offer-stage-badge stage-applied" x-text="statusState === 'accepted' ? 'Offer Accepted' : (statusState === 'rejected' ? 'Offer Declined' : 'Application Submitted')">
                        <?= htmlspecialchars($offer['current_stage']) ?>
                    </span>
                </div>

                <!-- Col 4: Applied On -->
                <div class="db-offer-summary-col db-offer-summary-date">
                    <span class="db-offer-col-header">APPLIED ON</span>
                    <div class="db-offer-date-val">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span><?= htmlspecialchars($offer['applied_date']) ?></span>
                    </div>
                </div>
            </div>

            <!-- Two-Column Cards Grid (Image 1) -->
            <div class="db-offer-grid">
                
                <!-- ══ LEFT CARD: Offer Letter ══ -->
                <div class="db-offer-letter-card">
                    <div class="db-offer-card-header">
                        <div class="db-offer-card-header-left">
                            <div class="db-offer-icon-box bg-purple-light">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="db-offer-card-title">Offer Letter</h3>
                            <span class="db-offer-pill-badge">OFFER RECEIVED</span>
                        </div>
                        <button type="button" class="db-offer-download-btn" @click="downloadPdf()">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            <span>Download PDF</span>
                        </button>
                    </div>

                    <!-- Letter Body -->
                    <div class="db-offer-letter-body">
                        <p class="db-offer-letter-salutation">Dear <?= htmlspecialchars($name) ?>,</p>
                        
                        <p class="db-offer-letter-p">
                            We are delighted to offer you the position of <strong>Data Analyst Intern</strong> at <strong>Stellar Systems</strong>, commencing <strong><?= htmlspecialchars($offer['commencement_date']) ?></strong>. After reviewing your application and speaking with you during your interview, we are confident that your analytical mindset, academic background in Interaction Design and genuine curiosity about data-driven solutions make you an excellent fit for our team.
                        </p>
                        
                        <p class="db-offer-letter-p">
                            During your time with us, you will work alongside our senior data team on live projects, build and maintain dashboards, support product analytics reporting and gain hands-on experience with tools including Python, SQL, Tableau and Google BigQuery.
                        </p>
                        
                        <p class="db-offer-letter-p">
                            To confirm your acceptance, please respond by <strong><?= htmlspecialchars($offer['response_deadline']) ?></strong>. We are excited about the possibility of welcoming you to the Stellar Systems family and look forward to supporting your growth.
                        </p>
                        
                        <p class="db-offer-letter-p db-offer-warm-regards">Warm regards,</p>
                        
                        <div class="db-offer-signature-block">
                            <div class="db-offer-signatory-name"><?= htmlspecialchars($offer['signatory_name']) ?></div>
                            <div class="db-offer-signatory-role"><?= htmlspecialchars($offer['signatory_title'] . ', ' . $offer['company']) ?></div>
                        </div>
                    </div>
                </div>

                <!-- ══ RIGHT CARD: Job Description ══ -->
                <div class="db-offer-job-card">
                    <div class="db-offer-card-header">
                        <div class="db-offer-card-header-left">
                            <div class="db-offer-icon-box bg-blue-light">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <h3 class="db-offer-card-title">Job Description</h3>
                        </div>
                    </div>

                    <div class="db-offer-job-body">
                        <!-- Position -->
                        <div class="db-offer-job-section">
                            <div class="db-offer-job-meta-label">Position</div>
                            <div class="db-offer-job-meta-val bold"><?= htmlspecialchars($offer['job_title']) ?></div>
                        </div>

                        <!-- 2-Col Location & Type -->
                        <div class="db-offer-job-meta-row">
                            <div class="db-offer-job-meta-item">
                                <div class="db-offer-meta-icon-label">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>Location</span>
                                </div>
                                <div class="db-offer-job-meta-val"><?= htmlspecialchars($offer['location']) ?></div>
                            </div>
                            <div class="db-offer-job-meta-item">
                                <div class="db-offer-meta-icon-label">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Type</span>
                                </div>
                                <div class="db-offer-job-meta-val"><?= htmlspecialchars($offer['type']) ?></div>
                            </div>
                        </div>

                        <!-- Duration -->
                        <div class="db-offer-job-section">
                            <div class="db-offer-meta-icon-label">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Duration</span>
                            </div>
                            <div class="db-offer-job-meta-val"><?= htmlspecialchars($offer['duration']) ?></div>
                        </div>

                        <!-- Key Responsibilities -->
                        <div class="db-offer-job-section">
                            <div class="db-offer-section-heading">Key Responsibilities</div>
                            <ul class="db-offer-check-list">
                                <?php foreach ($offer['responsibilities'] as $resp): ?>
                                    <li>
                                        <svg class="db-offer-check-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span><?= htmlspecialchars($resp) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <!-- Required Skills -->
                        <div class="db-offer-job-section">
                            <div class="db-offer-section-heading">Required Skills</div>
                            <div class="db-offer-skills-wrap">
                                <?php foreach ($offer['skills'] as $skill): ?>
                                    <span class="db-offer-skill-badge"><?= htmlspecialchars($skill) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- About the Company Highlight Box -->
                        <div class="db-offer-about-box">
                            <div class="db-offer-about-title">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span>About the Company</span>
                            </div>
                            <p class="db-offer-about-desc"><?= htmlspecialchars($offer['about_company']) ?></p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ══ BOTTOM ACTION FOOTER BAR (Image 1) ══ -->
            <div class="db-offer-action-bar">
                <div class="db-offer-action-left">
                    <template x-if="statusState === 'pending'">
                        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                            <button type="button" class="db-offer-btn-accept" @click="showAcceptModal = true">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Accept Offer</span>
                            </button>

                            <button type="button" class="db-offer-btn-reject" @click="showRejectModal = true">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span>Reject Offer</span>
                            </button>

                            <button type="button" class="db-offer-btn-message" @click="showMessageModal = true">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <span>Message Recruiter</span>
                            </button>
                        </div>
                    </template>

                    <template x-if="statusState === 'accepted'">
                        <div class="db-offer-confirmed-state success">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Offer Accepted! You are scheduled to start February 1, 2027.</span>
                        </div>
                    </template>

                    <template x-if="statusState === 'rejected'">
                        <div class="db-offer-confirmed-state declined">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span>Offer Declined. Your response has been submitted.</span>
                        </div>
                    </template>
                </div>

                <div class="db-offer-action-right">
                    <div class="db-offer-note">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>You can still reject this offer if you change your mind. No worries!</span>
                    </div>
                </div>
            </div>

        </div><!-- /.db-content -->

        <!-- Dashboard Footer -->
        <?php include __DIR__ . '/../../shared/components/db-footer.php'; ?>



    </div><!-- /.db-main -->

    <!-- ══ 1. ACCEPT OFFER MODAL (Redesigned with Recap Card & Height) ══ -->
    <div x-show="showAcceptModal" class="db-offer-modal-backdrop" style="display: none;" @keydown.escape.window="showAcceptModal = false">
        <div class="db-offer-modal-dialog" @click.outside="showAcceptModal = false">
            <div class="db-offer-modal-icon-wrap accept">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h3 class="db-offer-modal-title">Accept Internship Offer</h3>
            <p class="db-offer-modal-body">
                By clicking <strong>Confirm & Accept</strong>, you are officially accepting the internship offer from <strong>Stellar Systems</strong>.
            </p>
            
            <!-- Summary recap card -->
            <div class="db-offer-modal-summary-card">
                <div class="db-offer-modal-summary-row">
                    <span class="db-offer-modal-summary-label">Position</span>
                    <span class="db-offer-modal-summary-val">Data Analyst Intern</span>
                </div>
                <div class="db-offer-modal-summary-row">
                    <span class="db-offer-modal-summary-label">Company</span>
                    <span class="db-offer-modal-summary-val">Stellar Systems</span>
                </div>
                <div class="db-offer-modal-summary-row">
                    <span class="db-offer-modal-summary-label">Job Type</span>
                    <span class="db-offer-modal-summary-val"><?= htmlspecialchars($offer['type'] ?? 'Internship') ?></span>
                </div>
                <div class="db-offer-modal-summary-row">
                    <span class="db-offer-modal-summary-label">Duration</span>
                    <span class="db-offer-modal-summary-val">6 Months (Remote)</span>
                </div>
            </div>

            <div class="db-offer-modal-actions">
                <button type="button" class="db-offer-modal-btn-cancel" @click="showAcceptModal = false">Review Again</button>
                <button type="button" class="db-offer-modal-btn-primary" @click="acceptOffer()">
                    <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Confirm & Accept</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ══ 2. DECLINE OFFER MODAL (Redesigned with Custom Select Dropdown) ══ -->
    <div x-show="showRejectModal" class="db-offer-modal-backdrop" style="display: none;" @keydown.escape.window="showRejectModal = false; openReasonSelect = false;">
        <div class="db-offer-modal-dialog" @click.outside="showRejectModal = false; openReasonSelect = false;">
            <div class="db-offer-modal-icon-wrap decline">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <h3 class="db-offer-modal-title">Decline Internship Offer</h3>
            <p class="db-offer-modal-body">
                Are you sure you want to decline the offer from <strong>Stellar Systems</strong>? This will release the position to other applicants.
            </p>

            <!-- Custom Select Dropdown Component -->
            <div class="db-modal-form-group" @click.outside="openReasonSelect = false">
                <label class="db-modal-field-label">Reason for declining (Optional)</label>
                <div class="db-custom-select">
                    <button type="button" 
                            class="db-custom-select-trigger" 
                            :class="{ 'is-active': openReasonSelect, 'has-value': rejectReason !== '' }"
                            @click="openReasonSelect = !openReasonSelect">
                        <span x-text="rejectReasonLabel"></span>
                        <svg class="db-custom-select-chevron" :class="{ 'is-rotated': openReasonSelect }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div x-show="openReasonSelect" 
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-[-6px] scale-98"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-[-6px] scale-98"
                         class="db-custom-select-dropdown" 
                         style="display: none;">
                        
                        <div class="db-custom-select-option" 
                             :class="{ 'is-selected': rejectReason === '' }"
                             @click="rejectReason = ''; rejectReasonLabel = 'Select a reason'; openReasonSelect = false">
                            <span>Select a reason</span>
                            <template x-if="rejectReason === ''">
                                <svg class="db-option-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </template>
                        </div>
                        
                        <div class="db-custom-select-option" 
                             :class="{ 'is-selected': rejectReason === 'accepted_other' }"
                             @click="rejectReason = 'accepted_other'; rejectReasonLabel = 'Accepted another offer'; openReasonSelect = false">
                            <span>Accepted another offer</span>
                            <template x-if="rejectReason === 'accepted_other'">
                                <svg class="db-option-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </template>
                        </div>

                        <div class="db-custom-select-option" 
                             :class="{ 'is-selected': rejectReason === 'location' }"
                             @click="rejectReason = 'location'; rejectReasonLabel = 'Location / Remote preference'; openReasonSelect = false">
                            <span>Location / Remote preference</span>
                            <template x-if="rejectReason === 'location'">
                                <svg class="db-option-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </template>
                        </div>

                        <div class="db-custom-select-option" 
                             :class="{ 'is-selected': rejectReason === 'compensation' }"
                             @click="rejectReason = 'compensation'; rejectReasonLabel = 'Compensation / Terms'; openReasonSelect = false">
                            <span>Compensation / Terms</span>
                            <template x-if="rejectReason === 'compensation'">
                                <svg class="db-option-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </template>
                        </div>

                        <div class="db-custom-select-option" 
                             :class="{ 'is-selected': rejectReason === 'personal' }"
                             @click="rejectReason = 'personal'; rejectReasonLabel = 'Personal reasons'; openReasonSelect = false">
                            <span>Personal reasons</span>
                            <template x-if="rejectReason === 'personal'">
                                <svg class="db-option-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Optional Personal Reason / Notes Textarea -->
            <div class="db-modal-form-group">
                <label class="db-modal-field-label">Additional Notes / Personal Reason (Optional)</label>
                <textarea x-model="rejectNote" rows="3" class="db-modal-textarea" style="min-height: 80px;" placeholder="Tell them why you are declining or provide any additional context..."></textarea>
            </div>

            <div class="db-offer-modal-actions">
                <button type="button" class="db-offer-modal-btn-cancel" @click="showRejectModal = false; openReasonSelect = false;">Go Back</button>
                <button type="button" class="db-offer-modal-btn-danger" @click="rejectOffer()">
                    <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span>Confirm Decline</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ══ 3. MESSAGE RECRUITER MODAL (Redesigned with Recruiter Profile Box & Focus Rings) ══ -->
    <div x-show="showMessageModal" class="db-offer-modal-backdrop" style="display: none;" @keydown.escape.window="showMessageModal = false">
        <div class="db-offer-modal-dialog" @click.outside="showMessageModal = false">
            <div class="db-offer-modal-icon-wrap message">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <h3 class="db-offer-modal-title">Message Recruiter</h3>
            
            <!-- Recruiter Profile Badge Box -->
            <div class="db-modal-recruiter-box">
                <div class="db-modal-recruiter-avatar">AO</div>
                <div class="db-modal-recruiter-info">
                    <div class="db-modal-recruiter-name"><?= htmlspecialchars($offer['signatory_name'] ?? 'Akinyele Okoro') ?></div>
                    <div class="db-modal-recruiter-role"><?= htmlspecialchars($offer['signatory_title'] ?? $offer['signatory_role'] ?? 'Talent Acquisition Lead, Stellar Systems') ?></div>
                </div>
            </div>

            <div class="db-modal-form-group">
                <label class="db-modal-field-label">Subject</label>
                <input type="text" x-model="messageSubject" class="db-modal-input" placeholder="Enter message subject...">
            </div>

            <div class="db-modal-form-group">
                <label class="db-modal-field-label">Message</label>
                <textarea x-model="messageBody" rows="4" class="db-modal-textarea" placeholder="Write your message here..."></textarea>
            </div>

            <div class="db-offer-modal-actions">
                <button type="button" class="db-offer-modal-btn-cancel" @click="showMessageModal = false">Cancel</button>
                <button type="button" class="db-offer-modal-btn-primary" @click="sendMessage()">
                    <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    <span>Send Message</span>
                </button>
            </div>
        </div>
    </div>

</div>


<?php
// Data for the offer letter PDF (downloadPdf above). JSON in a script block: never executed, safely escaped.
$offerPdf = [
    'name'             => $name,
    'company'          => (string)($offer['company'] ?? ''),
    'role'             => (string)($offer['job_title'] ?? $offer['role'] ?? ''),
    'location'         => (string)($offer['location'] ?? ''),
    'type'             => (string)($offer['type'] ?? ''),
    'duration'         => (string)($offer['duration'] ?? ''),
    'start'            => (string)($offer['commencement_date'] ?? ''),
    'deadline'         => (string)($offer['response_deadline'] ?? ''),
    'signatory'        => (string)($offer['signatory_name'] ?? ''),
    'signatoryTitle'   => (string)($offer['signatory_title'] ?? ''),
    'responsibilities' => array_values(array_map('strval', (array)($offer['responsibilities'] ?? []))),
    'verified'         => !empty($offer['verified']),
    'reference'        => 'OFR-' . strtoupper(substr(hash('sha256', ($offer['company'] ?? '') . '|' . ($offer['role'] ?? '') . '|' . $name), 0, 8)),
    'issued'           => date('j F Y'),
    'file'             => preg_replace('/[^A-Za-z0-9]+/', '-', ($offer['company'] ?? 'Offer') . ' offer letter ' . $name),
];
?>
<script type="application/json" id="offer-pdf-data"><?= json_encode($offerPdf, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?></script>
