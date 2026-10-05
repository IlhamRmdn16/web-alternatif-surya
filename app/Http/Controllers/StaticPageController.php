<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Support\PageDefaults;

class StaticPageController extends Controller
{
    public function show(string $page)
    {
        $defaults = PageDefaults::all();
        abort_unless(isset($defaults[$page]), 404);

        // Dibuat otomatis dengan isi bawaan jika belum ada; selanjutnya diedit dari Admin > Halaman.
        $model = Page::firstOrCreate(['slug' => $page], $defaults[$page]);

        return view('page', ['page' => $model]);
    }
}
