@extends('admin.layout')
@section('title', $motor->exists ? 'Edit '.$motor->name.' '.$motor->variant : 'Tambah Motor / Tipe')
@section('content')
<form method="POST" enctype="multipart/form-data" action="{{ $motor->exists ? route('admin.motors.update', $motor) : route('admin.motors.store') }}"
      x-data="motorForm({{ Js::from(old('colors') ? array_values(old('colors')) : $colors) }}, {{ Js::from($seriesMap) }}, {{ Js::from(['name' => old('name', $motor->name), 'category' => old('category_id', $motor->category_id), 'price' => old('price', $motor->price), 'discount' => old('cash_discount', $motor->cash_discount)]) }})"
      class="max-w-3xl space-y-6">
    @csrf @if($motor->exists) @method('PUT') @endif

    <div class="space-y-5 rounded-2xl bg-white p-6 shadow-sm">
        <h2 class="font-bold">Info motor</h2>
        <div class="grid gap-5 sm:grid-cols-2">
            <label class="block text-sm font-semibold">Nama motor (seri)
                <input type="text" name="name" x-model="name" @input="pickCategory()" list="series-list" required placeholder="mis. Beat" class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5">
                <datalist id="series-list">@foreach($seriesNames as $n)<option value="{{ $n }}">@endforeach</datalist>
                <span class="mt-1 block text-xs font-normal text-zinc-500">Nama sama = digabung jadi satu seri ("Beat Series").</span></label>
            <label class="block text-sm font-semibold">Tipe
                <input type="text" name="variant" value="{{ old('variant', $motor->variant) }}" required placeholder="mis. CBS, CBS ISS, ABS" class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5"></label>
        </div>
        <label class="block text-sm font-semibold">Jenis motor
            <select name="category_id" x-model="category" required class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5">
                <option value="">Pilih jenis</option>
                @foreach($categories as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
            </select></label>

        <div class="grid gap-5 sm:grid-cols-2">
            <label class="block text-sm font-semibold">Harga OTR (Rp)
                <input type="number" min="0" name="price" x-model="price" required class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5"></label>
            <label class="block text-sm font-semibold">Diskon pembelian cash (Rp)
                <input type="number" min="0" name="cash_discount" x-model="discount" placeholder="0 = tanpa diskon" class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5">
                <span class="mt-1 block text-xs font-normal text-zinc-500">Kosongkan atau isi 0 jika unit ini tidak ada diskon.</span></label>
        </div>
        <p class="rounded-xl bg-zinc-50 px-4 py-3 text-sm" x-show="Number(discount) > 0" x-cloak>
            Tampilan di website: <s class="text-zinc-400" x-text="'Rp ' + Number(price || 0).toLocaleString('id-ID')"></s>
            <b class="ml-2 text-honda" x-text="'Rp ' + Math.max(Number(price || 0) - Number(discount || 0), 0).toLocaleString('id-ID')"></b>
        </p>

        <label class="block text-sm font-semibold">Deskripsi (tampil di halaman detail, penting untuk SEO)
            <textarea name="description" rows="5" class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5">{{ old('description', $motor->description) }}</textarea></label>
        <div class="flex flex-wrap items-center gap-6">
            <input type="hidden" name="is_active" value="0">
            <label class="flex items-center gap-2 text-sm font-semibold"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $motor->is_active ?? true))> Aktif (tampil di website)</label>
            <input type="hidden" name="show_on_home" value="0">
            <label class="flex items-center gap-2 text-sm font-semibold"><input type="checkbox" name="show_on_home" value="1" @checked(old('show_on_home', $motor->show_on_home))> Tampilkan seri ini di beranda</label>
            <label class="flex items-center gap-2 text-sm font-semibold">Urutan di beranda
                <input type="number" min="0" name="home_sort" value="{{ old('home_sort', $motor->home_sort ?? 0) }}" class="w-20 rounded-xl border border-zinc-300 px-3 py-1.5"></label>
        </div>
        <p class="text-xs text-zinc-500">Beranda menampilkan maksimal 4 seri per jenis. Cukup satu tipe dalam seri yang dicentang agar seri tampil.</p>
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between"><h2 class="font-bold">Warna & foto unit</h2>
            <button type="button" @click="addColor()" class="text-sm font-bold text-honda">+ Tambah warna</button></div>
        <p class="mt-1 text-xs text-zinc-500">Isi nama warna, lalu upload foto unit warna tersebut. Foto warna pertama menjadi foto utama tipe ini. Harga otomatis sama dengan tipe; matikan opsinya jika harga warna tertentu berbeda.</p>
        @if($errors->has('colors') || $errors->has('colors.*'))
            <p class="mt-3 text-sm text-red-600">Periksa kembali data warna di bawah.</p>
        @endif
        <div class="mt-4 space-y-4">
            <template x-for="(c, i) in colors" :key="c.uid">
                <div class="space-y-3 rounded-xl border border-zinc-200 p-4">
                    <div class="flex flex-wrap items-center gap-2">
                        <input type="text" :name="'colors['+i+'][name]'" x-model="c.name" placeholder="Nama warna, mis. Hitam Doff" required class="min-w-0 flex-1 rounded-xl border border-zinc-300 px-4 py-2.5">
                        <input type="color" :name="'colors['+i+'][hex]'" x-model="c.hex" title="Warna bulatan pilihan di website" class="h-11 w-14 rounded-lg border border-zinc-300">
                        <button type="button" @click="colors.splice(i, 1)" class="px-2 text-xl font-bold text-red-600" aria-label="Hapus warna">&times;</button>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <img x-show="c.preview || c.old_image" :src="c.preview || ('/storage/' + c.old_image)" class="h-16 w-20 rounded-lg bg-zinc-50 object-contain" alt="">
                        <label class="text-sm font-semibold">Foto unit warna ini
                            <input type="file" :name="'colors['+i+'][image]'" accept="image/*" :required="!c.old_image" @change="preview($event, c)" class="mt-1 block w-full text-sm">
                        </label>
                        <input type="hidden" :name="'colors['+i+'][old_image]'" :value="c.old_image || ''">
                    </div>

                    <div class="rounded-xl bg-zinc-50 p-3">
                        <input type="hidden" :name="'colors['+i+'][custom]'" :value="c.auto ? 0 : 1">
                        <label class="flex items-center gap-2 text-sm font-semibold">
                            <input type="checkbox" :checked="c.auto" @change="setAuto(c, $event.target.checked)">
                            Harga sama dengan tipe (otomatis)
                        </label>
                        <p x-show="c.auto" class="mt-1 text-xs text-zinc-500">
                            Mengikuti tipe: <span x-text="'Rp ' + Number(price || 0).toLocaleString('id-ID')"></span>
                            <span x-show="Number(discount) > 0" x-text="' / cash Rp ' + Math.max(Number(price || 0) - Number(discount || 0), 0).toLocaleString('id-ID')"></span>
                        </p>
                        <div x-show="!c.auto" x-cloak class="mt-3 grid gap-3 sm:grid-cols-2">
                            <label class="block text-xs font-semibold">Harga OTR warna ini (Rp)
                                <input type="number" min="0" :name="'colors['+i+'][price]'" x-model="c.price" :disabled="c.auto" class="mt-1 w-full rounded-xl border border-zinc-300 px-3 py-2 text-sm"></label>
                            <label class="block text-xs font-semibold">Diskon cash warna ini (Rp)
                                <input type="number" min="0" :name="'colors['+i+'][cash_discount]'" x-model="c.discount" :disabled="c.auto" placeholder="0 = tanpa diskon" class="mt-1 w-full rounded-xl border border-zinc-300 px-3 py-2 text-sm"></label>
                        </div>
                    </div>
                </div>
            </template>
            <p x-show="colors.length === 0" class="rounded-xl border border-dashed border-zinc-300 p-6 text-center text-sm text-zinc-500">Belum ada warna. Klik "+ Tambah warna" lalu upload foto unitnya.</p>
        </div>
    </div>

    <div class="flex gap-3">
        <button class="rounded-xl bg-honda px-8 py-3 text-sm font-bold text-white hover:bg-honda-dark">Simpan</button>
        <a href="{{ route('admin.motors.index') }}" class="rounded-xl border border-zinc-300 bg-white px-6 py-3 text-sm font-semibold">Batal</a>
    </div>
</form>
@endsection
