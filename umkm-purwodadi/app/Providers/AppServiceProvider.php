<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Force HTTPS on Vercel (behind proxy)
        if (env('VERCEL') || env('APP_ENV') === 'production') {
            \URL::forceScheme('https');
        }
    }
}
