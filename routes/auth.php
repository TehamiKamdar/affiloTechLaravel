<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\VerificationController;

// Guest routes


    // Home route
    Route::get('/get-started', function () {
        return view('auth.login');
    })->name('get-started');

    // Login route
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Register route
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Password reset routes
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

    // Confirm password route
    Route::get('/confirm-password', [ConfirmPasswordController::class, 'showConfirmForm'])->name('password.confirm');
    Route::post('/confirm-password', [ConfirmPasswordController::class, 'confirm']);
    
    // Forgot password route
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    // Email verification routes
    Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::post('/email/verification', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/verification/code', [VerificationController::class, 'verifyCode'])->name('verification.verify.code');
    Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend.email');


// Authenticated routes
Route::group(['middleware' => ['auth', 'verified']], function () {

    // Home route after login
    Route::get('/home', [HomeController::class, 'redirectTo'])->name('home');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
