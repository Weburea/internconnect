<?php

declare(strict_types=1);

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\InternshipController;
use App\Controllers\CandidateController;

/** @var Router $router */

$router->get('/', [HomeController::class, 'index']);
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'loginPost']);
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
$router->get('/welcome-pro', [AuthController::class, 'welcomePro']);
$router->get('/dashboard', [CandidateController::class, 'dashboard']);
$router->get('/saved', [CandidateController::class, 'savedInternships']);
$router->get('/profile', [CandidateController::class, 'profileSettings']);
$router->post('/profile', [CandidateController::class, 'updateProfileSettings']);
$router->get('/internships', [InternshipController::class, 'index']);
$router->get('/internship/{slug}', [InternshipController::class, 'show']);
$router->get('/apply/{slug}', [InternshipController::class, 'apply']);
$router->post('/apply/{slug}', [CandidateController::class, 'submitApplication']);


