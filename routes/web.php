<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;


use Illuminate\Support\Facades\Auth;
use App\Models\User;

// Public pages (Visitor can access without login)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');

// Auth routes (login/register) come from Breeze
require __DIR__.'/auth.php';

// Authenticated dashboard redirect
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        /** @var User $user */
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('agent')) {
            return redirect()->route('agent.dashboard');
        }
        return redirect()->route('client.dashboard');
    })->name('dashboard');

    Route::post('/notifications/{id}/read', function ($id) {
        /** @var User $user */ 
        Auth::user()->notifications()->findOrFail($id)->markAsRead();
        return back();
    })->name('notifications.read');

    Route::post('/notifications/read-all', function () {
        /** @var User $user */ 
        Auth::user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.read-all');
});