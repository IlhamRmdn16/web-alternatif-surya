@extends('admin.layout')
@section('title', $brochure->exists ? 'Edit Brosur' : 'Upload Brosur / Daftar Harga (PDF)')
@section('content')
<form method="POST" enctype="multipart/form-data" action="{{ $brochure->exists ? route('admin.brochures.update', $brochure) : route('admin.brochures.store') }}" class="max-w-2xl space-y-5 rounded-2xl bg-white p-6 shadow-sm">
    @csrf @if($brochure->exists) @method('PUT') @endif

    <label class="block text-sm font-semibold">Judul (mis. Daftar Harga Motor Honda Oktober 2026)
        <input type="text" name="title" value="{{ old('title', $brochure->title) }}" required class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5"></label>

    <label class="block text-sm font-semibold">Keterangan singkat (opsional, tampil di bawah judul)
        <input type="text" name="description" maxlength="300" value="{{ old('description', $brochure->description) }}" placeholder="mis. Harga OTR beberapa motor + simulasi kredit DP 1-5 juta" class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5"></label>

    <div>
        <label class="block text-sm font-semibold">File PDF {{ $brochure->exists ? '- kosongkan jika tidak diganti' : '' }}
            <input type="file" name="file" accept="application/pdf,.pdf" @if(! $brochure->exists) required @endif class="mt-2 block w-full text-sm"></label>
        <p class="mt-1 text-xs text-zinc-500">Format PDF, maksimal 20 MB. Mengganti file tidak mengubah alamat (link) brosur. Jika upload gagal untuk file besar, naikkan <code>upload_max_filesize</code> dan <code>post_max_size</code> di php.ini (mis. 25M) lalu restart server.</p>
        @if($brochure->exists)
            <p class="mt-2 text-sm">File saat ini: <a href="{{ route('brochures.show', $brochure) }}" target="_blank" class="font-bold text-honda underline">{{ $brochure->download_name }}</a> @if($brochure->size_label)<span class="text-zinc-500">({{ $brochure->size_label }})</span>@endif</p>
        @endif
    </div>

    <label class="block text-sm font-semibold">Urutan tampil (angka kecil tampil lebih dulu)
        <input type="number" min="0" name="sort" value="{{ old('sort', $brochure->sort ?? 0) }}" class="mt-1 w-32 rounded-xl border border-zinc-300 px-4 py-2.5"></label>

    <input type="hidden" name="is_active" value="0">
    <label class="flex items-center gap-2 text-sm font-semibold"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $brochure->is_active ?? true))> Tampilkan di halaman Daftar Harga</label>

    <div class="flex gap-3 pt-2">
        <button class="rounded-xl bg-honda px-6 py-2.5 text-sm font-bold text-white hover:bg-honda-dark">Simpan</button>
        <a href="{{ route('admin.brochures.index') }}" class="rounded-xl border border-zinc-300 px-6 py-2.5 text-sm font-semibold">Batal</a>
    </div>
</form>
@endsection
