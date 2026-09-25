<?php

declare(strict_types=1);

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\InternshipController;
use App\Controllers\CandidateController;
use App\Controllers\EmployerController;
use App\Controllers\PaymentController;

/** @var Router $router */

$router->get('/', [HomeController::class, 'index']);
$router->get('/pricing', [HomeController::class, 'pricing']);
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
$router->get('/register/employer', [AuthController::class, 'registerEmployer']);
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

// Employer routes
$router->get('/employer/dashboard', [EmployerController::class, 'dashboard']);
$router->get('/employer/company', [EmployerController::class, 'companyProfile']);
$router->get('/employer/companies', [EmployerController::class, 'companyProfile']);
$router->get('/employer/profile', [EmployerController::class, 'profileSettings']);
$router->post('/employer/profile', [EmployerController::class, 'updateProfileSettings']);
$router->get('/employer/settings', [EmployerController::class, 'profileSettings']);
$router->post('/employer/settings', [EmployerController::class, 'updateProfileSettings']);
$router->get('/employer/profile/settings', [EmployerController::class, 'profileSettings']);
$router->get('/companies', [EmployerController::class, 'companyProfile']);
$router->get('/company', [EmployerController::class, 'companyProfile']);
$router->get('/company/{slug}', [EmployerController::class, 'companyProfile']);

// Internship & Application routes
$router->get('/internships', [InternshipController::class, 'index']);
$router->get('/internship/{slug}', [InternshipController::class, 'show']);
$router->get('/apply/{slug}', [InternshipController::class, 'apply']);
$router->post('/apply/{slug}', [CandidateController::class, 'submitApplication']);


