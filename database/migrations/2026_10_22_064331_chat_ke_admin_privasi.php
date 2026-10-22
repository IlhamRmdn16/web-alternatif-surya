<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $page = DB::table('pages')->where('slug', 'kebijakan-privasi')->first();
        if (! $page) {
            return; // belum dibuat; akan dibuat otomatis dari isi bawaan yang sudah diperbarui
        }

        $c = str_replace(
            [
                'unit yang Anda minati, serta sales counter yang Anda pilih. Untuk simulasi',
                'serta sales counter atau call center yang Anda pilih, sebelum diarahkan ke WhatsApp.',
            ],
            [
                'serta unit yang Anda minati. Untuk simulasi',
                'sebelum Anda diarahkan ke WhatsApp admin kami (atau call center).',
            ],
            $page->content
        );

        if ($c !== $page->content) {
            DB::table('pages')->where('id', $page->id)->update(['content' => $c, 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        // Perubahan isi halaman tidak dikembalikan.
    }
};

