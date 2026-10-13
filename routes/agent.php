<?php

use App\Http\Controllers\Agent\CheckInController;
use App\Http\Controllers\Agent\CheckOutController;
use App\Http\Controllers\Agent\ContractController;
use App\Http\Controllers\Agent\DashboardController;
use App\Http\Controllers\Agent\VehicleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'agent'])->prefix('agent')->name('agent.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Check-out (pickup)
    Route::get('/check-out', [CheckOutController::class, 'index'])->name('checkout.index');
    Route::get('/check-out/{reservation}', [CheckOutController::class, 'create'])->name('checkout.create');
    Route::post('/check-out/{reservation}', [CheckOutController::class, 'store'])->name('checkout.store');

    // Check-in (return)
    Route::get('/check-in', [CheckInController::class, 'index'])->name('checkin.index');
    Route::get('/check-in/{contract}', [CheckInController::class, 'edit'])->name('checkin.edit');
    Route::put('/check-in/{contract}', [CheckInController::class, 'update'])->name('checkin.update');

    // Contracts
    Route::get('/contracts', [ContractController::class, 'index'])->name('contracts.index');
    Route::get('/contracts/{contract}', [ContractController::class, 'show'])->name('contracts.show');

    // Vehicles (view + update status only)
    Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
    Route::patch('/vehicles/{vehicle}/status', [VehicleController::class, 'updateStatus'])->name('vehicles.status');
});