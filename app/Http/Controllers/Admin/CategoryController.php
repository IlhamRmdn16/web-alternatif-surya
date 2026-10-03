<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class CategoryController extends CrudController
{
    protected function cfg(): array
    {
        return [
            'model' => Category::class, 'title' => 'Jenis Motor', 'route' => 'admin.categories', 'dir' => 'categories',
            'order' => ['sort', 'id'], 'slug_from' => 'name',
            'columns' => ['name', 'sort', 'is_active'],
            'fields' => [
                'name'      => ['label' => 'Nama jenis (mis. Matic, Sport, EV, Cub)', 'rules' => 'required|string|max:50'],
                'sort'      => ['label' => 'Urutan tab', 'type' => 'number', 'rules' => 'nullable|integer|min:0'],
                'is_active' => ['label' => 'Aktif', 'type' => 'checkbox', 'rules' => 'boolean'],
            ],
        ];
    }

    protected function blockDelete(Model $item): ?string
    {
        return $item->motors()->exists() ? 'Jenis ini masih memiliki motor. Pindahkan atau hapus motornya dulu.' : null;
    }
}
