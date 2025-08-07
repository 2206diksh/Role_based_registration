<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

// --------------------------------------------------------------------------
// Public Routes (Guest only)
// --------------------------------------------------------------------------

// 🔐 Login
Route::get('/', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// 📝 Registration
Route::get('/signup', [LoginController::class, 'showRegister'])->name('signup');
Route::post('/store', [LoginController::class, 'register'])->name('register.store');

// 🔁 Password Reset
Route::get('/forgot-password', [LoginController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [LoginController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [LoginController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('password.update');


// --------------------------------------------------------------------------
// Authenticated Routes (Only for logged-in users)
// --------------------------------------------------------------------------
Route::middleware('auth')->group(function () {

    // ----------------------------------------------------------------------
    // Admin Routes
    // ----------------------------------------------------------------------

    // 🧑‍💼 Admin Dashboard
    Route::get('/admin/dashboard', [LoginController::class, 'adminDashboard'])->name('admin.dashboard');

    // ✅ Approve Users
    Route::post('/admin/approve/{id}', [LoginController::class, 'approve'])->name('admin.approve');

    // 📋 User List
    Route::get('/admin/users', [LoginController::class, 'listUsers'])->name('users.list');

    // 👁️ View Single User
    Route::get('/admin/users/{id}/view', [LoginController::class, 'showUser'])->name('users.show');

    // ✏️ Edit User
    Route::get('/admin/users/{id}/edit', [LoginController::class, 'editUser'])->name('users.edit');
    Route::post('/admin/users/{id}/update', [LoginController::class, 'updateUser'])->name('users.update');

    // ❌ Delete User
    Route::get('/admin/users/{id}/delete', [LoginController::class, 'confirmDeleteUser'])->name('users.confirmDelete');
    Route::delete('/admin/users/{id}', [LoginController::class, 'deleteUser'])->name('users.destroy');

    // 📁 Uploaded Files
    Route::get('/admin/uploads', [LoginController::class, 'uploadedFiles'])->name('admin.upload.list');
    Route::get('/admin/uploads/new', [LoginController::class, 'showUploadForm'])->name('file.upload.form');
    Route::post('/admin/uploads', [LoginController::class, 'handleUpload'])->name('file.upload.store');

    // ✅ GET view for AJAX multiple upload
    Route::get('/admin/uploads/ajax', [LoginController::class, 'showAjaxUploadForm'])->name('file.upload.ajax.form');

    // ✅ POST for handling AJAX uploads
    Route::post('/admin/uploads/multiple', [LoginController::class, 'handleMultipleUpload'])->name('file.upload.multiple');

    // 🔍 View & Delete
    Route::get('/admin/uploads/view/{id}', [LoginController::class, 'viewFile'])->name('admin.upload.view');
    Route::delete('/admin/uploads/delete/{id}', [LoginController::class, 'deleteUploadedFile'])->name('admin.upload.delete');

    // ⚙️ Placeholder
    Route::get('/admin/user-management', function () {
        return view('admin.user-management');
    })->name('admin.user.management');

    // 👤 User Dashboard
    Route::get('/user/dashboard', [LoginController::class, 'userDashboard'])->name('user.dashboard');

    // ✅ ✅ ✅ MISSING ROUTE FIX (added here)
    Route::get('/upload/files', [LoginController::class, 'uploadedFiles'])->name('upload.files');
});
