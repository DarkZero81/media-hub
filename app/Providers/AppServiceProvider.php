<?php

namespace App\Providers;

// 1. الاستدعاء الصحيح لكافة المكتبات يجب أن يكون هنا خارج نطاق الكلاس بالملي
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
        // 2. فحص السيرفر وإجبار الروابط على الـ HTTPS الآمن لمنع الـ Mixed Content
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }
    }
}
