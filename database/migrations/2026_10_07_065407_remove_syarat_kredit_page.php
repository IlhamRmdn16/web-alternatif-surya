<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration {
    public function up(): void
    {
        foreach (DB::table('pages')->whereIn('slug', ['syarat-kredit', 'cara-beli-dan-syarat-kredit'])->get() as $row) {
            if (! empty($row->banner)) Storage::disk('public')->delete($row->banner);
            DB::table('pages')->where('id', $row->id)->delete();
        }

        $privacy = DB::table('pages')->where('slug', 'kebijakan-privasi')->first();
        if ($privacy) {
            $clean = str_replace(' Browser Anda juga menyimpan penanda kecil agar petunjuk penggunaan halaman motor tidak ditampilkan berulang.', '', $privacy->content);
            if ($clean !== $privacy->content) {
                DB::table('pages')->where('id', $privacy->id)->update(['content' => $clean]);
            }
        }
    }

    public function down(): void
    {
        // Data yang dihapus tidak dikembalikan.
    }
};
