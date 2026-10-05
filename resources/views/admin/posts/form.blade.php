@extends('admin.layout')
@section('title', $post->exists ? 'Edit Berita' : 'Tulis Berita')
@section('content')
<form method="POST" enctype="multipart/form-data" action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}" class="max-w-4xl space-y-6">
    @csrf @if($post->exists) @method('PUT') @endif

    <div class="space-y-5 rounded-2xl bg-white p-6 shadow-sm">
        <label class="block text-sm font-semibold">Judul berita
            <input type="text" name="title" value="{{ old('title', $post->title) }}" required class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5"></label>
        <label class="block text-sm font-semibold">Ringkasan (tampil di daftar berita & hasil Google, maks. ±160 karakter; kosong = diambil dari isi)
            <textarea name="excerpt" rows="2" maxlength="300" class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5">{{ old('excerpt', $post->excerpt) }}</textarea></label>
        <label class="block text-sm font-semibold">Foto sampul (disarankan rasio 16:10) {{ $post->exists ? '- kosongkan jika tidak diganti' : '' }}
            @if($post->cover)<img src="{{ asset('storage/'.$post->cover) }}" class="mt-2 h-24 rounded-lg object-cover" alt="">@endif
            <input type="file" name="cover" accept="image/*" class="mt-2 block w-full text-sm"></label>
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-sm">
        <label class="block text-sm font-semibold">Isi berita
            <span class="block text-xs font-normal text-zinc-500">Ketik seperti di MS Word. Foto: klik ikon gambar lalu unggah, atau tempel (paste) langsung ke editor.</span>
            <textarea name="content" class="rich mt-2 w-full rounded-xl border border-zinc-300 px-4 py-3" rows="18">{{ old('content', $post->content) }}</textarea>
        </label>
        @include('admin.partials.editor')
    </div>

    <div class="flex flex-wrap items-center gap-6 rounded-2xl bg-white p-6 shadow-sm">
        <input type="hidden" name="is_published" value="0">
        <label class="flex items-center gap-2 text-sm font-semibold"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $post->is_published ?? true))> Terbitkan</label>
        <label class="flex items-center gap-2 text-sm font-semibold">Tanggal terbit
            <input type="datetime-local" name="published_at" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}" class="rounded-xl border border-zinc-300 px-3 py-1.5">
        </label>
        <p class="w-full text-xs text-zinc-500">Kosongkan tanggal untuk terbit sekarang. Isi tanggal di masa depan untuk menjadwalkan. Hilangkan centang "Terbitkan" untuk menyimpan sebagai draft.</p>
    </div>

    <div class="flex gap-3">
        <button class="rounded-xl bg-honda px-8 py-3 text-sm font-bold text-white hover:bg-honda-dark">Simpan</button>
        <a href="{{ route('admin.posts.index') }}" class="rounded-xl border border-zinc-300 bg-white px-6 py-3 text-sm font-semibold">Batal</a>
    </div>
</form>
@endsection
