<?php

declare(strict_types=1);

/**
 * View: Contact Us
 * Route: /contact
 * Layout: app
 */
?>
<section class="ic-page-shell" x-data="{
    submitted: false,
    loading: false,
    category: 'support',
    formData: {
        name: '',
        email: '',
        role: 'candidate',
        subject: '',
        message: ''
    },
    reference: '',
    website: '',
    toast(text, type) {
        window.dispatchEvent(new CustomEvent('show-toast', { detail: { text: text, type: type } }));
    },
    async submitContact() {
        if (!this.formData.name || !this.formData.email || !this.formData.message) {
            this.toast('Please fill in all required fields.', 'error');
            return;
        }
        this.loading = true;
        try {
            // Stored and e-mailed to the support inbox (POST /contact, docs/54-email-newsletter-contact.md).
            const r = await fetch('<?= url('/contact') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(Object.assign({}, this.formData, { topic: this.category, website: this.website }))
            });
            const d = await r.json();
            if (d.success) {
                this.reference = d.reference;
                this.submitted = true;
                this.toast('Message sent. We will reply within 4 business hours.', 'success');
            } else {
                this.toast(d.error || 'Your message could not be sent. Please try again.', 'error');
            }
        } catch (e) {
            this.toast('Network error. Please check your connection and try again.', 'error');
        } finally {
            this.loading = false;
        }
    }
}">

    <!-- Hero Header Banner -->
    <header class="ic-page-hero">
        <div class="ic-container">
            <span class="ic-badge">
                <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Contact &amp; Support
            </span>
            <h1 class="ic-page-hero-title">Get in Touch with Our Team</h1>
            <p class="ic-page-hero-subtitle">
                Have questions about student applications, corporate CAC verification, or employer subscription plans? We're here to assist you promptly.
            </p>
        </div>
    </header>

    <div class="ic-container">

        <!-- Contact Layout Grid -->
        <div class="contact-layout-grid">

            <!-- Left: Direct Channels & Office Info -->
            <aside class="contact-sidebar-card">
                <h2 style="font-size: 18px; font-weight: 800; color: #000623; margin: 0 0 8px;">Direct Assistance</h2>
                <p style="font-size: 13.5px; color: #64748B; margin: 0 0 24px; line-height: 1.5;">
                    Reach out to our specialized desks directly for immediate escalation.
                </p>

                <div class="contact-channel-item">
                    <div class="contact-channel-icon">
                        <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="contact-channel-title">Candidate &amp; Technical Support</div>
                        <p class="contact-channel-desc">For login issues, application submission questions, and resume uploads.</p>
                        <a href="mailto:support@internconnect.ng" class="contact-channel-link">support@internconnect.ng</a>
                    </div>
                </div>

                <div class="contact-channel-item">
                    <div class="contact-channel-icon">
                        <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <div class="contact-channel-title">Employer &amp; CAC Verification</div>
                        <p class="contact-channel-desc">Expedited review for corporate accounts, hiring plans, and Paystack invoicing.</p>
                        <a href="mailto:employers@internconnect.ng" class="contact-channel-link">employers@internconnect.ng</a>
                    </div>
                </div>

                <div class="contact-channel-item">
                    <div class="contact-channel-icon">
                        <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <div class="contact-channel-title">University Partnerships</div>
                        <p class="contact-channel-desc">For student affairs deans and university career placement offices.</p>
                        <a href="mailto:partnerships@internconnect.ng" class="contact-channel-link">partnerships@internconnect.ng</a>
                    </div>
                </div>

                <div class="contact-channel-item">
                    <div class="contact-channel-icon">
                        <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="contact-channel-title">Corporate Headquarters</div>
                        <p class="contact-channel-desc">Plot 14, Victoria Island Financial District, Lagos, Nigeria.</p>
                        <span style="font-size: 12.5px; color: #64748B; font-weight: 500;">Hours: Mon – Fri, 8:00 AM – 6:00 PM (WAT)</span>
                    </div>
                </div>
            </aside>

            <!-- Right: Interactive Inquiry Form -->
            <div class="contact-form-card">
                <div x-show="!submitted">
                    <h2 style="font-size: 20px; font-weight: 800; color: #000623; margin: 0 0 8px;">Send an Inquiry</h2>
                    <p style="font-size: 14px; color: #64748B; margin: 0 0 24px;">
                        Fill out the details below. Our support agents respond in under 4 business hours.
                    </p>

                    <form @submit.prevent="submitContact()">
                        <input type="text" name="website" x-model="website" tabindex="-1" autocomplete="off" aria-hidden="true" style="position: absolute; left: -9999px; width: 1px; height: 1px; opacity: 0;">

                        <div class="contact-form-grid">
                            
                            <!-- Full Name -->
                            <div>
                                <label class="contact-label" for="contact-name">Full Name <span style="color: #DC2626;">*</span></label>
                                <input type="text" id="contact-name" class="contact-input" placeholder="e.g. Sarah Peters" x-model="formData.name" required>
                            </div>

                            <!-- Email Address -->
                            <div>
                                <label class="contact-label" for="contact-email">Email Address <span style="color: #DC2626;">*</span></label>
                                <input type="email" id="contact-email" class="contact-input" placeholder="e.g. sarah@example.com" x-model="formData.email" required>
                            </div>

                            <!-- Role / Account Type -->
                            <div>
                                <label class="contact-label">I Am A:</label>
                                <div style="display: flex; gap: 8px;">
                                    <button type="button" @click="formData.role = 'candidate'" class="ic-btn" :class="formData.role === 'candidate' ? 'ic-btn-primary' : 'ic-btn-secondary'" style="flex: 1; padding: 10px 12px; font-size: 13px;">
                                        Candidate
                                    </button>
                                    <button type="button" @click="formData.role = 'employer'" class="ic-btn" :class="formData.role === 'employer' ? 'ic-btn-primary' : 'ic-btn-secondary'" style="flex: 1; padding: 10px 12px; font-size: 13px;">
                                        Employer
                                    </button>
                                    <button type="button" @click="formData.role = 'partner'" class="ic-btn" :class="formData.role === 'partner' ? 'ic-btn-primary' : 'ic-btn-secondary'" style="flex: 1; padding: 10px 12px; font-size: 13px;">
                                        University
                                    </button>
                                </div>
                            </div>

                            <!-- Subject Topic -->
                            <div>
                                <label class="contact-label" for="contact-subject">Topic / Subject</label>
                                <input type="text" id="contact-subject" class="contact-input" placeholder="e.g. Question about CAC approval" x-model="formData.subject">
                            </div>

                            <!-- Message Content -->
                            <div class="contact-field-full">
                                <label class="contact-label" for="contact-message">Your Message <span style="color: #DC2626;">*</span></label>
                                <textarea id="contact-message" class="contact-textarea" placeholder="Describe how our support team can assist you today..." x-model="formData.message" required></textarea>
                            </div>

                        </div>

                        <div style="margin-top: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
                            <div style="font-size: 12.5px; color: #64748B;">
                                Protected by SSL encryption. Never share sensitive account passwords.
                            </div>
                            <button type="submit" class="ic-btn ic-btn-primary" :disabled="loading" style="padding: 12px 28px;">
                                <span x-show="!loading">Send Message</span>
                                <span x-show="loading" style="display: none; align-items: center; gap: 8px;">
                                    <svg style="width: 16px; height: 16px; animation: spin 1s linear infinite;" fill="none" viewBox="0 0 24 24">
                                        <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                    Sending...
                                </span>
                                <svg x-show="!loading" style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Submission Success State -->
                <div x-show="submitted" style="display: none; text-align: center; padding: 36px 12px;">
                    <div style="width: 60px; height: 60px; border-radius: 50%; background: #ECFDF5; color: #059669; display: flex; align-items: center; justify-content: center; margin: 0 auto 18px;">
                        <svg style="width: 32px; height: 32px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 style="font-size: 22px; font-weight: 800; color: #000623; margin: 0 0 8px;">Message Received!</h3>
                    <p style="font-size: 14.5px; color: #64748B; max-width: 440px; margin: 0 auto 24px; line-height: 1.6;">
                        Thank you for reaching out. A confirmation has been sent to your email, and our operations desk will reply within 4 business hours.
                    </p>
                    <p x-show="reference" style="display: none; font-size: 13px; color: #334155; margin: -12px 0 22px;">Reference: <strong x-text="reference" style="color: #4F48E6;"></strong></p>
                    <button type="button" @click="submitted = false; formData = { name: '', email: '', role: 'candidate', subject: '', message: '' }" class="ic-btn ic-btn-secondary">
                        <span>Send Another Inquiry</span>
                    </button>
                </div>
            </div>

        </div>

    </div>

</section>
