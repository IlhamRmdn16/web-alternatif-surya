<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $page = DB::table('pages')->where('slug', 'kebijakan-privasi')->first();
        if (! $page || str_contains($page->content, 'Google Analytics')) {
            return; // belum ada, atau sudah memuat penjelasan Google Analytics
        }

        $c = $page->content;

        // 1) Tambahkan poin "Data penggunaan website" pada daftar data yang dikumpulkan
        $i = strpos($c, '<h2>Data yang kami kumpulkan</h2>');
        $j = $i === false ? false : strpos($c, '</ul>', $i);
        if ($j !== false) {
            $bullet = "<li><strong>Data penggunaan website:</strong> melalui Google Analytics, misalnya halaman yang dibuka, durasi kunjungan, jenis perangkat dan browser, serta perkiraan wilayah. Data ini bersifat statistik dan tidak mencakup nama atau nomor telepon Anda.</li>\n";
            $c = substr($c, 0, $j).$bullet.substr($c, $j);
        }

        // 2) Kalimat lama tentang analitik
        $c = str_replace(
            ' Jika di kemudian hari kami menambahkan layanan analitik, kebijakan ini akan diperbarui.',
            ' Cookie analitik dari Google Analytics dijelaskan pada bagian berikut.',
            $c
        );

        // 3) Bagian Google Analytics (sebelum "Penyimpanan dan keamanan", atau di akhir jika bagian itu sudah diubah)
        $section = <<<'HTML'
<h2>Google Analytics</h2>
<p>Website ini menggunakan Google Analytics, layanan analitik dari Google, untuk memantau performa dan penggunaan website, misalnya jumlah pengunjung, halaman yang paling sering dibuka, durasi kunjungan, jenis perangkat dan browser, serta perkiraan wilayah pengunjung. Informasi ini membantu kami memperbaiki isi dan kecepatan website.</p>
<p>Google Analytics menggunakan cookie dan teknologi serupa untuk mengumpulkan informasi tersebut secara statistik. Kami tidak mengirimkan nama, nomor telepon, atau isi formulir yang Anda isi ke Google Analytics. Data diproses oleh Google sesuai <a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer">Kebijakan Privasi Google</a>.</p>
<p>Anda dapat menonaktifkan cookie melalui pengaturan browser atau memasang <a href="https://tools.google.com/dlpage/gaoptout" target="_blank" rel="noopener noreferrer">add-on penolakan Google Analytics</a>. Perlu diketahui, menonaktifkan cookie dapat memengaruhi sebagian fungsi website.</p>

HTML;
        $marker = '<h2>Penyimpanan dan keamanan</h2>';
        $c = str_contains($c, $marker) ? str_replace($marker, $section.$marker, $c) : $c."\n".$section;

        DB::table('pages')->where('id', $page->id)->update(['content' => $c, 'updated_at' => now()]);
    }

    public function down(): void
    {
        // Perubahan isi halaman tidak dikembalikan.
    }
};
