<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CouponController;

// ===================================
// Public Routes (User OTP Flow)
// ===================================
Route::redirect('/', '/otp-request');


// User OTP
Route::get('/otp-request', [AuthController::class, 'showPhoneForm'])->name('otp.request.form');
Route::post('/otp-request', [AuthController::class, 'requestOtp'])->name('otp.request');
Route::get('/otp-verify', [AuthController::class, 'showOtpForm'])->name('otp.verify.form');
Route::post('/otp-verify', [AuthController::class, 'verifyOtp'])->name('otp.verify');

// Show coupon after OTP
Route::get('/coupon', [CouponController::class, 'showCoupon'])->name('coupon.show');

// ===================================
// Admin Auth
// ===================================

Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
})->middleware('admin');


Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// ===================================
// Admin Routes (Protected by 'admin' middleware)
// ===================================
Route::middleware(['admin'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');

    Route::get('/admin/export-coupons', [AdminController::class, 'exportCoupons'])
        ->name('admin.export.coupons');

    Route::post('/admin/import-coupons', [AdminController::class, 'importCoupons'])
        ->name('admin.import.coupons');

    // Show used coupons in a table
    Route::get('/admin/successful-coupons', [AdminController::class, 'successfulCoupons'])
        ->name('admin.successful.coupons');

    // Chart-based stats
    Route::get('/admin/stats', [AdminController::class, 'stats'])
        ->name('admin.stats');

    // Inside your admin middleware group:
    Route::get('/admin/demo-csv', [AdminController::class, 'downloadDemoCsv'])
        ->name('admin.demo.csv');

    // View all subscribers (with pagination)
    Route::get('/admin/subscribers', [AdminController::class, 'subscribersList'])
        ->name('admin.subscribers.list');

    // Download subscribers list as CSV
    Route::get('/admin/export-subscribers', [AdminController::class, 'exportSubscribers'])
        ->name('admin.export.subscribers');
});

// ===================================
// Fallback Route
// ===================================
Route::fallback(function () {
    // Show a 404 page or redirect to a default route
    return redirect()->route('/admin/login');
});
