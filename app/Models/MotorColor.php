<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotorColor extends Model
{
    protected $guarded = [];
    protected $casts = ['price' => 'integer', 'cash_discount' => 'integer'];

    public function motor() { return $this->belongsTo(Motor::class); }
}
