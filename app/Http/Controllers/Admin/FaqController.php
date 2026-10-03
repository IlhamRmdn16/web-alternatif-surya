<?php

namespace App\Http\Controllers\Admin;

use App\Models\Faq;

class FaqController extends CrudController
{
    protected function cfg(): array
    {
        return [
            'model' => Faq::class, 'title' => 'FAQ', 'route' => 'admin.faqs', 'dir' => 'faqs',
            'order' => ['sort', 'id'],
            'columns' => ['question', 'sort', 'is_active'],
            'fields' => [
                'question'  => ['label' => 'Pertanyaan', 'rules' => 'required|string|max:255'],
                'answer'    => ['label' => 'Jawaban', 'type' => 'textarea', 'rules' => 'required|string'],
                'sort'      => ['label' => 'Urutan', 'type' => 'number', 'rules' => 'nullable|integer|min:0'],
                'is_active' => ['label' => 'Tampilkan', 'type' => 'checkbox', 'rules' => 'boolean'],
            ],
        ];
    }
}
