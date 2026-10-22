<?php

namespace App\Http\Controllers;

use App\Models\SalesContact;
use App\Support\About;

class AboutController extends Controller
{
    public function __invoke()
    {
        // Sales counter hanya tampil di halaman ini (foto + nama saja).
        $sales = SalesContact::where('is_active', true)->orderBy('sort')->orderBy('id')->get(['id', 'name', 'photo']);

        return view('about', ['about' => About::get(), 'sales' => $sales]);
    }
}
