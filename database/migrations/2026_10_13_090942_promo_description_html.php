<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('promos')) return;

        foreach (DB::table('promos')->whereNotNull('description')->get() as $row) {
            $text = trim((string) $row->description);
            if ($text === '' || preg_match('/<\/?[a-z][^>]*>/i', $text)) continue;

            $paragraphs = array_filter(preg_split('/\R{2,}/', $text), fn ($p) => trim($p) !== '');
            $html = implode("\n", array_map(fn ($p) => '<p>'.nl2br(e(trim($p))).'</p>', $paragraphs));

            DB::table('promos')->where('id', $row->id)->update(['description' => $html]);
        }
    }

    public function down(): void
    {
        // Perubahan format tidak dikembalikan.
    }
};
