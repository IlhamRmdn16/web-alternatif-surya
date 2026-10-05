<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $guarded = [];
    protected $casts = ['is_published' => 'boolean', 'published_at' => 'datetime'];

    public function getRouteKeyName(): string { return 'slug'; }

    /** Hanya berita yang sudah terbit (dan tanggal terbitnya sudah tiba). */
    public function scopePublished($q)
    {
        return $q->where('is_published', true)
            ->where(fn ($w) => $w->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    public function getIsLiveAttribute(): bool
    {
        return $this->is_published && (! $this->published_at || $this->published_at->lte(now()));
    }

    /** Ringkasan untuk kartu & meta description. */
    public function getSummaryAttribute(): string
    {
        return $this->excerpt ?: Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($this->content))), 155);
    }
}
