<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    // Tenant authentication routes
    Route::get('/login', [App\Http\Controllers\Tenant\AuthController::class, 'showLoginForm'])->name('tenant.login');
    Route::post('/login', [App\Http\Controllers\Tenant\AuthController::class, 'login'])->name('tenant.login.post');
    
    // Tenant protected routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/', App\Livewire\Tenant\Dashboard::class)->name('tenant.dashboard');
        Route::get('/pos', App\Livewire\Tenant\PosSystem::class)->name('tenant.pos');
        Route::get('/products', App\Livewire\Tenant\ProductManagement::class)->name('tenant.products');
        Route::get('/categories', App\Livewire\Tenant\CategoryManagement::class)->name('tenant.categories');
        Route::get('/sales-report', App\Livewire\Tenant\SalesReport::class)->name('tenant.sales');
        Route::post('/logout', [App\Http\Controllers\Tenant\AuthController::class, 'logout'])->name('tenant.logout');
    });
});
