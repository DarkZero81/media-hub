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
use Illuminate\Support\Facades\URL;

public function boot(): void
{
    // فحص: إذا كان الموقع يعمل على سيرفر خارجي (وليس جهازك المحلي)، أجبره على الـ HTTPS الآمن
    if (config('app.env') !== 'local') {
        URL::forceScheme('https');
    }
}

}
