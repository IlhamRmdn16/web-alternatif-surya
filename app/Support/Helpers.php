<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Helpers
{
    public const DEALER_ADDRESS = 'Jl. Papandayan No.112, Kota Kulon, Kec. Garut Kota, Kabupaten Garut, Jawa Barat 44114';

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

    /** Rasio asli gambar di storage publik ("lebar / tinggi") untuk CSS aspect-ratio; default 4000 / 1667. */
    public static function imageRatio(?string $path, string $default = '4000 / 1667'): string
    {
        if (! $path) return $default;
        try {
            $size = @getimagesize(Storage::disk('public')->path($path));
            if ($size && $size[0] > 0 && $size[1] > 0) return $size[0].' / '.$size[1];
        } catch (\Throwable $e) {
            // abaikan, pakai default
        }
        return $default;
    }

    /** Jumlah data per halaman dari ?per_page= (hanya 10, 20, 50, 100). */
    public static function perPage(Request $r, int $default = 10): int
    {
        $n = (int) $r->query('per_page');
        return in_array($n, [10, 20, 50, 100], true) ? $n : $default;
    }

    public static function rp($n): string
    {
        return 'Rp '.number_format((int) $n, 0, ',', '.');
    }
}
