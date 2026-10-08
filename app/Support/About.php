<?php

namespace App\Support;

use App\Models\Setting;

class About
{
    /** Isi halaman Tentang Kami: nilai tersimpan (Admin > Tentang Kami) digabung dengan isi bawaan. */
    public static function get(): array
    {
        $stored = json_decode((string) Setting::where('key', 'about_page')->value('value'), true);
        return array_replace_recursive(AboutDefaults::all(), is_array($stored) ? $stored : []);
    }
}
