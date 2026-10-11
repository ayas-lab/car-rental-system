<?php

use App\Http\Controllers\Agent\CheckInController;
use App\Http\Controllers\Agent\CheckOutController;
use App\Http\Controllers\Agent\ContractController;
use App\Http\Controllers\Agent\DashboardController;
use App\Http\Controllers\Agent\VehicleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'agent'])->prefix('agent')->name('agent.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Check-in (pickup)
    Route::get('/check-in', [CheckInController::class, 'index'])->name('checkin.index');
    Route::get('/check-in/{reservation}', [CheckInController::class, 'create'])->name('checkin.create');
    Route::post('/check-in/{reservation}', [CheckInController::class, 'store'])->name('checkin.store');

    // Check-out (return)
    Route::get('/check-out', [CheckOutController::class, 'index'])->name('checkout.index');
    Route::get('/check-out/{contract}', [CheckOutController::class, 'edit'])->name('checkout.edit');
    Route::put('/check-out/{contract}', [CheckOutController::class, 'update'])->name('checkout.update');

    // Contracts
    Route::get('/contracts', [ContractController::class, 'index'])->name('contracts.index');
    Route::get('/contracts/{contract}', [ContractController::class, 'show'])->name('contracts.show');

    // Vehicles (view + update status only)
    Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
    Route::patch('/vehicles/{vehicle}/status', [VehicleController::class, 'updateStatus'])->name('vehicles.status');
});