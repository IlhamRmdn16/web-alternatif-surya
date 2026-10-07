<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Category, Motor};
use App\Support\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MotorController extends Controller
{
    public function index(Request $r)
    {
        $motors = Motor::with(['category', 'colors'])
            ->when($r->query('q'), fn ($q, $v) => $q->where(fn ($w) => $w->where('name', 'like', "%$v%")->orWhere('variant', 'like', "%$v%")))
            ->when($r->query('category'), fn ($q, $v) => $q->where('category_id', $v))
            ->when($r->query('status') === 'aktif', fn ($q) => $q->where('is_active', true))
            ->when($r->query('status') === 'nonaktif', fn ($q) => $q->where('is_active', false))
            ->when($r->query('home') === '1', fn ($q) => $q->where('show_on_home', true))
            ->orderBy('name')->orderBy('price')
            ->paginate(Helpers::perPage($r, 10))->onEachSide(1)->withQueryString();

        $categories = Category::orderBy('sort')->get();

        return view('admin.motors.index', compact('motors', 'categories'));
    }

    /** /admin/motors/create?series=Beat -> nama & jenis terisi otomatis untuk menambah tipe baru di seri yang sama. */
    public function create(Request $r)
    {
        $motor = new Motor(['is_active' => true]);
        if ($r->query('series') && $sib = Motor::where('series_slug', Str::slug($r->query('series')))->first()) {
            $motor->name = $sib->name;
            $motor->category_id = $sib->category_id;
        }
        return $this->form($motor);
    }

    public function edit(Motor $motor) { return $this->form($motor); }

    protected function form(Motor $motor)
    {
        $categories = Category::orderBy('sort')->get();
        $colors = $motor->exists
            ? $motor->colors->map(fn ($c) => ['name' => $c->name, 'hex' => $c->hex, 'old_image' => $c->image,
                'custom' => $c->price !== null, 'price' => $c->price, 'cash_discount' => $c->cash_discount])->values()
            : collect();

        // nama seri (huruf kecil) => id jenis, untuk auto-pilih jenis di form
        $seriesMap = Motor::select('name', 'category_id')->get()
            ->unique(fn ($m) => Str::lower($m->name))
            ->mapWithKeys(fn ($m) => [Str::lower($m->name) => $m->category_id]);
        $seriesNames = Motor::select('name')->distinct()->orderBy('name')->pluck('name');

        return view('admin.motors.form', compact('motor', 'categories', 'colors', 'seriesMap', 'seriesNames'));
    }

    public function store(Request $r) { return $this->save($r, new Motor); }

    public function update(Request $r, Motor $motor) { return $this->save($r, $motor); }

    protected function save(Request $r, Motor $motor)
    {
        $data = $r->validate([
            'name'               => 'required|string|max:100',
            'variant'            => 'required|string|max:100',
            'category_id'        => 'required|exists:categories,id',
            'price'              => 'required|integer|min:0',
            'cash_discount'      => 'nullable|integer|min:0|lte:price',
            'description'        => 'nullable|string',
            'home_sort'          => 'nullable|integer|min:0',
            'colors'             => 'required|array|min:1',
            'colors.*.name'      => 'required|string|max:60',
            'colors.*.hex'       => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'colors.*.image'     => 'nullable|image|max:4096',
            'colors.*.old_image' => 'nullable|string|max:255',
            'colors.*.custom'    => 'nullable|in:0,1',
            'colors.*.price'     => 'nullable|integer|min:0',
            'colors.*.cash_discount' => 'nullable|integer|min:0',
        ], [
            'cash_discount.lte' => 'Diskon cash tidak boleh lebih besar dari harga OTR.',
            'colors.required'   => 'Tambahkan minimal 1 warna beserta foto unitnya.',
            'colors.min'        => 'Tambahkan minimal 1 warna beserta foto unitnya.',
        ], [
            'name' => 'Nama motor', 'variant' => 'Tipe', 'price' => 'Harga OTR', 'cash_discount' => 'Diskon cash',
            'colors.*.name' => 'nama warna',
        ]);

        // Nama sama (tanpa peduli huruf besar/kecil) = seri yang sama. Pakai penulisan nama yang sudah ada.
        $name = trim($data['name']);
        $seriesSlug = Str::slug($name) ?: Str::lower(Str::random(6));
        $sibling = Motor::with('category')->where('series_slug', $seriesSlug)->where('id', '!=', $motor->id ?? 0)->first();
        if ($sibling) {
            $name = $sibling->name;
            if ($sibling->category_id != $data['category_id']) {
                return back()->withInput()->withErrors(['category_id' => 'Seri "'.$name.'" sudah ada di jenis '.$sibling->category->name.'. Pilih jenis yang sama.']);
            }
        }

        $variant = trim($data['variant']);
        $dupe = Motor::where('series_slug', $seriesSlug)->whereRaw('lower(variant) = ?', [mb_strtolower($variant)])
            ->where('id', '!=', $motor->id ?? 0)->exists();
        if ($dupe) {
            return back()->withInput()->withErrors(['variant' => 'Tipe "'.$variant.'" sudah ada di seri '.$name.'.']);
        }

        // Cek warna sebelum ada yang disimpan: foto wajib, harga khusus harus valid.
        $oldImages = $motor->exists ? $motor->colors()->pluck('image')->filter()->all() : [];
        $colorErrors = [];
        foreach (array_values($r->input('colors', [])) as $i => $c) {
            $label = 'Warna ke-'.($i + 1).' ('.($c['name'] ?? '-').')';
            $hasFile = $r->hasFile("colors.$i.image");
            $hasOld = ! empty($c['old_image']) && in_array($c['old_image'], $oldImages, true);
            if (! $hasFile && ! $hasOld) $colorErrors[] = $label.': foto unit wajib diunggah.';
            if (($c['custom'] ?? '0') === '1') {
                if (($c['price'] ?? '') === '') $colorErrors[] = $label.': isi harga OTR khusus, atau aktifkan kembali "harga sama dengan tipe".';
                elseif ((int) ($c['cash_discount'] ?? 0) > (int) $c['price']) $colorErrors[] = $label.': diskon cash tidak boleh lebih besar dari harga OTR.';
            }
        }
        if ($colorErrors) return back()->withInput()->withErrors(['colors' => $colorErrors]);

        $fields = [
            'category_id'   => $data['category_id'],
            'name'          => $name,
            'series_slug'   => $seriesSlug,
            'variant'       => $variant,
            'price'         => (int) $data['price'],
            'cash_discount' => (int) ($data['cash_discount'] ?? 0),
            'description'   => $data['description'] ?? null,
            'home_sort'     => (int) ($data['home_sort'] ?? 0),
            'show_on_home'  => $r->boolean('show_on_home'),
            'is_active'     => $r->boolean('is_active'),
        ];
        if (! $motor->exists) $fields['slug'] = Helpers::uniqueSlug(Motor::class, $name.' '.$variant);

        $motor->fill($fields)->save();

        // Warna: nama + foto + (opsional) harga khusus
        $keep = [];
        $motor->colors()->delete();
        foreach (array_values($r->input('colors', [])) as $i => $c) {
            if ($r->hasFile("colors.$i.image")) {
                $image = $r->file("colors.$i.image")->store('colors', 'public');
            } else {
                $image = $c['old_image'];
                $keep[] = $image;
            }
            $custom = ($c['custom'] ?? '0') === '1';
            $motor->colors()->create([
                'name' => $c['name'], 'hex' => $c['hex'] ?? '#000000', 'image' => $image,
                'price' => $custom ? (int) $c['price'] : null,
                'cash_discount' => $custom ? (int) ($c['cash_discount'] ?? 0) : null,
                'sort' => $i,
            ]);
        }
        foreach (array_diff($oldImages, $keep) as $unused) Storage::disk('public')->delete($unused);

        // Foto utama tipe = foto warna pertama
        $motor->update(['image' => $motor->colors()->value('image')]);

        return redirect()->route('admin.motors.index')->with('ok', 'Motor "'.$name.' '.$variant.'" berhasil disimpan.');
    }

    public function destroy(Motor $motor)
    {
        foreach ($motor->colors as $c) if ($c->image) Storage::disk('public')->delete($c->image);
        if ($motor->image) Storage::disk('public')->delete($motor->image);
        $motor->delete();
        return back()->with('ok', 'Tipe motor dihapus.');
    }
}
