<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prospect extends Model
{
    public const PURPOSES = [
        'stok'      => 'Tanya ketersediaan / stok',
        'simulasi'  => 'Minta hitungan kredit (simulasi)',
        'booking'   => 'Pemesanan / booking unit',
        'lainnya'   => 'Lainnya',
    ];
    public const TENORS = [11, 17, 23, 29, 33, 35];
    public const STATUSES = ['baru' => 'Baru', 'dihubungi' => 'Sudah dihubungi', 'selesai' => 'Selesai / deal', 'batal' => 'Batal'];

    protected $guarded = [];

    public function motor() { return $this->belongsTo(Motor::class); }

    public function getPurposeLabelAttribute(): string
    {
        return self::PURPOSES[$this->purpose] ?? '-';
    }
}
