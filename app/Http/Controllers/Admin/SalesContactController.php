<?php

namespace App\Http\Controllers\Admin;

use App\Models\SalesContact;

class SalesContactController extends CrudController
{
    protected function cfg(): array
    {
        return [
            'model' => SalesContact::class, 'title' => 'Sales Counter', 'route' => 'admin.sales', 'dir' => 'sales',
            'order' => ['sort', 'id'],
            'columns' => ['photo', 'name', 'position', 'phone', 'sort', 'is_active'],
            'fields' => [
                'name'      => ['label' => 'Nama sales', 'rules' => 'required|string|max:100'],
                'position'  => ['label' => 'Jabatan (opsional, tidak ditampilkan di website)', 'rules' => 'nullable|string|max:100', 'default' => 'Sales Counter'],
                'phone'     => ['label' => 'Nomor WhatsApp (opsional, hanya arsip internal, tidak ditampilkan di website)', 'rules' => ['nullable', 'string', 'regex:/^[0-9+\-\s]{8,20}$/']],
                'photo'     => ['label' => 'Foto (tampil di halaman Tentang Kami, disarankan rasio persegi)', 'type' => 'image', 'rules' => 'nullable|image|max:4096'],
                'sort'      => ['label' => 'Urutan tampil', 'type' => 'number', 'rules' => 'nullable|integer|min:0'],
                'is_active' => ['label' => 'Tampilkan di website', 'type' => 'checkbox', 'rules' => 'boolean'],
            ],
        ];
    }
}
