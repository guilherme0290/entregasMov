<?php

use App\Http\Controllers\Api\V1\Admin\CourierController as AdminCourierController;
use App\Http\Controllers\Api\V1\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Api\V1\Admin\DeliveryController as AdminDeliveryController;
use App\Http\Controllers\Api\V1\Admin\PartnerController as AdminPartnerController;
use App\Http\Controllers\Api\V1\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\Courier\DashboardController as CourierDashboardController;
use App\Http\Controllers\Api\V1\Courier\DeliveryController as CourierDeliveryController;
use App\Http\Controllers\Api\V1\Courier\NotificationController as CourierNotificationController;
use App\Http\Controllers\Api\V1\Courier\StatusController as CourierStatusController;
use App\Http\Controllers\Api\V1\Partner\DashboardController as PartnerDashboardController;
use App\Http\Controllers\Api\V1\Partner\DeliveryController as PartnerDeliveryController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/me', [AuthController::class, 'me']);
        });
    });

    Route::prefix('admin')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::get('/dashboard', AdminDashboardController::class);
        Route::get('/partners', [AdminPartnerController::class, 'index']);
        Route::get('/partners/{partner}', [AdminPartnerController::class, 'show']);
        Route::get('/couriers', [AdminCourierController::class, 'index']);
        Route::get('/couriers/{courier}', [AdminCourierController::class, 'show']);
        Route::get('/deliveries', [AdminDeliveryController::class, 'index']);
        Route::get('/deliveries/{delivery}', [AdminDeliveryController::class, 'show']);
        Route::post('/deliveries/{delivery}/assign-courier', [AdminDeliveryController::class, 'assignCourier']);
        Route::get('/reports/deliveries', [AdminReportController::class, 'deliveries']);
        Route::get('/reports/partners', [AdminReportController::class, 'partners']);
        Route::get('/reports/couriers', [AdminReportController::class, 'couriers']);
    });

    Route::prefix('partner')->middleware(['auth:sanctum', 'role:partner'])->group(function () {
        Route::get('/dashboard', PartnerDashboardController::class);
        Route::post('/deliveries', [PartnerDeliveryController::class, 'store']);
        Route::get('/deliveries', [PartnerDeliveryController::class, 'index']);
        Route::get('/deliveries/{delivery}', [PartnerDeliveryController::class, 'show']);
        Route::post('/deliveries/{delivery}/cancel', [PartnerDeliveryController::class, 'cancel']);
    });

    Route::prefix('courier')->middleware(['auth:sanctum', 'role:courier'])->group(function () {
        Route::get('/dashboard', CourierDashboardController::class);
        Route::get('/notifications', [CourierNotificationController::class, 'index']);
        Route::post('/notifications/{notification}/read', [CourierNotificationController::class, 'markAsRead']);
        Route::patch('/status', [CourierStatusController::class, 'update']);
        Route::get('/deliveries/available', [CourierDeliveryController::class, 'available']);
        Route::get('/deliveries/mine', [CourierDeliveryController::class, 'mine']);
        Route::get('/deliveries/{delivery}', [CourierDeliveryController::class, 'show']);
        Route::post('/deliveries/{delivery}/accept', [CourierDeliveryController::class, 'accept']);
        Route::post('/deliveries/{delivery}/reject', [CourierDeliveryController::class, 'reject']);
        Route::post('/deliveries/{delivery}/start-pickup', [CourierDeliveryController::class, 'startPickup']);
        Route::post('/deliveries/{delivery}/start-transit', [CourierDeliveryController::class, 'startTransit']);
        Route::post('/deliveries/{delivery}/complete', [CourierDeliveryController::class, 'complete']);
        Route::get('/earnings', [CourierDeliveryController::class, 'earnings']);
    });
});
