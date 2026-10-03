<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public const FIELDS = [
        'Umum' => [
            'site_name' => ['Nama website', 'text'],
            'logo'      => ['Logo (PNG/WEBP)', 'image'],
            'og_image'  => ['Gambar share sosmed / OG (JPG 1200x630)', 'image'],
        ],
        'Kontak' => [
            'wa_number' => ['Nomor WhatsApp (format 62xxxxxxxxxx)', 'text'],
            'phone'     => ['Telepon', 'text'],
            'email'     => ['Email', 'text'],
            'address'   => ['Alamat dealer', 'textarea'],
            'map_url'   => ['Link Google Maps', 'text'],
            'hours'     => ['Jam operasional (satu baris per hari)', 'textarea'],
        ],
        'Sosial media' => [
            'instagram' => ['Instagram (URL lengkap)', 'text'],
            'facebook'  => ['Facebook (URL lengkap)', 'text'],
            'tiktok'    => ['TikTok (URL lengkap)', 'text'],
            'youtube'   => ['YouTube (URL lengkap)', 'text'],
        ],
        'Beranda & SEO' => [
            'home_h1'              => ['Judul utama beranda (H1)', 'text'],
            'home_intro'           => ['Paragraf pengantar beranda', 'textarea'],
            'seo_home_title'       => ['SEO title beranda (maks. ±60 karakter)', 'text'],
            'seo_home_description' => ['SEO description (maks. ±160 karakter)', 'textarea'],
        ],
    ];

    public function edit()
    {
        return view('admin.settings', ['groups' => self::FIELDS]);
    }

    public function update(Request $r)
    {
        foreach (self::FIELDS as $group) {
            foreach ($group as $key => [$label, $type]) {
                if ($type === 'image') {
                    $r->validate([$key => 'nullable|image|max:4096']);
                    if ($r->hasFile($key)) {
                        $old = Setting::where('key', $key)->value('value');
                        if ($old) Storage::disk('public')->delete($old);
                        Setting::updateOrCreate(['key' => $key], ['value' => $r->file($key)->store('site', 'public')]);
                    }
                } else {
                    Setting::updateOrCreate(['key' => $key], ['value' => $r->input($key)]);
                }
            }
        }
        return back()->with('ok', 'Pengaturan disimpan.');
    }
}
