<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controller CRUD generik. Subclass cukup mendefinisikan cfg():
 * model, title, route, dir, columns[], fields[name => label,type,rules,options,default], order[], slug_from
 */
abstract class CrudController extends Controller
{
    abstract protected function cfg(): array;

    protected function find($id): Model
    {
        $m = new ($this->cfg()['model']);
        return $m->where($m->getRouteKeyName(), $id)->firstOrFail();
    }

    public function index()
    {
        $c = $this->cfg();
        $q = $c['model']::query();
        foreach ($c['order'] ?? ['id'] as $col) $q->orderBy($col);
        $items = $q->paginate(20);
        return view('admin.crud.index', compact('c', 'items'));
    }

    public function create()
    {
        $c = $this->cfg();
        $item = new ($c['model']);
        return view('admin.crud.form', compact('c', 'item'));
    }

    public function store(Request $r)
    {
        $c = $this->cfg();
        $data = $this->validated($r, null);
        if (isset($c['slug_from'])) $data['slug'] = Helpers::uniqueSlug($c['model'], $data[$c['slug_from']]);
        $c['model']::create($data);
        return redirect()->route($c['route'].'.index')->with('ok', $c['title'].' berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $c = $this->cfg();
        $item = $this->find($id);
        return view('admin.crud.form', compact('c', 'item'));
    }

    public function update(Request $r, $id)
    {
        $c = $this->cfg();
        $item = $this->find($id);
        $item->update($this->validated($r, $item));
        return redirect()->route($c['route'].'.index')->with('ok', $c['title'].' berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $c = $this->cfg();
        $item = $this->find($id);
        if ($msg = $this->blockDelete($item)) return back()->with('err', $msg);
        foreach ($c['fields'] as $n => $f) {
            if (($f['type'] ?? '') === 'image' && $item->$n) Storage::disk('public')->delete($item->$n);
        }
        $item->delete();
        return back()->with('ok', 'Data berhasil dihapus.');
    }

    protected function blockDelete(Model $item): ?string { return null; }

    protected function validated(Request $r, ?Model $item): array
    {
        $c = $this->cfg();
        $rules = [];
        foreach ($c['fields'] as $n => $f) {
            $rule = $f['rules'] ?? 'nullable';
            if (($f['type'] ?? '') === 'image' && $item) $rule = str_replace('required', 'nullable', $rule);
            $rules[$n] = $rule;
        }
        $data = $r->validate($rules);

        foreach ($c['fields'] as $n => $f) {
            $t = $f['type'] ?? 'text';
            if ($t === 'checkbox') $data[$n] = $r->boolean($n);
            if ($t === 'number' && ($data[$n] ?? null) === null) $data[$n] = 0;
            if ($t === 'image') {
                if ($r->hasFile($n)) {
                    if ($item && $item->$n) Storage::disk('public')->delete($item->$n);
                    $data[$n] = $r->file($n)->store($c['dir'], 'public');
                } else {
                    unset($data[$n]);
                }
            }
        }
        return $data;
    }
}
