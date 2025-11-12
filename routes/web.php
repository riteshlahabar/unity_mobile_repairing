<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\JobSheetController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ReportsController;

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
     // PDF Download
    Route::get('/{id}/download-pdf', [JobSheetController::class, 'downloadPDF'])->name('downloadPDF');
    // Status update routes
    Route::post('/{id}/mark-complete', [JobSheetController::class, 'markComplete'])->name('markComplete');
    Route::post('/{id}/generate-otp', [JobSheetController::class, 'generateDeliveryOTP'])->name('generateOTP');
    Route::post('/{id}/verify-otp', [JobSheetController::class, 'verifyOTPAndDeliver'])->name('verifyOTP');
    Route::post('/{id}/mark-delivered', [JobSheetController::class, 'markDelivered'])->name('markDelivered');    
});


// WhatsApp
Route::prefix('whatsapp')->name('whatsapp.')->group(function () {
    Route::get('/festival', [WhatsAppController::class, 'festival'])->name('festival');
    Route::get('/service', [WhatsAppController::class, 'service'])->name('service');
    Route::post('/send', [WhatsAppController::class, 'sendMessage'])->name('send');
    Route::post('/notifications/store', [WhatsAppController::class, 'storeNotification'])->name('notifications.store');
    Route::post('/notifications/{id}/update', [WhatsAppController::class, 'updateNotification'])->name('notifications.update');
    Route::post('/notifications/{id}/delete', [WhatsAppController::class, 'deleteNotification'])->name('notifications.delete');

    // Festival Messages
    Route::get('/festival', [WhatsAppController::class, 'festival'])->name('festival');
    Route::post('/festival/send', [WhatsAppController::class, 'sendFestivalMessages'])->name('festival.send');
    Route::get('/festival/customers', [WhatsAppController::class, 'getFestivalCustomers'])->name('festival.customers');
    Route::post('/festival/count-by-date', [WhatsAppController::class, 'getCustomerCountByDate'])->name('whatsapp.festival.countByDate');
    
});

Route::prefix('messages')->name('messages.')->group(function () {
    Route::post('/resend', [SendMessageController::class, 'resendMessage'])->name('resend');
});


// Reports
Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
Route::get('/reports/data', [ReportsController::class, 'data'])->name('reports.data');

Route::prefix('settings')->group(function () {    
    Route::get('/', [SettingController::class, 'index'])->name('setting.index');    
    Route::put('/', [SettingController::class, 'update'])->name('setting.update');    
    Route::post('/update-logo', [SettingController::class, 'updateLogo'])->name('setting.updateLogo');    
    Route::post('/update-profile-picture', [SettingController::class, 'updateProfilePicture'])->name('setting.updateProfilePicture');
    Route::post('/update-unity-signature', [SettingController::class, 'updateUnitySignature'])->name('setting.updateUnitySignature');

});





