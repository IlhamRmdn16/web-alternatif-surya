<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = [];

    public static function normalizeNumber(?string $n): string
    {
        $n = preg_replace('/\D/', '', (string) $n);
        if (str_starts_with($n, '0')) $n = '62'.substr($n, 1);
        return $n ?: '6285199359033';
    }
}
