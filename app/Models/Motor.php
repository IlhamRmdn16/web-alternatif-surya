<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Motor extends Model
{
    protected $guarded = [];
    protected $casts = ['is_active' => 'boolean', 'show_on_home' => 'boolean'];

    public function getRouteKeyName(): string { return 'slug'; }

    public function category() { return $this->belongsTo(Category::class); }
    public function colors() { return $this->hasMany(MotorColor::class)->orderBy('sort')->orderBy('id'); }

    public function scopeActive($q) { return $q->where('is_active', true); }

    /**
     * Harga untuk satu warna. Jika warna tidak punya harga khusus, mengikuti harga tipe.
     * Mengembalikan: price (OTR), discount (diskon cash), cash (harga setelah diskon), custom (bool).
     */
    public function offerFor(?MotorColor $color = null): array
    {
        $custom = $color && $color->price !== null;
        $price = $custom ? (int) $color->price : (int) $this->price;
        $disc = $custom ? (int) ($color->cash_discount ?? 0) : (int) $this->cash_discount;
        $disc = min($disc, $price);

        return ['price' => $price, 'discount' => $disc, 'cash' => max($price - $disc, 0), 'custom' => $custom];
    }

    /** Daftar harga setiap warna (atau harga tipe jika belum ada warna). */
    public function getOffersAttribute(): Collection
    {
        return $this->colors->isEmpty()
            ? collect([$this->offerFor()])
            : $this->colors->map(fn ($c) => $this->offerFor($c));
    }

    /** Harga termurah dari semua warna - dipakai untuk tampilan "mulai dari". */
    public function getLowestOfferAttribute(): array
    {
        $priced = $this->offers->filter(fn ($o) => $o['price'] > 0);
        return $priced->isEmpty() ? $this->offerFor() : $priced->sortBy('cash')->first();
    }

    /** True jika ada warna yang harganya berbeda dari warna lain. */
    public function getHasVariedPricesAttribute(): bool
    {
        return $this->offers->unique(fn ($o) => $o['price'].'-'.$o['discount'])->count() > 1;
    }

    /** Harga cash berdasarkan harga dasar tipe. */
    public function getCashPriceAttribute(): int
    {
        return max((int) $this->price - (int) $this->cash_discount, 0);
    }

    public function getHasDiscountAttribute(): bool
    {
        return $this->cash_discount > 0 && $this->price > 0;
    }

    public function getDisplayNameAttribute(): string
    {
        return Str::startsWith(Str::lower($this->name), 'honda') ? $this->name : 'Honda '.$this->name;
    }

    public function getFullNameAttribute(): string
    {
        return $this->display_name.' '.$this->variant;
    }
}
