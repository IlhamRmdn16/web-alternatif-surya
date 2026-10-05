<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = [];

    /** Ubah nomor ke format 62xxxxxxxx. Mengembalikan string kosong jika nomor kosong. */
    public static function normalizeNumber(?string $n): string
    {
        $n = preg_replace('/\D/', '', (string) $n);
        if ($n === '') return '';
        if (str_starts_with($n, '0')) $n = '62'.substr($n, 1);
        return $n;
    }
}
