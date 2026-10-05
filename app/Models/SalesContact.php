<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesContact extends Model
{
    protected $guarded = [];
    protected $casts = ['is_active' => 'boolean'];

    /** Nomor format internasional (62...) untuk link wa.me */
    public function getWaNumberAttribute(): string
    {
        return Setting::normalizeNumber($this->phone);
    }

    /** Nomor format lokal (08...) untuk ditampilkan */
    public function getDisplayPhoneAttribute(): string
    {
        $n = $this->wa_number;
        return str_starts_with($n, '62') ? '0'.substr($n, 2) : $n;
    }
}
