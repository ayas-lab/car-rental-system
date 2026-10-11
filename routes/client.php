<?php

use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Client\ReservationController;
use App\Http\Controllers\Client\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Reservations
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'cancel'])->name('reservations.cancel');

    // Invoices
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
});