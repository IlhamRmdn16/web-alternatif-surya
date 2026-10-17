<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brochure;
use App\Support\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrochureController extends Controller
{
    public function index()
    {
        $brochures = Brochure::orderBy('sort')->orderByDesc('id')->get();
        return view('admin.brochures.index', compact('brochures'));
    }

    public function create()
    {
        return view('admin.brochures.form', ['brochure' => new Brochure(['is_active' => true])]);
    }

    public function edit(Brochure $brochure)
    {
        return view('admin.brochures.form', compact('brochure'));
    }

    public function store(Request $r) { return $this->save($r, new Brochure); }

    public function update(Request $r, Brochure $brochure) { return $this->save($r, $brochure); }

    protected function save(Request $r, Brochure $brochure)
    {
        $data = $r->validate([
            'title'       => 'required|string|max:150',
            'description' => 'nullable|string|max:300',
            'file'        => ($brochure->exists ? 'nullable' : 'required').'|file|mimes:pdf|max:20480',
            'sort'        => 'nullable|integer|min:0',
        ], [
            'file.required' => 'File PDF wajib diunggah.',
            'file.mimes'    => 'File harus berformat PDF.',
            'file.max'      => 'Ukuran PDF maksimal 20 MB.',
            'file.uploaded' => 'Upload gagal. Kemungkinan ukuran file melebihi batas server (upload_max_filesize di php.ini).',
        ], ['title' => 'Judul', 'description' => 'Keterangan', 'file' => 'File PDF']);

        $fields = [
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'sort'        => (int) ($data['sort'] ?? 0),
            'is_active'   => $r->boolean('is_active'),
        ];
        if (! $brochure->exists) $fields['slug'] = Helpers::uniqueSlug(Brochure::class, $data['title']);

        if ($r->hasFile('file')) {
            if ($brochure->file) Storage::disk('public')->delete($brochure->file);
            $path = $r->file('file')->store('brochures', 'public');
            $fields['file'] = $path;
            $fields['file_size'] = Storage::disk('public')->size($path);
        }

        $brochure->fill($fields)->save();

        return redirect()->route('admin.brochures.index')->with('ok', 'Brosur "'.$brochure->title.'" berhasil disimpan.');
    }

    public function destroy(Brochure $brochure)
    {
        if ($brochure->file) Storage::disk('public')->delete($brochure->file);
        $brochure->delete();
        return back()->with('ok', 'Brosur dihapus.');
    }
}
