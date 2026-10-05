<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        // ---- Berita ----
        Schema::create('posts', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('excerpt', 300)->nullable();
            $t->string('cover')->nullable();
            $t->longText('content');
            $t->boolean('is_published')->default(true);
            $t->timestamp('published_at')->nullable();
            $t->timestamps();
        });

        // ---- Foto di sebelah kanan teks pada halaman statis ----
        Schema::table('pages', function (Blueprint $t) {
            $t->text('images')->nullable()->after('content');
        });

        // ---- Hapus nomor WA lama (nomor dealer) dari pengaturan. Nomor call center diisi ulang di Admin > Pengaturan. ----
        DB::table('settings')->where('key', 'wa_number')->delete();

        // ---- "Cara Beli & Syarat Kredit" menjadi "Syarat Kredit" ----
        $old = DB::table('pages')->where('slug', 'cara-beli-dan-syarat-kredit')->first();
        if ($old) {
            if ($old->created_at == $old->updated_at) {
                // belum pernah diedit: hapus, akan dibuat ulang otomatis dengan isi "Syarat Kredit" yang baru
                DB::table('pages')->where('id', $old->id)->delete();
            } else {
                DB::table('pages')->where('id', $old->id)->update([
                    'slug' => 'syarat-kredit',
                    'title' => $old->title === 'Cara Beli & Syarat Kredit Motor Honda' ? 'Syarat Kredit Motor Honda' : $old->title,
                ]);
            }
        }

        // ---- "Pricelist" menjadi "Daftar Harga" pada teks yang sudah tersimpan ----
        $fix = function (?string $text) {
            $text = str_ireplace(['(/pricelist)', 'href="/pricelist"'], ['(/daftar-harga)', 'href="/daftar-harga"'], (string) $text);
            $text = str_replace('Pricelist', 'Daftar Harga', $text);
            return str_ireplace('pricelist', 'daftar harga', $text);
        };
        foreach (['settings' => 'value', 'faqs' => 'answer', 'pages' => 'content'] as $table => $col) {
            foreach (DB::table($table)->where($col, 'like', '%ricelist%')->get() as $row) {
                DB::table($table)->where('id', $row->id)->update([$col => $fix($row->$col)]);
            }
        }

        // ---- Isi halaman lama (Markdown) diubah menjadi HTML agar bisa diedit di editor ----
        foreach (DB::table('pages')->get() as $row) {
            if (! str_contains($row->content, '<')) {
                DB::table('pages')->where('id', $row->id)->update([
                    'content' => Str::markdown($row->content, ['html_input' => 'strip', 'allow_unsafe_links' => false]),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $t) {
            $t->dropColumn('images');
        });
        Schema::dropIfExists('posts');
    }
};
