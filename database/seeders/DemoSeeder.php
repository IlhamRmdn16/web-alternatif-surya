<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Motor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * DATA CONTOH untuk uji tampilan di localhost. Harga di sini FIKTIF.
 * Jalankan: php artisan db:seed --class=DemoSeeder
 * Hapus semua data contoh lewat admin sebelum website dipublikasikan.
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            // jenis, nama seri, tipe, OTR, diskon cash
            ['matic', 'Beat', 'CBS', 20000000, 300000],
            ['matic', 'Beat', 'CBS ISS', 21000000, 0],
            ['matic', 'Beat', 'Deluxe', 22000000, 700000],
            ['matic', 'Vario 125', 'CBS', 25000000, 600000],
            ['matic', 'Vario 125', 'CBS ISS', 26000000, 0],
            ['sport', 'CB150R', 'Standard', 40000000, 0],
            ['cub', 'Supra GTR', 'Standard', 28000000, 400000],
            ['ev', 'EM1 e:', 'Standard', 30000000, 0],
        ];

        foreach ($rows as $i => [$cat, $name, $variant, $price, $disc]) {
            $category = Category::where('slug', $cat)->first();
            if (! $category) continue;
            $motor = Motor::firstOrCreate(
                ['slug' => Str::slug("$name $variant")],
                [
                    'category_id' => $category->id, 'name' => $name, 'series_slug' => Str::slug($name),
                    'variant' => $variant, 'price' => $price, 'cash_discount' => $disc,
                    'description' => "DATA CONTOH untuk uji tampilan. Ganti dengan deskripsi asli $name $variant.",
                    'show_on_home' => true, 'home_sort' => $i, 'is_active' => true,
                ]
            );

            // Contoh warna (tanpa foto). Beat CBS: warna Merah punya harga khusus (+500 ribu).
            if ($motor->wasRecentlyCreated) {
                $motor->colors()->create(['name' => 'Hitam', 'hex' => '#111111', 'sort' => 0]);
                $motor->colors()->create(['name' => 'Putih', 'hex' => '#f5f5f5', 'sort' => 1]);
                if ($motor->slug === 'beat-cbs') {
                    $motor->colors()->create(['name' => 'Merah Spesial', 'hex' => '#c8102e', 'price' => $price + 500000, 'cash_discount' => $disc, 'sort' => 2]);
                }
            }
        }
    }
}
