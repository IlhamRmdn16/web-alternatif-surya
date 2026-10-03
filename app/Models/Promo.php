<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $guarded = [];
    protected $casts = ['is_active' => 'boolean', 'start_date' => 'date', 'end_date' => 'date'];

    public function getRouteKeyName(): string { return 'slug'; }

    public function scopeActive($q)
    {
        return $q->where('is_active', true)
            ->where(fn ($w) => $w->whereNull('end_date')->orWhere('end_date', '>=', now()->toDateString()));
    }
}
