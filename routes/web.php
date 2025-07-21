<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\LoginController;
use App\Mail\ApprovalRequestMail;
use App\Mail\NewLoginNotification;
use App\Models\User;

// ✅ Registration Routes
Route::get('/signup', [LoginController::class, 'showRegister'])->name('signup');
Route::post('/store', [LoginController::class, 'register'])->name('register.store');

// ✅ Login Routes
Route::get('/', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// ✅ Password Reset Routes (handled inside LoginController)
Route::get('/forgot-password', [LoginController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [LoginController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [LoginController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('password.update');

// ✅ Authenticated Routes
Route::middleware('auth')->group(function () {

    // Admin Dashboard
    Route::get('/admin/dashboard', [LoginController::class, 'adminDashboard'])->name('admin.dashboard');

    // User Dashboard
    Route::get('/user/dashboard', [LoginController::class, 'userDashboard'])->name('user.dashboard');

    // Admin Approves Users
    Route::post('/admin/approve/{id}', [LoginController::class, 'approve'])->name('admin.approve');

    // Optional: View admin blade directly (for testing only)
    Route::get('/dashboard-admin', function () {
        return view('dashboard-admin');
    })->name('dashboard.admin.view');
});

// ✅ Test Mail Routes

// Test sending approval request email to admin
Route::get('/test-admin-mail', function () {
    $user = User::where('role', 'user')->first();
    if (!$user) return '❌ No user found to test approval email.';

    try {
        Mail::to('dikshethasriss@gmail.com')->send(new ApprovalRequestMail($user));
        return '✅ Test approval email sent.';
    } catch (\Exception $e) {
        return '❌ Mail failed: ' . $e->getMessage();
    }
});

// Test sending login notification email to admin
Route::get('/test-login-mail', function () {
    $user = User::where('role', 'user')->first();
    if (!$user) return '❌ No user found to test login notification.';

    try {
        Mail::to('dikshethasriss@gmail.com')->send(new NewLoginNotification($user));
        return '✅ Test login notification sent.';
    } catch (\Exception $e) {
        return '❌ Mail failed: ' . $e->getMessage();
    }
});
