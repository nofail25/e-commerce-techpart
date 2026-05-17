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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Ensure public disk URL is properly configured for techpart storage
        $this->configurePublicStorageUrl();
    }

    /**
     * Configure public storage URL for product images and other media
     */
    protected function configurePublicStorageUrl(): void
    {
        \Illuminate\Support\Facades\Storage::macro('imageUrl', function ($path) {
            if (!$path) {
                return null;
            }
            return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
        });
    }
}
