<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\SroiController;
use App\Http\Controllers\EsgReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    // Register & Login Routes (Tetap Sama)
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.process');
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');

    // OTP Login/Register
    Route::get('/otp-verify', [AuthController::class, 'showOtpForm'])->name('otp.form');
    Route::post('/otp-verify', [AuthController::class, 'verifyOtp'])->name('otp.verify');
    Route::post('/otp-resend', [AuthController::class, 'resendOtp'])->name('otp.resend');

    // --- PASSWORD RESET FLOW (REVISED) ---
    // 1. Form Lupa Password (Input Email/Username)
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetOtp'])->name('password.email');

    // 2. Form Verifikasi OTP (Khusus Reset)
    Route::get('/forgot-password/otp', [AuthController::class, 'showResetOtpForm'])->name('password.otp.form');
    Route::post('/forgot-password/verify', [AuthController::class, 'verifyResetOtp'])->name('password.otp.verify');

    // NEW: Route Resend OTP Reset
    Route::post('/forgot-password/resend', [AuthController::class, 'resendResetOtp'])->name('password.otp.resend');

    // 3. Form Reset Password (Input Password Baru)
    Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('password.reset.form');
    Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('password.update');
});

// --- AUTH ROUTES ---
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');

    // Profiles
    // User Profile Routes
    Route::get('/profile', [ProfileController::class, 'editUser'])->name('user.profile');
    Route::put('/profile', [ProfileController::class, 'updateUser'])->name('user.profile.update');
    // Rute Baru: Update Password
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('user.profile.password');

    // API Internal
    Route::post('/profile/phone/otp', [ProfileController::class, 'requestPhoneChangeOtp'])->name('user.profile.phone.otp');
    Route::post('/profile/phone/verify', [ProfileController::class, 'verifyPhoneChangeOtp'])->name('user.profile.phone.verify');

    Route::get('/company-profile', [ProfileController::class, 'editCompany'])->name('user.company');
    Route::put('/company-profile', [ProfileController::class, 'updateCompany'])->name('user.company.update');

    // Dokumen Repository (User)
    Route::get('/documents', [DocumentController::class, 'index'])->name('user.documents');
    Route::post('/documents/upload', [DocumentController::class, 'upload'])->name('user.documents.upload');

    // Route Baru untuk Custom Document
    Route::post('/documents/upload-custom', [DocumentController::class, 'uploadCustom'])->name('user.documents.custom');

    // Chatbot AI
    Route::get('/chat', [ChatController::class, 'index'])->name('user.chat.index');
    Route::post('/chat', [ChatController::class, 'store'])->name('user.chat.store');
    Route::get('/chat/{id}', [ChatController::class, 'show'])->name('user.chat.show');
    Route::post('/chat/{id}/message', [ChatController::class, 'sendMessage'])->name('user.chat.send');
    Route::post('/chat/{id}/documents', [ChatController::class, 'updateDocuments'])->name('user.chat.documents');
    Route::post('/chat/{id}/clear', [ChatController::class, 'clearMessages'])->name('user.chat.clear');
    Route::delete('/chat/{id}', [ChatController::class, 'destroy'])->name('user.chat.delete');

    // SROI Calculator
    Route::get('/sroi-calculator', [SroiController::class, 'index'])->name('user.sroi.index');

    // ESG Report Generator
    Route::get('/esg-reports', [EsgReportController::class, 'index'])->name('user.esg.index');
    Route::get('/esg-reports/create', [EsgReportController::class, 'create'])->name('user.esg.create');
    Route::post('/esg-reports', [EsgReportController::class, 'store'])->name('user.esg.store');
    Route::get('/esg-reports/{id}', [EsgReportController::class, 'show'])->name('user.esg.show');
    Route::get('/esg-reports/{id}/progress', [EsgReportController::class, 'progress'])->name('user.esg.progress');
    Route::get('/esg-reports/{id}/download', [EsgReportController::class, 'download'])->name('user.esg.download');
    Route::delete('/esg-reports/{id}', [EsgReportController::class, 'destroy'])->name('user.esg.destroy');

    // GEOPORTAL ROUTES (Single Page Logic)
    Route::get('/geoportal', [\App\Http\Controllers\GeoportalController::class, 'index'])->name('geoportal.index');
    Route::post('/geoportal', [\App\Http\Controllers\GeoportalController::class, 'store'])->name('geoportal.store');
    Route::put('/geoportal/{id}', [\App\Http\Controllers\GeoportalController::class, 'update'])->name('geoportal.update');
    Route::delete('/geoportal/{id}', [\App\Http\Controllers\GeoportalController::class, 'destroy'])->name('geoportal.destroy');

    // --- HEATMAP (ISU NASIONAL) ---
    Route::get('/heatmap', [\App\Http\Controllers\HeatmapController::class, 'index'])->name('heatmap.index');
    Route::post('/heatmap/demo', [\App\Http\Controllers\HeatmapController::class, 'generateDemo'])->name('heatmap.demo');
    Route::post('/heatmap/live', [\App\Http\Controllers\HeatmapController::class, 'fetchLive'])->name('heatmap.live');
    Route::post('/heatmap/clear', [\App\Http\Controllers\HeatmapController::class, 'clearData'])->name('heatmap.clear');

    // GROUP ADMIN (Middleware cek role sudah ada di Controller/Logic Dashboard, tapi bisa dipertegas di sini)
    Route::prefix('admin')->name('admin.')->group(function () {
        // User Actions
        Route::post('/users/{id}/approve', [\App\Http\Controllers\AdminController::class, 'approveUser'])->name('users.approve');
        Route::post('/users/{id}/reject', [\App\Http\Controllers\AdminController::class, 'rejectUser'])->name('users.reject');

        // Document Actions
        Route::get('/documents/{id}/view', [\App\Http\Controllers\AdminController::class, 'viewDocument'])->name('documents.view');
        Route::post('/documents/{id}/approve', [\App\Http\Controllers\AdminController::class, 'approveDocument'])->name('documents.approve');
        Route::post('/documents/{id}/reject', [\App\Http\Controllers\AdminController::class, 'rejectDocument'])->name('documents.reject');


        // --- MANAJEMEN DOKUMEN (USER) ---
        Route::get('/manage-documents', [\App\Http\Controllers\AdminController::class, 'listUsersForDocuments'])->name('documents.users');
        Route::get('/manage-documents/{userId}', [\App\Http\Controllers\AdminController::class, 'showUserDocuments'])->name('documents.user.show');
        Route::delete('/documents/{id}/delete', [\App\Http\Controllers\AdminController::class, 'deleteDocument'])->name('documents.delete');

        // --- MASTER DATA DOKUMEN (BARU) ---
        Route::get('/master-documents', [\App\Http\Controllers\MasterDocumentController::class, 'index'])->name('master.documents.index');

        // Field Routes
        Route::post('/master-documents/field', [\App\Http\Controllers\MasterDocumentController::class, 'storeField'])->name('master.field.store');
        Route::put('/master-documents/field/{id}', [\App\Http\Controllers\MasterDocumentController::class, 'updateField'])->name('master.field.update');
        Route::delete('/master-documents/field/{id}', [\App\Http\Controllers\MasterDocumentController::class, 'destroyField'])->name('master.field.destroy');

        // Subfield Routes (Delete Subfield Custom pakai route ini juga)
        Route::post('/master-documents/subfield', [\App\Http\Controllers\MasterDocumentController::class, 'storeSubfield'])->name('master.subfield.store');
        Route::put('/master-documents/subfield/{id}', [\App\Http\Controllers\MasterDocumentController::class, 'updateSubfield'])->name('master.subfield.update');
        Route::delete('/master-documents/subfield/{id}', [\App\Http\Controllers\MasterDocumentController::class, 'destroySubfield'])->name('master.subfield.destroy');
        // --- MANAJEMEN USER (CRUD) ---
        Route::get('/users', [\App\Http\Controllers\AdminController::class, 'manageUsers'])->name('users.index');
        Route::post('/users', [\App\Http\Controllers\AdminController::class, 'storeUser'])->name('users.store');
        Route::put('/users/{id}', [\App\Http\Controllers\AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{id}', [\App\Http\Controllers\AdminController::class, 'destroyUser'])->name('users.destroy');

        // --- BROADCAST PESAN (NEW) ---
        Route::get('/broadcast', [\App\Http\Controllers\BroadcastController::class, 'index'])->name('broadcast.index');
        Route::post('/broadcast/send', [\App\Http\Controllers\BroadcastController::class, 'send'])->name('broadcast.send');

        // Template Routes
        Route::post('/broadcast/templates', [\App\Http\Controllers\BroadcastController::class, 'storeTemplate'])->name('broadcast.templates.store');
        Route::delete('/broadcast/templates/{id}', [\App\Http\Controllers\BroadcastController::class, 'deleteTemplate'])->name('broadcast.templates.delete');



    });
});
