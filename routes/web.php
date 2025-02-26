<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


use App\Http\Controllers\AuthController;

// Show phone form
Route::get('/otp-request', [AuthController::class, 'showPhoneForm'])->name('otp.request.form');

// Handle phone submission to request OTP
Route::post('/otp-request', [AuthController::class, 'requestOtp'])->name('otp.request');

// Show OTP form
Route::get('/otp-verify', [AuthController::class, 'showOtpForm'])->name('otp.verify.form');

// Verify the OTP
Route::post('/otp-verify', [AuthController::class, 'verifyOtp'])->name('otp.verify');

use App\Http\Controllers\CouponController;

// Show / assign coupon after OTP is verified
Route::get('/coupon', action: [CouponController::class, 'showCoupon'])->name('coupon.show');

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;

// Admin Login
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login');

// Admin Logout
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Protected by 'admin' middleware
Route::middleware(['admin'])->group(function() {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
         ->name('admin.dashboard');

    Route::get('/admin/export-coupons', [AdminController::class, 'exportCoupons'])
         ->name('admin.export.coupons');

    Route::post('/admin/import-coupons', [AdminController::class, 'importCoupons'])
         ->name('admin.import.coupons');

    // (Optional) additional routes for expanded analytics or listing used coupons
    Route::get('/admin/successful-coupons', [AdminController::class, 'successfulCoupons'])
         ->name('admin.successful.coupons');

    Route::get('/admin/stats', [AdminController::class, 'stats'])
         ->name('admin.stats');
});

