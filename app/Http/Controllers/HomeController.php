<?php

namespace App\Http\Controllers;

use App\Models\{Banner, Category, Faq, Motor, Promo};
use App\Support\Series;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('is_active', true)->orderBy('sort')->orderBy('id')->get();

        $series = Series::group(Motor::active()->with(['category', 'colors'])->get());

        $categories = Category::where('is_active', true)->orderBy('sort')->get()->each(function ($c) use ($series) {
            $c->setRelation('homeSeries', $series
                ->filter(fn ($s) => $s->category->id === $c->id && $s->home)
                ->sortBy('home_sort')->take(4)->values());
        });

        $promos = Promo::active()->latest()->take(3)->get();
        $faqs = Faq::where('is_active', true)->orderBy('sort')->take(5)->get();

        return view('home', compact('banners', 'categories', 'promos', 'faqs'));
    }
}
