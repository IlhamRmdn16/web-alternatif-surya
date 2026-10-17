<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Brochure extends Model
{
    protected $guarded = [];
    protected $casts = ['is_active' => 'boolean'];

    public function getRouteKeyName(): string { return 'slug'; }

    /** "2,4 MB" / "850 KB" */
    public function getSizeLabelAttribute(): string
    {
        $b = (int) $this->file_size;
        if ($b <= 0) return '';
        return $b >= 1048576
            ? number_format($b / 1048576, 1, ',', '.').' MB'
            : number_format($b / 1024, 0, ',', '.').' KB';
    }

    /** Nama file saat diunduh, mis. daftar-harga-motor-honda.pdf */
    public function getDownloadNameAttribute(): string
    {
        return (Str::slug($this->title) ?: 'brosur').'.pdf';
    }
}
