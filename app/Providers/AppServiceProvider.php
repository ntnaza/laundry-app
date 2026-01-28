<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
    // Paksa semua aset pakai HTTPS kalau lagi di Production atau Ngrok
    if($this->app->environment('production') || config('app.url') !== 'http://localhost') {
        URL::forceScheme('https');
    }
}
    
}
