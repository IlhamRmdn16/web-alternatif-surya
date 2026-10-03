<?php

namespace App\Http\Controllers\Admin;

use App\Models\Promo;

class PromoController extends CrudController
{
    protected function cfg(): array
    {
        return [
            'model' => Promo::class, 'title' => 'Promo', 'route' => 'admin.promos', 'dir' => 'promos',
            'order' => ['id'], 'slug_from' => 'title',
            'columns' => ['image', 'title', 'start_date', 'end_date', 'is_active'],
            'fields' => [
                'title'       => ['label' => 'Judul promo', 'rules' => 'required|string|max:150'],
                'image'       => ['label' => 'Gambar promo', 'type' => 'image', 'rules' => 'required|image|max:4096'],
                'description' => ['label' => 'Deskripsi / syarat & ketentuan', 'type' => 'textarea', 'rules' => 'nullable|string'],
                'start_date'  => ['label' => 'Mulai', 'type' => 'date', 'rules' => 'nullable|date'],
                'end_date'    => ['label' => 'Berakhir (kosongkan jika tanpa batas)', 'type' => 'date', 'rules' => 'nullable|date'],
                'is_active'   => ['label' => 'Tampilkan promo', 'type' => 'checkbox', 'rules' => 'boolean'],
            ],
        ];
    }
}
