<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Prospect;
use App\Support\Series;

class MotorController extends Controller
{
    public function show(Motor $motor)
    {
        abort_unless($motor->is_active, 404);
        $motor->load(['category', 'colors']);

        // Semua tipe dalam seri yang sama (nama sama)
        $siblings = Motor::active()->with('colors')->where('series_slug', $motor->series_slug)->orderBy('price')->get();

        $related = Series::group(
            Motor::active()->with(['category', 'colors'])
                ->where('category_id', $motor->category_id)
                ->where('series_slug', '!=', $motor->series_slug)->get()
        )->take(4);

        $purposes = Prospect::PURPOSES;
        $tenors = Prospect::TENORS;

        return view('motor', compact('motor', 'siblings', 'related', 'purposes', 'tenors'));
    }
}
