<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Pengolah gambar banner agar ringan di pengunjung.
 *
 * Setiap upload dibuat 2 file WebP (di-crop "cover" dari tengah):
 *   - desktop : 1240x520 (rasio 31:13)  -> {nama}.webp
 *   - mobile  :  750x600 (rasio 5:4)    -> {nama}-m.webp
 * Kolom database cukup menyimpan path file desktop; path mobile diturunkan.
 */
class BannerImage
{
    private const VARIANTS = [
        ''   => [1240, 520, 78],
        '-m' => [750, 600, 75],
    ];

    /** Simpan banner, kembalikan path file desktop (relatif ke disk "public"). */
    public static function store(UploadedFile $file, string $dir = 'banners'): string
    {
        $src = (function_exists('imagewebp') && function_exists('imagecreatefromstring'))
            ? @imagecreatefromstring((string) file_get_contents($file->getRealPath()))
            : false;

        // GD/WebP tidak tersedia atau gambar tidak terbaca: simpan apa adanya.
        if (! $src) {
            return $file->store($dir, 'public');
        }

        $disk = Storage::disk('public');
        $base = trim($dir, '/').'/'.Str::random(24);

        foreach (self::VARIANTS as $suffix => [$w, $h, $quality]) {
            $img = self::cover($src, $w, $h);
            ob_start();
            imagewebp($img, null, $quality);
            $disk->put($base.$suffix.'.webp', (string) ob_get_clean());
            imagedestroy($img);
        }
        imagedestroy($src);

        return $base.'.webp';
    }

    /** Hapus file desktop beserta varian mobile-nya. */
    public static function delete(?string $path): void
    {
        if (! $path) return;
        $disk = Storage::disk('public');
        $disk->delete($path);
        if ($m = self::mobilePath($path)) $disk->delete($m);
    }

    public static function mobilePath(?string $path): ?string
    {
        return ($path && str_ends_with($path, '.webp') && ! str_ends_with($path, '-m.webp'))
            ? substr($path, 0, -5).'-m.webp'
            : null;
    }

    /** URL varian mobile bila filenya ada; null bila tidak (banner lama). */
    public static function mobileUrl(?string $path): ?string
    {
        $m = self::mobilePath($path);
        return ($m && Storage::disk('public')->exists($m)) ? asset('storage/'.$m) : null;
    }

    /** Resize + crop dari tengah sampai tepat $w x $h. */
    private static function cover($src, int $w, int $h)
    {
        $sw = imagesx($src);
        $sh = imagesy($src);
        $scale = max($w / $sw, $h / $sh);
        $cw = (int) round($w / $scale);
        $ch = (int) round($h / $scale);
        $cx = (int) floor(($sw - $cw) / 2);
        $cy = (int) floor(($sh - $ch) / 2);

        $dst = imagecreatetruecolor($w, $h);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        imagecopyresampled($dst, $src, 0, 0, $cx, $cy, $w, $h, $cw, $ch);
        return $dst;
    }
}
