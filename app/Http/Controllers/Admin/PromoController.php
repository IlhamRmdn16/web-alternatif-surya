<?php

namespace App\Http\Controllers\Admin;

use App\Models\Promo;
use App\Support\Html;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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

    // Form promo memakai editor seperti MS Word (sama dengan Berita), bukan form generik.
    public function create()
    {
        return view('admin.promos.form', ['promo' => new Promo(['is_active' => true])]);
    }

    public function edit($id)
    {
        return view('admin.promos.form', ['promo' => $this->find($id)]);
    }

    /** Deskripsi dari editor dibersihkan (hanya tag aman) sebelum disimpan. */
    protected function validated(Request $r, ?Model $item): array
    {
        $data = parent::validated($r, $item);
        $data['description'] = Html::clean($data['description'] ?? '') ?: null;
        return $data;
    }
}
