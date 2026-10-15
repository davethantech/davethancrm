<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
Route::get('/', fn () => redirect()->route('login'));
Route::get('/login', [AuthController::class, 'show'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::resource('leads', LeadController::class)->except(['show']);
});
Route::get('/setup', [AuthController::class, 'setupForm'])->name('setup');
Route::post('/setup', [AuthController::class, 'setup'])->middleware('throttle:3,60');