<?php

namespace App\Http\Controllers;

use App\Models\SalesContact;

class ContactController extends Controller
{
    public function __invoke()
    {
        $sales = SalesContact::where('is_active', true)->orderBy('sort')->orderBy('id')->get();
        return view('contact', compact('sales'));
    }
}
