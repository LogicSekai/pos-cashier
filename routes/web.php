<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Superadmin\AuthController as SuperadminAuthController;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Superadmin routes
Route::prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/login', [SuperadminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [SuperadminAuthController::class, 'login'])->name('login.post');
    
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', \App\Livewire\Superadmin\Dashboard::class)->name('dashboard');
        Route::get('/tenants', \App\Livewire\Superadmin\TenantManagement::class)->name('tenants');
        Route::get('/users', \App\Livewire\Superadmin\UserManagement::class)->name('users');
        Route::post('/logout', [SuperadminAuthController::class, 'logout'])->name('logout');
    });
});
