<?php

use App\Http\Controllers\Web\Admin\CourierController as AdminCourierController;
use App\Http\Controllers\Web\Admin\CourierEarningController as AdminCourierEarningController;
use App\Http\Controllers\Web\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Web\Admin\DeliveryController as AdminDeliveryController;
use App\Http\Controllers\Web\Admin\PartnerController as AdminPartnerController;
use App\Http\Controllers\Web\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\Web\Partner\DeliveryController as PartnerDeliveryController;
use App\Http\Controllers\Web\Partner\PortalController as PartnerPortalController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});

Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');
    Route::get('reports', AdminReportController::class)->name('reports.index');
    Route::resource('deliveries', AdminDeliveryController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update']);
    Route::post('deliveries/{delivery}/assign-courier', [AdminDeliveryController::class, 'assignCourier'])->name('deliveries.assign-courier');
    Route::post('deliveries/{delivery}/cancel', [AdminDeliveryController::class, 'cancel'])->name('deliveries.cancel');
    Route::get('earnings', [AdminCourierEarningController::class, 'index'])->name('earnings.index');
    Route::put('earnings/{earning}', [AdminCourierEarningController::class, 'update'])->name('earnings.update');
    Route::resource('partners', AdminPartnerController::class)->except(['show', 'destroy']);
    Route::resource('couriers', AdminCourierController::class)->except(['show', 'destroy']);
});

Route::prefix('partner')->middleware(['auth', 'role:partner'])->name('partner.')->group(function () {
    Route::get('/', PartnerPortalController::class)->name('portal');
    Route::post('/deliveries', [PartnerDeliveryController::class, 'store'])->name('deliveries.store');
    Route::post('/deliveries/{delivery}/cancel', [PartnerDeliveryController::class, 'cancel'])->name('deliveries.cancel');
});
