<?php

namespace App\Http\Controllers;

use App\Models\{Brochure, Category, Motor, Promo};
use Illuminate\Support\Facades\Schema;
use App\Support\Series;

class PageController extends Controller
{
    public function pricelist()
    {
        $series = Series::group(Motor::active()->with(['category', 'colors'])->get());

        $categories = Category::where('is_active', true)->orderBy('sort')->get()->each(function ($c) use ($series) {
            $c->setRelation('series', $series->filter(fn ($s) => $s->category->id === $c->id)->sortBy('name')->values());
        })->filter(fn ($c) => $c->series->isNotEmpty())->values();

        // Brosur & daftar harga PDF (Admin > Brosur & Daftar Harga). Aman jika migrasi belum dijalankan.
        $brochures = Schema::hasTable('brochures')
            ? Brochure::where('is_active', true)->orderBy('sort')->orderByDesc('id')->get()
            : collect();

        return view('pricelist', compact('categories', 'brochures'));
    }

    public function promos()
    {
        $promos = Promo::active()->latest()->paginate(9);
        return view('promos', compact('promos'));
    }

    public function promo(Promo $promo)
    {
        abort_unless($promo->is_active, 404);
        return view('promo-show', compact('promo'));
    }
}
