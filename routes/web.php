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
use App\Http\Controllers\RevenueController;

// Redirect the root URL '/' to the login page
Route::get('/', function () {
    return redirect()->route('admin.login');
})->name('home');

// Login routes
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [LoginController::class, 'login']);
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportsController::class, 'index'])->name('index');
        Route::get('/data', [ReportsController::class, 'data'])->name('data');
        
        // Revenue Report (PIN Protected)
        Route::get('/revenue', [RevenueController::class, 'index'])->name('revenue');
        Route::post('/revenue/verify-pin', [RevenueController::class, 'verifyPin'])->name('verifyPin');
        Route::get('/revenue/data', [RevenueController::class, 'getData'])->name('getData');
        Route::post('/revenue/update-profit', [RevenueController::class, 'updateProfit'])->name('updateProfit');
    });

    // Customers
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/search', [CustomerController::class, 'search'])->name('search');
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/store', [CustomerController::class, 'store'])->name('store');
        Route::post('/check-contact', [CustomerController::class, 'checkContact'])->name('checkContact');
        Route::get('/{id}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CustomerController::class, 'update'])->name('update');
        Route::delete('/{id}', [CustomerController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [CustomerController::class, 'show'])->name('show');
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
        Route::get('/{id}/print', [JobSheetController::class, 'printPDF'])->name('printPDF');
        Route::get('/{id}/print-label', [JobSheetController::class, 'printLabel'])->name('printLabel');

        // Status update routes
        Route::post('/{id}/mark-complete', [JobSheetController::class, 'markComplete'])->name('markComplete');
        Route::post('/{id}/save-warranty', [JobSheetController::class, 'saveWarranty'])->name('saveWarranty');
        Route::post('/{id}/generate-otp', [JobSheetController::class, 'generateDeliveryOTP'])->name('generateOTP');
        Route::post('/{id}/verify-otp', [JobSheetController::class, 'verifyOTPAndDeliver'])->name('verifyOTP');
        Route::post('/{id}/mark-delivered', [JobSheetController::class, 'markDelivered'])->name('markDelivered');  
        Route::post('/{id}/update-status', [JobSheetController::class, 'updateStatus'])->name('updateStatus');
        Route::get('/{id}/previous-warranties', [JobSheetController::class, 'getPreviousWarranties'])->name('previousWarranties');
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
        Route::post('/festival/send', [WhatsAppController::class, 'sendFestivalMessages'])->name('festival.send');
        Route::get('/festival/customers', [WhatsAppController::class, 'getFestivalCustomers'])->name('festival.customers');
        Route::post('/festival/count-by-date', [WhatsAppController::class, 'getCustomerCountByDate'])->name('whatsapp.festival.countByDate');
       
    });

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings/business-info', [SettingController::class, 'updateBusinessInfo'])->name('setting.updateBusinessInfo');
    Route::put('/settings/terms-conditions', [SettingController::class, 'updateTermsConditions'])->name('setting.updateTermsConditions');
    Route::put('/settings/remarks', [SettingController::class, 'updateRemarks'])->name('setting.updateRemarks');
    Route::put('/settings/security', [SettingController::class, 'updateSecurity'])->name('setting.updateSecurity');
    Route::post('/settings/change-pin', [SettingController::class, 'changePin'])->name('setting.changePin');

    // Mobile details
    Route::prefix('mobile-details')->name('mobile.')->group(function () {
        // Store
        Route::post('/store-company', [MobileDetailController::class, 'storeCompany'])->name('storeCompany');
        Route::post('/store-model', [MobileDetailController::class, 'storeModel'])->name('storeModel');
        Route::post('/store-color', [MobileDetailController::class, 'storeColor'])->name('storeColor');
        Route::post('/store-series', [MobileDetailController::class, 'storeSeries'])->name('storeSeries');
        Route::post('/store-technician', [MobileDetailController::class, 'storeTechnician'])->name('storeTechnician');

        // Fetch
        Route::get('/fetch-companies', [MobileDetailController::class, 'fetchCompanies'])->name('fetchCompanies');
        Route::get('/fetch-models', [MobileDetailController::class, 'fetchModels'])->name('fetchModels');
        Route::get('/fetch-colors', [MobileDetailController::class, 'fetchColors'])->name('fetchColors');
        Route::get('/fetch-series', [MobileDetailController::class, 'fetchSeries'])->name('fetchSeries');
        Route::get('/fetch-technicians', [MobileDetailController::class, 'fetchTechnicians'])->name('fetchTechnicians');

        // Update
        Route::post('/update-company', [MobileDetailController::class, 'updateCompany'])->name('updateCompany');
        Route::post('/update-model', [MobileDetailController::class, 'updateModel'])->name('updateModel');
        Route::post('/update-color', [MobileDetailController::class, 'updateColor'])->name('updateColor');
        Route::post('/update-series', [MobileDetailController::class, 'updateSeries'])->name('updateSeries');
        Route::post('/update-technician', [MobileDetailController::class, 'updateTechnician'])->name('updateTechnician');
        Route::post('/bulk-master-upload', [MobileDetailController::class, 'bulkMasterUpload'])->name('bulkMasterUpload');
    });
});

// Logout route (outside auth middleware)
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

// Messages
Route::prefix('messages')->middleware('auth')->group(function () {
    Route::post('/resend', [\App\Http\Controllers\MessageController::class, 'resend'])->name('messages.resend');
    Route::post('/custom', [\App\Http\Controllers\MessageController::class, 'sendCustom'])->name('messages.custom');
});
