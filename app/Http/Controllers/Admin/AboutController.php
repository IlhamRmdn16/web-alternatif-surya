<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    public function edit()
    {
        return view('admin.about.edit', ['about' => About::get()]);
    }

    public function update(Request $r)
    {
        $r->validate([
            'about.hero.title' => 'required|string|max:150',
            'hero_photo'       => 'nullable|image|max:5120',
            'story_photo'      => 'nullable|image|max:5120',
        ], [], ['about.hero.title' => 'Judul utama', 'hero_photo' => 'Foto hero', 'story_photo' => 'Foto cerita']);

        $current = About::get();
        $data = array_replace_recursive($current, $this->clean($r->input('about', [])));

        // Foto: ganti, hapus, atau biarkan
        foreach (['hero' => 'hero_photo', 'story' => 'story_photo'] as $section => $field) {
            $old = $current[$section]['photo'] ?? null;
            if ($r->hasFile($field)) {
                if ($old) Storage::disk('public')->delete($old);
                $data[$section]['photo'] = $r->file($field)->store('about', 'public');
            } elseif ($r->boolean('remove_'.$field) && $old) {
                Storage::disk('public')->delete($old);
                $data[$section]['photo'] = null;
            } else {
                $data[$section]['photo'] = $old;
            }
        }

        Setting::updateOrCreate(['key' => 'about_page'], ['value' => json_encode($data, JSON_UNESCAPED_UNICODE)]);

        return redirect()->route('admin.about.edit')->with('ok', 'Halaman Tentang Kami berhasil disimpan.');
    }

    private function clean($value)
    {
        return is_array($value)
            ? array_map(fn ($v) => $this->clean($v), $value)
            : mb_substr(trim((string) $value), 0, 3000);
    }
}
