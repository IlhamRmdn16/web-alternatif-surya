<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    /** Dipanggil editor (TinyMCE) saat menyisipkan/menempel gambar. */
    public function image(Request $r)
    {
        $r->validate(['file' => 'required|image|max:5120'], [
            'file.image' => 'File harus berupa gambar (jpg, png, webp, gif).',
            'file.max'   => 'Ukuran gambar maksimal 5 MB.',
        ]);

        $path = $r->file('file')->store('editor', 'public');

        return response()->json(['location' => '/storage/'.$path]);
    }
}
