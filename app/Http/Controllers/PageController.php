<?php

namespace App\Http\Controllers;

use App\Models\{Brochure, Category, Motor, Promo};
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function pricelist()
    {
        // Daftar harga per TIPE: satu kartu per tipe motor (sama seperti kartu di beranda), dikelompokkan per jenis.
        $motors = Motor::active()->with(['category', 'colors'])->orderBy('name')->orderBy('price')->get();

        $categories = Category::where('is_active', true)->orderBy('sort')->get()->each(function ($c) use ($motors) {
            $c->setRelation('cards', $motors->where('category_id', $c->id)->map(fn ($m) => (object) [
                'single'   => true,
                'slug'     => $m->slug,
                'name'     => $m->name,
                'category' => $m->category,
                'types'    => collect([$m]),
                'cheapest' => $m,
                'image'    => $m->cover_image,
                'url'      => route('motor.show', $m),
                'hay'      => Str::lower($m->display_name.' '.$m->variant.' '.$m->category->name),   // teks pencarian
            ])->values());
        })->filter(fn ($c) => $c->cards->isNotEmpty())->values();

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
