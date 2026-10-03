<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;

class BannerController extends CrudController
{
    protected function cfg(): array
    {
        return [
            'model' => Banner::class, 'title' => 'Banner', 'route' => 'admin.banners', 'dir' => 'banners',
            'order' => ['sort', 'id'],
            'columns' => ['image', 'title', 'sort', 'is_active'],
            'fields' => [
                'title'     => ['label' => 'Judul (untuk alt gambar / SEO)', 'rules' => 'nullable|string|max:150'],
                'image'     => ['label' => 'Gambar banner (disarankan 1600x600 px)', 'type' => 'image', 'rules' => 'required|image|max:4096'],
                'link'      => ['label' => 'Link tujuan (opsional)', 'rules' => 'nullable|string|max:255'],
                'sort'      => ['label' => 'Urutan slide (angka kecil tampil lebih dulu)', 'type' => 'number', 'rules' => 'nullable|integer|min:0'],
                'is_active' => ['label' => 'Tampilkan banner', 'type' => 'checkbox', 'rules' => 'boolean'],
            ],
        ];
    }
}
