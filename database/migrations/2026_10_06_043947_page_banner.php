<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration {
    public function up(): void
    {
        // Banner (1 gambar) untuk halaman statis: judul halaman tampil di depan banner.
        if (! Schema::hasColumn('pages', 'banner')) {
            Schema::table('pages', function (Blueprint $t) {
                $t->string('banner')->nullable()->after('content');
            });
        }

        // Fitur "foto di sebelah kanan tulisan" dihapus: hapus file fotonya lalu kolomnya.
        if (Schema::hasColumn('pages', 'images')) {
            foreach (DB::table('pages')->whereNotNull('images')->get() as $row) {
                foreach ((array) json_decode((string) $row->images, true) as $img) {
                    if ($img) Storage::disk('public')->delete($img);
                }
            }
            Schema::table('pages', function (Blueprint $t) {
                $t->dropColumn('images');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('pages', 'images')) {
            Schema::table('pages', function (Blueprint $t) {
                $t->text('images')->nullable()->after('content');
            });
        }
        if (Schema::hasColumn('pages', 'banner')) {
            Schema::table('pages', function (Blueprint $t) {
                $t->dropColumn('banner');
            });
        }
    }
};
