<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Domain berganti dari dealermotorhondagarut.id menjadi hondagarut.id: perbarui teks yang sudah tersimpan.
        $targets = [
            'settings' => ['value'],
            'pages'    => ['title', 'meta_title', 'meta_description', 'content'],
            'faqs'     => ['question', 'answer'],
            'posts'    => ['title', 'excerpt', 'content'],
            'promos'   => ['title', 'description'],
        ];

        foreach ($targets as $table => $columns) {
            if (! Schema::hasTable($table)) continue;
            foreach ($columns as $col) {
                if (! Schema::hasColumn($table, $col)) continue;
                foreach (DB::table($table)->where($col, 'like', '%dealermotorhondagarut.id%')->get() as $row) {
                    DB::table($table)->where('id', $row->id)->update([
                        $col => str_ireplace(['DealerMotorHondaGarut.id'], ['HondaGarut.id'], (string) $row->$col),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        // Perubahan teks tidak dikembalikan.
    }
};
