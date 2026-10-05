<?php

namespace App\Providers;

use App\Models\SalesContact;
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
        $salesList = [];

        try {
            if (Schema::hasTable('settings')) {
                $settings = Setting::pluck('value', 'key')->toArray();
            }
            // Daftar sales counter aktif untuk pilihan di semua tombol/formulir WhatsApp (nomor tidak dikirim ke browser).
            if (Schema::hasTable('sales_contacts')) {
                $salesList = SalesContact::where('is_active', true)->orderBy('sort')->orderBy('id')->get()
                    ->map(fn ($s) => [
                        'id' => $s->id, 'name' => $s->name, 'position' => $s->position,
                        'photo' => $s->photo ? asset('storage/'.$s->photo) : null,
                    ])->values()->all();
            }
        } catch (\Throwable $e) {
            // database belum siap (mis. saat migrasi pertama)
        }

        View::share('settings', $settings);
        View::share('salesList', $salesList);
    }
}
