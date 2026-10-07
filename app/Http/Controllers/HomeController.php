<?php

namespace App\Http\Controllers;

use App\Models\{Banner, Category, Faq, Motor, Post, Promo};

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('is_active', true)->orderBy('sort')->orderBy('id')->get();

        // Beranda menampilkan TIPE motor (bukan seri): setiap tipe yang dicentang "Tampilkan di beranda"
        // menjadi satu kartu sendiri dengan foto, tipe, dan harganya masing-masing. Maksimal 4 per jenis,
        // urut berdasarkan "Urutan di beranda". Dibungkus seperti objek seri agar kartu & view beranda tetap sama.
        $homeTypes = Motor::active()->where('show_on_home', true)
            ->with(['category', 'colors'])->orderBy('home_sort')->orderBy('id')->get();

        $categories = Category::where('is_active', true)->orderBy('sort')->get()->each(function ($c) use ($homeTypes) {
            $c->setRelation('homeSeries', $homeTypes->where('category_id', $c->id)->take(4)->map(fn ($m) => (object) [
                'single'   => true,
                'slug'     => $m->slug,
                'name'     => $m->name,
                'category' => $m->category,
                'types'    => collect([$m]),
                'cheapest' => $m,
                'image'    => $m->cover_image,
                'url'      => route('motor.show', $m),
            ])->values());
        });

        $promos = Promo::active()->latest()->take(3)->get();
        $faqs = Faq::where('is_active', true)->orderBy('sort')->get();   // FAQ hanya ada di beranda: tampilkan semua
        $posts = Post::published()->orderByDesc('published_at')->take(4)->get();

        return view('home', compact('banners', 'categories', 'promos', 'faqs', 'posts'));
    }
}
