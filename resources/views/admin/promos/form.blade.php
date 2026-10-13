@extends('admin.layout')
@section('title', $promo->exists ? 'Edit Promo' : 'Tambah Promo')
@section('content')
<form method="POST" enctype="multipart/form-data" action="{{ $promo->exists ? route('admin.promos.update', $promo) : route('admin.promos.store') }}" class="max-w-4xl space-y-6">
    @csrf @if($promo->exists) @method('PUT') @endif

    <div class="space-y-5 rounded-2xl bg-white p-6 shadow-sm">
        <label class="block text-sm font-semibold">Judul promo
            <input type="text" name="title" value="{{ old('title', $promo->title) }}" required class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5"></label>
        <label class="block text-sm font-semibold">Gambar promo {{ $promo->exists ? '- kosongkan jika tidak diganti' : '' }}
            @if($promo->image)<img src="{{ asset('storage/'.$promo->image) }}" class="mt-2 h-24 rounded-lg object-cover" alt="">@endif
            <input type="file" name="image" accept="image/*" @if(! $promo->exists) required @endif class="mt-2 block w-full text-sm"></label>
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-sm">
        <label class="block text-sm font-semibold">Deskripsi / syarat & ketentuan
            <span class="block text-xs font-normal text-zinc-500">Ketik seperti di MS Word. Foto: klik ikon gambar lalu unggah, atau tempel (paste) langsung ke editor.</span>
            <textarea name="description" class="rich mt-2 w-full rounded-xl border border-zinc-300 px-4 py-3" rows="14">{{ old('description', \App\Support\Html::fromPlain($promo->description)) }}</textarea>
        </label>
        @include('admin.partials.editor')
    </div>

    <div class="flex flex-wrap items-center gap-6 rounded-2xl bg-white p-6 shadow-sm">
        <label class="flex items-center gap-2 text-sm font-semibold">Mulai
            <input type="date" name="start_date" value="{{ old('start_date', $promo->start_date?->format('Y-m-d')) }}" class="rounded-xl border border-zinc-300 px-3 py-1.5"></label>
        <label class="flex items-center gap-2 text-sm font-semibold">Berakhir
            <input type="date" name="end_date" value="{{ old('end_date', $promo->end_date?->format('Y-m-d')) }}" class="rounded-xl border border-zinc-300 px-3 py-1.5"></label>
        <input type="hidden" name="is_active" value="0">
        <label class="flex items-center gap-2 text-sm font-semibold"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $promo->is_active ?? true))> Tampilkan promo</label>
        <p class="w-full text-xs text-zinc-500">Kosongkan tanggal berakhir jika promo tanpa batas waktu.</p>
    </div>

    <div class="flex gap-3">
        <button class="rounded-xl bg-honda px-8 py-3 text-sm font-bold text-white hover:bg-honda-dark">Simpan</button>
        <a href="{{ route('admin.promos.index') }}" class="rounded-xl border border-zinc-300 bg-white px-6 py-3 text-sm font-semibold">Batal</a>
    </div>
</form>
@endsection
