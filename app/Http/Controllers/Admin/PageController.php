<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Support\Html;
use App\Support\PageDefaults;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function index()
    {
        foreach (PageDefaults::all() as $slug => $defaults) {
            Page::firstOrCreate(['slug' => $slug], $defaults);
        }
        $pages = Page::orderBy('id')->get();
        return view('admin.pages.index', compact('pages'));
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $r, Page $page)
    {
        $data = $r->validate([
            'title'            => 'required|string|max:150',
            'meta_title'       => 'nullable|string|max:150',
            'meta_description' => 'nullable|string|max:300',
            'content'          => 'required|string',
            'banner'           => 'nullable|image|max:5120',
        ], [], ['title' => 'Judul', 'content' => 'Isi halaman', 'banner' => 'Banner']);

        $content = Html::clean($data['content']);
        if ($content === '') {
            return back()->withInput()->withErrors(['content' => 'Isi halaman wajib diisi.']);
        }

        // Banner (1 gambar): ganti, hapus, atau biarkan
        $banner = $page->banner;
        if ($r->hasFile('banner')) {
            if ($banner) Storage::disk('public')->delete($banner);
            $banner = $r->file('banner')->store('pages', 'public');
        } elseif ($r->boolean('remove_banner') && $banner) {
            Storage::disk('public')->delete($banner);
            $banner = null;
        }

        $page->update([
            'title'            => $data['title'],
            'meta_title'       => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'content'          => $content,
            'banner'           => $banner,
        ]);

        return redirect()->route('admin.pages.index')->with('ok', 'Halaman "'.$page->title.'" berhasil diperbarui.');
    }
}
