<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\JobSheetController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\MobileDetailController;
use App\Http\Controllers\Auth\LoginController;

// Redirect the root URL '/' to the login page
Route::get('/', function () {
    return redirect()->route('admin.login');
})->name('home');

// Login routes
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [LoginController::class, 'login']);
});

// Protected routes example
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Logout route
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

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

//settings
Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
Route::put('/settings/business-info', [SettingController::class, 'updateBusinessInfo'])->name('setting.updateBusinessInfo');
Route::put('/settings/terms-conditions', [SettingController::class, 'updateTermsConditions'])->name('setting.updateTermsConditions');
Route::put('/settings/remarks', [SettingController::class, 'updateRemarks'])->name('setting.updateRemarks');
Route::put('/settings/logo', [SettingController::class, 'updateLogo'])->name('setting.updateLogo');
Route::put('/settings/profile-picture', [SettingController::class, 'updateProfilePicture'])->name('setting.updateProfilePicture');
Route::put('/settings/unity-signature', [SettingController::class, 'updateUnitySignature'])->name('setting.updateUnitySignature');
Route::put('/settings/security', [SettingController::class, 'updateSecurity'])->name('setting.updateSecurity');

// Mobile details routes
Route::post('/mobile-details/store-company', [MobileDetailController::class, 'storeCompany'])->name('mobile.storeCompany');
Route::post('/mobile-details/store-model', [MobileDetailController::class, 'storeModel'])->name('mobile.storeModel');
Route::post('/mobile-details/store-color', [MobileDetailController::class, 'storeColor'])->name('mobile.storeColor');
Route::post('/mobile-details/store-series', [MobileDetailController::class, 'storeSeries'])->name('mobile.storeSeries');
Route::post('/mobile-details/store-technician', [MobileDetailController::class, 'storeTechnician'])->name('mobile.storeTechnician');

// Fetch dropdown data
Route::get('/mobile-details/fetch-companies', [MobileDetailController::class, 'fetchCompanies'])->name('mobile.fetchCompanies');
Route::get('/mobile-details/fetch-models', [MobileDetailController::class, 'fetchModels'])->name('mobile.fetchModels');
Route::get('/mobile-details/fetch-colors', [MobileDetailController::class, 'fetchColors'])->name('mobile.fetchColors');
Route::get('/mobile-details/fetch-series', [MobileDetailController::class, 'fetchSeries'])->name('mobile.fetchSeries');
Route::get('/mobile-details/fetch-technicians', [MobileDetailController::class, 'fetchTechnicians'])->name('mobile.fetchTechnicians');




