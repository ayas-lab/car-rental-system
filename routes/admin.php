<?php

use App\Http\Controllers\Admin\ContractController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VehicleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Vehicles CRUD
    Route::resource('vehicles', VehicleController::class);

    // Users CRUD
    Route::resource('users', UserController::class);
    Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.role');

    // Reservations
    Route::resource('reservations', ReservationController::class);
    Route::patch('/reservations/{reservation}/status', [ReservationController::class, 'updateStatus'])->name('reservations.status');

    // Contracts
    Route::resource('contracts', ContractController::class)->only(['index', 'show']);

    // Invoices
    Route::resource('invoices', InvoiceController::class);

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
});