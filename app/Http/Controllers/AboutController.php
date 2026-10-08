<?php

namespace App\Http\Controllers;

use App\Support\About;

class AboutController extends Controller
{
    public function __invoke()
    {
        return view('about', ['about' => About::get()]);
    }
}
