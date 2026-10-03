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
        try {
            $settings = Schema::hasTable('settings') ? Setting::pluck('value', 'key')->toArray() : [];
        } catch (\Throwable $e) {
            $settings = [];
        }
        View::share('settings', $settings);
        View::share('waNumber', Setting::normalizeNumber($settings['wa_number'] ?? '6285199359033'));
    }
}
