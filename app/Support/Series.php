<?php

namespace App\Support;

use Illuminate\Support\Collection;

/** Mengelompokkan tipe motor yang bernama sama menjadi satu "Seri". Pastikan relasi category & colors sudah di-load. */
class Series
{
    public static function group(Collection $motors): Collection
    {
        return $motors->groupBy('series_slug')->map(function (Collection $types) {
            $types = $types->sortBy(fn ($t) => $t->lowest['cash'])->values();
            $cheapest = $types->first();
            $homeTypes = $types->where('show_on_home', true);

            return (object) [
                'slug'      => $cheapest->series_slug,
                'name'      => $cheapest->name,
                'category'  => $cheapest->category,
                'types'     => $types,
                'cheapest'  => $cheapest,
                'image'     => $cheapest->cover_image ?: $types->first(fn ($t) => $t->cover_image)?->cover_image,
                'home'      => $homeTypes->isNotEmpty(),
                'home_sort' => $homeTypes->min('home_sort') ?? 0,
                'url'       => route('motor.show', $cheapest),
            ];
        })->values();
    }
}
