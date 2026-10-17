<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Prospect;

class MotorController extends Controller
{
    public function show(Motor $motor)
    {
        abort_unless($motor->is_active, 404);
        $motor->load(['category', 'colors']);

        // Semua tipe dalam seri yang sama (nama sama)
        $siblings = Motor::active()->with('colors')->where('series_slug', $motor->series_slug)->orderBy('price')->get();

        // Rekomendasi per TIPE (bukan seri): satu tipe acak dari tiap seri lain di jenis yang sama,
        // dibungkus seperti objek seri agar kartu (partials/series-card) sama dengan di beranda.
        $related = Motor::active()->with(['category', 'colors'])
            ->where('category_id', $motor->category_id)
            ->where('series_slug', '!=', $motor->series_slug)->get()
            ->groupBy('series_slug')->map(fn ($types) => $types->random())
            ->shuffle()->take(4)->values()
            ->map(fn ($m) => (object) [
                'single'   => true,
                'slug'     => $m->slug,
                'name'     => $m->name,
                'category' => $m->category,
                'types'    => collect([$m]),
                'cheapest' => $m,
                'image'    => $m->cover_image,
                'url'      => route('motor.show', $m),
            ]);

        $purposes = Prospect::PURPOSES;
        $tenors = Prospect::TENORS;

        return view('motor', compact('motor', 'siblings', 'related', 'purposes', 'tenors'));
    }
}
