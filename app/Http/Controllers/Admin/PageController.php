<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Support\BannerImage;
use App\Support\Html;
use App\Support\PageDefaults;
use Illuminate\Http\Request;

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
            'banner'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120|dimensions:min_width=1000,min_height=420',
        ], [
            'banner.dimensions' => 'Banner minimal 1000x420 px (disarankan 1240x520 px) agar tidak pecah.',
            'banner.max'        => 'Ukuran file banner maksimal 5 MB.',
        ], ['title' => 'Judul', 'content' => 'Isi halaman', 'banner' => 'Banner']);

        $content = Html::clean($data['content']);
        if ($content === '') {
            return back()->withInput()->withErrors(['content' => 'Isi halaman wajib diisi.']);
        }

        // Banner (1 gambar): ganti, hapus, atau biarkan
        $banner = $page->banner;
        if ($r->hasFile('banner')) {
            BannerImage::delete($banner);
            $banner = BannerImage::store($r->file('banner'), 'pages');
        } elseif ($r->boolean('remove_banner') && $banner) {
            BannerImage::delete($banner);
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
