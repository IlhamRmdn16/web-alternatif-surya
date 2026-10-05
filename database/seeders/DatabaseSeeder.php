<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Faq;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@dealermotorhondagarut.id'],
            ['name' => 'Admin', 'password' => Hash::make('ganti-password-ini')]
        );

        foreach (['Matic', 'Sport', 'EV', 'Cub'] as $i => $name) {
            Category::updateOrCreate(['slug' => strtolower($name)], ['name' => $name, 'sort' => $i + 1]);
        }

        $defaults = [
            'site_name' => 'Dealer Motor Honda Garut',
            'seo_home_title' => 'Dealer Motor Honda Garut Resmi | Harga, Promo & Kredit Motor Honda',
            'seo_home_description' => 'DealerMotorHondaGarut.id - Dealer motor Honda Garut resmi CV Surya Wijaya Sejahtera. Cek daftar harga, promo terbaru, simulasi kredit, dan konsultasi pembelian motor Honda.',
            'home_h1' => 'Dealer Motor Honda Garut Resmi',
            'home_intro' => 'DealerMotorHondaGarut.id adalah website resmi CV. Surya Wijaya Sejahtera, dealer motor Honda di Garut sejak 1991. Lihat harga setiap tipe, pilihan warna, promo terbaru, dan konsultasikan pembelian atau simulasi kredit langsung dengan tim kami.',
            'hours' => "Senin - Jumat: 08:00 - 17:00\nSabtu: 08:00 - 16:00\nMinggu: 09:00 - 14:00",
        ];
        foreach ($defaults as $k => $v) {
            Setting::firstOrCreate(['key' => $k], ['value' => $v]);
        }

        $faqs = [
            ['Bagaimana cara membeli motor Honda di sini?', 'Pilih motor di halaman Daftar Harga, klik tombol Konsultasi Pembelian, isi data Anda, dan tim sales kami akan menghubungi untuk proses selanjutnya.'],
            ['Apakah bisa membeli secara kredit?', 'Bisa. Gunakan menu Konsultasi Pembelian lalu pilih "Minta hitungan kredit (simulasi)", masukkan DP dan tenor yang diinginkan.'],
            ['Apakah harga di website sudah pasti?', 'Harga dapat berubah sewaktu-waktu mengikuti kebijakan dealer. Hubungi kami via WhatsApp untuk konfirmasi harga dan stok terbaru.'],
            ['Apakah tersedia layanan servis dan suku cadang asli?', 'Silakan hubungi kami via WhatsApp untuk informasi layanan servis (AHASS) dan suku cadang asli Honda.'],
        ];
        foreach ($faqs as $i => [$q, $a]) {
            Faq::firstOrCreate(['question' => $q], ['answer' => $a, 'sort' => $i + 1]);
        }
    }
}
