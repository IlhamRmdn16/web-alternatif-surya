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
    private const MAX_IMAGES = 6;

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
            'new_images'       => 'nullable|array',
            'new_images.*'     => 'image|max:4096',
            'remove_images'    => 'nullable|array',
            'remove_images.*'  => 'string',
        ], [], ['title' => 'Judul', 'content' => 'Isi halaman', 'new_images.*' => 'Foto']);

        $content = Html::clean($data['content']);
        if ($content === '') {
            return back()->withInput()->withErrors(['content' => 'Isi halaman wajib diisi.']);
        }

        // Foto di sebelah kanan teks
        $images = collect($page->images ?? []);
        $remove = $r->input('remove_images', []);
        foreach ($images->filter(fn ($img) => in_array($img, $remove, true)) as $gone) {
            Storage::disk('public')->delete($gone);
        }
        $images = $images->reject(fn ($img) => in_array($img, $remove, true))->values();

        foreach ((array) $r->file('new_images', []) as $file) {
            if ($images->count() >= self::MAX_IMAGES) break;
            $images->push($file->store('pages', 'public'));
        }

        $page->update([
            'title'            => $data['title'],
            'meta_title'       => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'content'          => $content,
            'images'           => $images->all(),
        ]);

        return redirect()->route('admin.pages.index')->with('ok', 'Halaman "'.$page->title.'" berhasil diperbarui.');
    }
}
