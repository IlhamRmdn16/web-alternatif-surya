<?php

namespace App\Support;

use Illuminate\Support\Str;

class Helpers
{
    public static function uniqueSlug(string $model, string $text, ?int $ignoreId = null): string
    {
        $base = Str::slug($text) ?: Str::lower(Str::random(6));
        $slug = $base;
        $i = 2;
        while ($model::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }
        return $slug;
    }

    public static function rp($n): string
    {
        return 'Rp '.number_format((int) $n, 0, ',', '.');
    }
}
