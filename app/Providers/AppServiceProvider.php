<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // ========================================
        // REPOSITORY BINDINGS
        // ========================================
        
        // JobSheet Repository
        $this->app->bind(
            \App\Repositories\Contracts\JobSheetRepositoryInterface::class,
            \App\Repositories\Eloquent\JobSheetRepository::class
        );

        // Customer Repository
        $this->app->bind(
            \App\Repositories\Contracts\CustomerRepositoryInterface::class,
            \App\Repositories\Eloquent\CustomerRepository::class
        );
        
        // Dashboard Repository 
        $this->app->bind(
            \App\Repositories\Contracts\DashboardRepositoryInterface::class,
            \App\Repositories\Eloquent\DashboardRepository::class
        );
        
        // FestivalMessage Repository 
        $this->app->bind(
            \App\Repositories\Contracts\FestivalMessageRepositoryInterface::class,
            \App\Repositories\Eloquent\FestivalMessageRepository::class
        );
        
        // MobileDetail Repository 
        $this->app->bind(
            \App\Repositories\Contracts\MobileDetailRepositoryInterface::class,
            \App\Repositories\Eloquent\MobileDetailRepository::class
        );
        
        // Reports Repository
        $this->app->bind(
            \App\Repositories\Contracts\ReportsRepositoryInterface::class,
            \App\Repositories\Eloquent\ReportsRepository::class
        );
        
        // Settings Repository
        $this->app->bind(
            \App\Repositories\Contracts\SettingsRepositoryInterface::class,
            \App\Repositories\Eloquent\SettingsRepository::class
        );
        
        // WhatsAppNotification Repository
        $this->app->bind(
            \App\Repositories\Contracts\WhatsAppNotificationRepositoryInterface::class,
            \App\Repositories\Eloquent\WhatsAppNotificationRepository::class
        );

        // Profit Repository
        $this->app->bind(
            \App\Repositories\Contracts\ProfitRepositoryInterface::class,
            \App\Repositories\Eloquent\ProfitRepository::class
        );

        // JobSheet Repository Interface (again for clarity)
        $this->app->bind(
            \App\Repositories\Contracts\JobSheetRepositoryInterface::class,
            \App\Repositories\Eloquent\JobSheetRepository::class
        );

        // ========================================
        // SERVICE BINDINGS
        // ========================================
        
        // File Storage Service
        $this->app->bind(
            \App\Services\Contracts\FileStorageServiceInterface::class,
            \App\Services\FileStorageService::class
        );

        // Notification Service (with PDF dependency)
        $this->app->bind(
            \App\Services\Contracts\NotificationServiceInterface::class,
            function ($app) {
                return new \App\Services\JobSheetNotificationService(
                    $app->make(\App\Services\WhatsAppService::class),
                    $app->make(\App\Services\Contracts\PdfServiceInterface::class)
                );
            }
        );

        // PDF Service
        $this->app->bind(
            \App\Services\Contracts\PdfServiceInterface::class,
            \App\Services\JobSheetPdfService::class
        );
        
        // Revenue Service
        $this->app->bind(
            \App\Services\Contracts\RevenueServiceInterface::class,
            \App\Services\RevenueService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
