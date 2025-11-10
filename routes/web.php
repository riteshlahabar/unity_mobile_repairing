<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\JobSheetController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;

// Home
Route::get('/', function () {
    return view('home');
})->name('home');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Customers
Route::prefix('customers')->name('customers.')->group(function () {
    Route::get('/search', [CustomerController::class, 'search'])->name('search'); // ✅ FIRST
    Route::get('/', [CustomerController::class, 'index'])->name('index');
    Route::get('/create', [CustomerController::class, 'create'])->name('create');
    Route::post('/store', [CustomerController::class, 'store'])->name('store');
    Route::post('/check-contact', [CustomerController::class, 'checkContact'])->name('checkContact');
    Route::get('/{id}/edit', [CustomerController::class, 'edit'])->name('edit');
    Route::put('/{id}', [CustomerController::class, 'update'])->name('update');
    Route::delete('/{id}', [CustomerController::class, 'destroy'])->name('destroy');
    Route::get('/{id}', [CustomerController::class, 'show'])->name('show'); // ✅ LAST
});

// JobSheets
Route::prefix('jobsheets')->name('jobsheets.')->group(function () {
    Route::get('/', [JobSheetController::class, 'index'])->name('index');
    Route::get('/create', [JobSheetController::class, 'create'])->name('create');
    Route::post('/store', [JobSheetController::class, 'store'])->name('store');
    Route::get('/{id}', [JobSheetController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [JobSheetController::class, 'edit'])->name('edit');
    Route::put('/{id}', [JobSheetController::class, 'update'])->name('update');
    Route::delete('/{id}', [JobSheetController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/mark-complete', [JobSheetController::class, 'markComplete'])->name('markComplete');
    Route::post('/{id}/mark-delivered', [JobSheetController::class, 'markDelivered'])->name('markDelivered');
});

// WhatsApp
Route::prefix('whatsapp')->name('whatsapp.')->group(function () {
    Route::get('/festival', [WhatsAppController::class, 'festival'])->name('festival');
    Route::get('/service', [WhatsAppController::class, 'service'])->name('service');
    Route::post('/send', [WhatsAppController::class, 'sendMessage'])->name('send');
});

// Reports
Route::get('/reports', [ReportController::class, 'index'])->name('reports');

// Settings
Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [SettingController::class, 'index'])->name('index');
    Route::put('/update', [SettingController::class, 'update'])->name('update');
});