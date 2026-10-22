<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $settings = [];

        try {
            if (Schema::hasTable('settings')) {
                $settings = Setting::pluck('value', 'key')->toArray();
            }
        } catch (\Throwable $e) {
            // database belum siap (mis. saat migrasi pertama)
        }

        View::share('settings', $settings);
    }
}
