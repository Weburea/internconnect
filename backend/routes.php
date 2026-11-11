<?php

declare(strict_types=1);

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\InternshipController;
use App\Controllers\CandidateController;
use App\Controllers\EmployerController;
use App\Controllers\PaymentController;
use App\Controllers\ApiController;
use App\Controllers\AdminController;
use App\Controllers\InterviewController;

/** @var Router $router */

$router->get('/', [HomeController::class, 'index']);
$router->get('/pricing', [HomeController::class, 'pricing']);
$router->get('/elearning', [HomeController::class, 'elearning']);
$router->get('/how-it-works', [HomeController::class, 'howItWorks']);
$router->get('/contact', [HomeController::class, 'contact']);
$router->get('/about', [HomeController::class, 'about']);
$router->get('/testimonials', [HomeController::class, 'testimonials']);
$router->get('/success-stories', [HomeController::class, 'testimonials']);
$router->get('/refund', [HomeController::class, 'refund']);
$router->get('/cookies', [HomeController::class, 'cookies']);
$router->get('/privacy', [HomeController::class, 'privacy']);
$router->get('/terms', [HomeController::class, 'terms']);
$router->get('/sitemap.xml', [HomeController::class, 'sitemap']);
$router->get('/robots.txt', [HomeController::class, 'robots']);

// API Endpoints
$router->get('/api/universities', [ApiController::class, 'universities']);
$router->get('/payments', [PaymentController::class, 'checkout']);
$router->get('/pricing/upgrade', [PaymentController::class, 'checkout']);
$router->get('/checkout', [PaymentController::class, 'checkout']);
$router->post('/payment/initialize', [PaymentController::class, 'initialize']);
$router->get('/payment/callback', [PaymentController::class, 'callback']);
$router->get('/payment/success', [PaymentController::class, 'success']);
$router->get('/payment/declined', [PaymentController::class, 'declined']);
$router->get('/payment/cancelled', [PaymentController::class, 'cancelled']);
$router->post('/payment/webhook', [PaymentController::class, 'webhook']);
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'loginPost']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/register', [AuthController::class, 'register']);
$router->post('/register', [AuthController::class, 'registerPost']);
$router->get('/register/student', [AuthController::class, 'registerStudent']);
$router->post('/register/student/complete', [AuthController::class, 'registerStudentComplete']);
$router->get('/register/employer', [AuthController::class, 'registerEmployer']);
$router->post('/register/employer/complete', [AuthController::class, 'registerEmployerComplete']);
$router->get('/forgot-password', [AuthController::class, 'forgotPassword']);
$router->post('/forgot-password', [AuthController::class, 'forgotPasswordPost']);
$router->get('/verify-code', [AuthController::class, 'verifyCode']);
$router->post('/verify-code', [AuthController::class, 'verifyCodePost']);
$router->get('/reset-password', [AuthController::class, 'resetPassword']);
$router->post('/reset-password', [AuthController::class, 'resetPasswordPost']);
$router->get('/password-reset-success', [AuthController::class, 'passwordResetSuccess']);
$router->get('/welcome', [AuthController::class, 'welcome']);
$router->get('/welcome/employer', [AuthController::class, 'welcomeEmployer']);
$router->get('/welcome/success', [AuthController::class, 'welcomeSuccess']);
$router->get('/welcome-back', [AuthController::class, 'welcomeBack']);
// Candidate routes
$router->get('/candidate', [CandidateController::class, 'applications']);
$router->get('/candidate/applications', [CandidateController::class, 'applications']);
$router->get('/candidate/application', [CandidateController::class, 'applications']);
$router->get('/applications', [CandidateController::class, 'applications']);
$router->get('/my-applications', [CandidateController::class, 'applications']);
$router->get('/dashboard', [CandidateController::class, 'dashboard']);
$router->get('/candidate/dashboard', [CandidateController::class, 'dashboard']);
$router->get('/saved', [CandidateController::class, 'savedInternships']);
$router->get('/candidate/saved', [CandidateController::class, 'savedInternships']);
$router->get('/profile', [CandidateController::class, 'profileSettings']);
$router->get('/candidate/profile', [CandidateController::class, 'profileSettings']);
$router->post('/profile', [CandidateController::class, 'updateProfileSettings']);
$router->post('/candidate/profile', [CandidateController::class, 'updateProfileSettings']);
$router->get('/candidate/profile/resume', [CandidateController::class, 'streamResume']);
$router->post('/candidate/profile/resume', [CandidateController::class, 'uploadResume']);
$router->post('/candidate/profile/resume/delete', [CandidateController::class, 'deleteResume']);
$router->get('/offer', [CandidateController::class, 'reviewOffer']);
$router->get('/candidate/offer', [CandidateController::class, 'reviewOffer']);
$router->get('/candidate/offer/accepted', [CandidateController::class, 'offerAccepted']);
$router->get('/candidate/offer/success', [CandidateController::class, 'offerAccepted']);
$router->get('/offer/accepted', [CandidateController::class, 'offerAccepted']);
$router->get('/candidate/offer/declined', [CandidateController::class, 'offerDeclined']);
$router->get('/candidate/offer/rejected', [CandidateController::class, 'offerDeclined']);
$router->get('/offer/declined', [CandidateController::class, 'offerDeclined']);
$router->get('/candidate/offer/{slug}', [CandidateController::class, 'reviewOffer']);
$router->get('/candidate/offers', [CandidateController::class, 'reviewOffer']);
$router->get('/candidate/offers/{slug}', [CandidateController::class, 'reviewOffer']);
$router->get('/candidate/interview', [CandidateController::class, 'viewInterviewInvite']);
$router->get('/candidate/interview/invite', [CandidateController::class, 'viewInterviewInvite']);
$router->get('/candidate/interview/room', [CandidateController::class, 'interviewRoom']);
$router->get('/candidate/interview/room/{id}', [CandidateController::class, 'interviewRoom']);
$router->post('/candidate/interview/room/{id}/join', [CandidateController::class, 'joinInterviewRoom']);
$router->post('/candidate/interview/room/{id}/left', [CandidateController::class, 'leftInterviewRoom']);
$router->get('/candidate/interview/guide', [CandidateController::class, 'interviewGuide']);
$router->get('/candidate/interview-guide', [CandidateController::class, 'interviewGuide']);
$router->get('/candidate/interview/{slug}', [CandidateController::class, 'viewInterviewInvite']);
$router->get('/interview', [CandidateController::class, 'viewInterviewInvite']);
$router->get('/interview/invite', [CandidateController::class, 'viewInterviewInvite']);
$router->get('/interview/guide', [CandidateController::class, 'interviewGuide']);
$router->get('/interview-guide', [CandidateController::class, 'interviewGuide']);

// Help Center routes
$router->get('/help', [CandidateController::class, 'help']);
$router->get('/candidate/help', [CandidateController::class, 'help']);
$router->get('/help/search', [CandidateController::class, 'helpSearch']);
$router->get('/help/article', [CandidateController::class, 'helpArticle']);
$router->get('/help/article/{slug}', [CandidateController::class, 'helpArticle']);
$router->get('/help/{category}/{slug}', [CandidateController::class, 'helpArticle']);

// Notifications routes
$router->get('/notifications', [CandidateController::class, 'notifications']);
$router->get('/candidate/notifications', [CandidateController::class, 'notifications']);
$router->post('/candidate/notifications/mark-read', [CandidateController::class, 'markNotificationRead']);
$router->post('/candidate/notifications/mark-all-read', [CandidateController::class, 'markAllNotificationsRead']);
$router->post('/candidate/notifications/delete', [CandidateController::class, 'deleteNotification']);

// Candidate 1-on-1 Messages
$router->get('/candidate/messages', [CandidateController::class, 'messages']);
$router->get('/candidate/messages/thread/{id}', [CandidateController::class, 'getThreadMessagesApi']);
$router->get('/candidate/messages/{id}', [CandidateController::class, 'messages']);
$router->get('/messages', [CandidateController::class, 'messages']);
$router->post('/candidate/messages/send', [CandidateController::class, 'sendMessage']);
$router->post('/candidate/interview/respond', [CandidateController::class, 'respondInterview']);

// Candidate AI Career Assistant routes (Pro & Premium)
$router->get('/candidate/ai-assistant', [CandidateController::class, 'aiAssistant']);
$router->get('/candidate/ai-assistant/cv-analyze', [CandidateController::class, 'aiCvAnalyze']);
$router->post('/candidate/ai-assistant/cv-analyze', [CandidateController::class, 'processAiCvAnalyze']);
$router->get('/candidate/ai-assistant/cv-results', [CandidateController::class, 'aiCvResults']);
$router->get('/candidate/ai-assistant/matched', [CandidateController::class, 'aiMatchedInternships']);
$router->get('/candidate/ai-assistant/find-internships', [CandidateController::class, 'aiMatchedInternships']);
$router->get('/candidate/ai-assistant/interview-prep', [CandidateController::class, 'aiInterviewPrep']);
$router->post('/candidate/ai-assistant/interview-prep/evaluate', [CandidateController::class, 'evaluateMockAnswer']);
$router->get('/candidate/ai-assistant/cover-letter', [CandidateController::class, 'aiCoverLetter']);
$router->post('/candidate/ai-assistant/cover-letter/generate', [CandidateController::class, 'generateAiCoverLetter']);
$router->get('/candidate/ai-assistant/career-coach', [CandidateController::class, 'aiCareerCoach']);
$router->post('/candidate/ai-assistant/career-coach/roadmap', [CandidateController::class, 'generateAiRoadmap']);
$router->get('/ai-assistant', [CandidateController::class, 'aiAssistant']);

// Employer routes
$router->get('/employer/dashboard', [EmployerController::class, 'dashboard']);
$router->get('/employer/company', [EmployerController::class, 'companyProfile']);
$router->get('/employer/companies', [EmployerController::class, 'companyProfile']);
$router->get('/employer/profile', [EmployerController::class, 'profileSettings']);
$router->post('/employer/profile', [EmployerController::class, 'updateProfileSettings']);
$router->get('/employer/profile/logo', [EmployerController::class, 'streamLogo']);
$router->post('/employer/profile/logo', [EmployerController::class, 'uploadLogo']);
$router->get('/employer/profile/business-doc', [EmployerController::class, 'streamBusinessDoc']);
$router->post('/employer/profile/business-doc', [EmployerController::class, 'uploadBusinessDoc']);
$router->post('/employer/profile/business-doc/delete', [EmployerController::class, 'deleteBusinessDoc']);
$router->get('/employer/settings', [EmployerController::class, 'profileSettings']);
$router->post('/employer/settings', [EmployerController::class, 'updateProfileSettings']);
$router->get('/employer/profile/settings', [EmployerController::class, 'profileSettings']);
$router->get('/companies', [EmployerController::class, 'publicCompanyProfile']);
$router->get('/company', [EmployerController::class, 'publicCompanyProfile']);
$router->get('/company/{slug}', [EmployerController::class, 'publicCompanyProfile']);
$router->get('/companies/{slug}', [EmployerController::class, 'publicCompanyProfile']);

// Employer My Internships & Post Internship flow
$router->get('/employer/internships', [EmployerController::class, 'myInternships']);
$router->get('/employer/my-internships', [EmployerController::class, 'myInternships']);
$router->get('/employer/internships/create', [EmployerController::class, 'createInternship']);
$router->post('/employer/internships/create', [EmployerController::class, 'storeInternship']);
$router->get('/employer/post-internship', [EmployerController::class, 'createInternship']);
$router->post('/employer/post-internship', [EmployerController::class, 'storeInternship']);
$router->get('/employer/internships/review', [EmployerController::class, 'reviewInternship']);
$router->post('/employer/internships/publish', [EmployerController::class, 'publishInternship']);
$router->get('/employer/internships/success', [EmployerController::class, 'internshipSuccess']);
$router->get('/employer/post-internship/success', [EmployerController::class, 'internshipSuccess']);

// Employer Candidates & Applications
$router->get('/employer/candidates', [EmployerController::class, 'candidates']);
$router->get('/employer/applications', [EmployerController::class, 'candidates']);
$router->get('/employer/candidates/{id}', [EmployerController::class, 'candidateProfile']);
$router->get('/employer/candidate/{id}', [EmployerController::class, 'candidateProfile']);

// Employer Interview Scheduler
$router->get('/employer/interviews', [EmployerController::class, 'interviews']);
$router->get('/employer/interviews/schedule', [EmployerController::class, 'scheduleInterview']);
$router->get('/employer/interviews/schedule/{id}', [EmployerController::class, 'scheduleInterview']);
$router->post('/employer/interviews/schedule', [EmployerController::class, 'processScheduleInterview']);
$router->get('/employer/interviews/room', [EmployerController::class, 'interviewRoom']);
$router->get('/employer/interviews/room/{id}', [EmployerController::class, 'interviewRoom']);
$router->post('/api/interviews/room/score', [EmployerController::class, 'saveInterviewScore']);
$router->post('/employer/interviews/room/{id}/join', [EmployerController::class, 'joinInterviewRoom']);
$router->post('/employer/interviews/room/{id}/left', [EmployerController::class, 'leftInterviewRoom']);
$router->post('/employer/interviews/room/{id}/end', [EmployerController::class, 'endInterviewRoom']);

// Employer 1-on-1 Messages
$router->get('/employer/messages', [EmployerController::class, 'messages']);
$router->get('/employer/messages/thread/{id}', [EmployerController::class, 'getThreadMessagesApi']);
$router->get('/employer/messages/{id}', [EmployerController::class, 'messages']);
$router->post('/employer/messages/send', [EmployerController::class, 'sendMessage']);

// Employer Notifications
$router->get('/employer/notifications', [EmployerController::class, 'notifications']);
$router->post('/employer/notifications/mark-read', [EmployerController::class, 'markNotificationRead']);
$router->post('/employer/notifications/mark-all-read', [EmployerController::class, 'markAllNotificationsRead']);
$router->post('/employer/notifications/delete', [EmployerController::class, 'deleteNotification']);

// Employer AI Assistant (Free for Employers, Jenny AI)
$router->get('/employer/ai-assistant', [EmployerController::class, 'aiAssistant']);

// Employer Enterprise Analytics & Data Exports (Phase 5)
$router->get('/employer/analytics', [EmployerController::class, 'analytics']);
$router->get('/employer/analytics/export/candidates', [EmployerController::class, 'exportCandidates']);
$router->get('/employer/analytics/export/interviews', [EmployerController::class, 'exportInterviews']);
$router->get('/employer/analytics/export/billing', [EmployerController::class, 'exportBilling']);

// Internship & Application routes
$router->get('/internships', [InternshipController::class, 'index']);
$router->get('/search', [InternshipController::class, 'search']);
$router->get('/internship/{slug}', [InternshipController::class, 'show']);
$router->get('/apply/{slug}', [InternshipController::class, 'apply']);
$router->post('/apply/{slug}', [CandidateController::class, 'submitApplication']);

// ==============================================================================
// ADMIN MISSION CONTROL ROUTES & ACCESS CONTROL SUITE
// ==============================================================================
$router->get('/admin/login', [AdminController::class, 'login']);
$router->post('/admin/login', [AdminController::class, 'loginPost']);
$router->get('/admin/login/2fa', [AdminController::class, 'twoFactor']);
$router->post('/admin/login/2fa', [AdminController::class, 'twoFactorPost']);
$router->get('/admin/forgot-password', [AdminController::class, 'forgotPassword']);
$router->post('/admin/forgot-password', [AdminController::class, 'forgotPasswordPost']);
$router->get('/admin/reset-password', [AdminController::class, 'resetPassword']);
$router->post('/admin/reset-password', [AdminController::class, 'resetPasswordPost']);
$router->get('/admin/logout', [AdminController::class, 'logout']);
$router->post('/admin/logout', [AdminController::class, 'logout']);
$router->get('/admin', [AdminController::class, 'overview']);
$router->get('/admin/overview', [AdminController::class, 'overview']);
$router->get('/admin/dashboard', [AdminController::class, 'overview']);
$router->get('/admin/interns', [AdminController::class, 'interns']);
$router->get('/admin/employers', [AdminController::class, 'employers']);
$router->get('/admin/ai', [AdminController::class, 'ai']);
$router->get('/admin/ai/chat', [AdminController::class, 'aiChat']);
$router->get('/admin/elearning', [AdminController::class, 'elearning']);
$router->get('/admin/subscriptions', [AdminController::class, 'subscriptions']);
$router->get('/admin/settings', [AdminController::class, 'settings']);
$router->get('/admin/settings/team', [AdminController::class, 'settingsTeam']);
$router->get('/admin/interviews', [AdminController::class, 'interviews']);
$router->post('/admin/interviews/settings', [AdminController::class, 'saveInterviewSettings']);
$router->post('/admin/interviews/{id}/end', [AdminController::class, 'endInterviewRoom']);
$router->post('/admin/interviews/{id}/reopen', [AdminController::class, 'reopenInterviewRoom']);
$router->get('/admin/api/interviews/presence', [AdminController::class, 'interviewPresence']);
$router->get('/admin/document/stream', [AdminController::class, 'streamDocument']);
$router->get('/admin/api/live-sessions', [AdminController::class, 'liveSessions']);

// ── In-platform video interviews: in-call actions, files, chat, AI and history (docs/47-video-interviews.md) ──
$router->post('/employer/interviews/suggest-questions', [EmployerController::class, 'suggestSetupQuestions']);
foreach (['/employer/interviews/room', '/candidate/interview/room'] as $roomBase) {
    $router->post($roomBase . '/{id}/event', [InterviewController::class, 'roomEvent']);
    $router->post($roomBase . '/{id}/files', [InterviewController::class, 'uploadFile']);
    $router->get($roomBase . '/{id}/files/{fileId}', [InterviewController::class, 'downloadFile']);
    $router->post($roomBase . '/{id}/chat', [InterviewController::class, 'chatMessage']);
}
$router->post('/employer/interviews/room/{id}/scorecard', [InterviewController::class, 'saveScorecard']);
$router->post('/employer/interviews/room/{id}/suggest', [InterviewController::class, 'suggestQuestions']);
$router->get('/employer/interviews/{id}/report', [InterviewController::class, 'report']);
$router->post('/employer/interviews/{id}/report/ai', [InterviewController::class, 'generateSummary']);
$router->get('/candidate/interview/{id}/report', [InterviewController::class, 'report']);

// Admin interview operations
$router->post('/admin/interviews/schedule', [AdminController::class, 'scheduleInterviewAdmin']);
$router->get('/admin/interviews/{id}', [AdminController::class, 'interviewDetail']);
$router->get('/admin/interviews/{id}/files/{fileId}', [AdminController::class, 'interviewFile']);
$router->post('/admin/interviews/{id}/report/ai', [AdminController::class, 'interviewSummary']);
$router->post('/admin/interviews/{id}/status', [AdminController::class, 'interviewStatus']);
$router->get('/admin/api/interviews/live-map', [AdminController::class, 'interviewLiveMap']);