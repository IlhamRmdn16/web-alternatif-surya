@extends('admin.layout')
@section('title', 'Edit: '.$page->title)
@section('actions')<a href="{{ route('page.show', $page->slug) }}" target="_blank" class="text-sm font-bold text-honda">Lihat halaman</a>@endsection
@section('content')
@php($current = collect($page->images ?? [])->filter()->values())
<form method="POST" enctype="multipart/form-data" action="{{ route('admin.pages.update', $page) }}" class="max-w-4xl space-y-6">
    @csrf @method('PUT')

    <div class="space-y-5 rounded-2xl bg-white p-6 shadow-sm">
        <label class="block text-sm font-semibold">Judul halaman (tampil sebagai judul utama)
            <input type="text" name="title" value="{{ old('title', $page->title) }}" required class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5"></label>
        <label class="block text-sm font-semibold">SEO title (opsional, maks. ±60 karakter; kosong = pakai judul)
            <input type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}" class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5"></label>
        <label class="block text-sm font-semibold">SEO description (maks. ±160 karakter)
            <textarea name="meta_description" rows="2" class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5">{{ old('meta_description', $page->meta_description) }}</textarea></label>
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-sm">
        <label class="block text-sm font-semibold">Isi halaman
            <span class="block text-xs font-normal text-zinc-500">Ketik seperti di MS Word. Foto di dalam tulisan: klik ikon gambar atau tempel langsung.</span>
            <textarea name="content" class="rich mt-2 w-full rounded-xl border border-zinc-300 px-4 py-3" rows="18">{{ old('content', $page->content) }}</textarea>
        </label>
        @include('admin.partials.editor')
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-sm">
        <h2 class="font-bold">Foto di sebelah kanan tulisan</h2>
        <p class="mt-1 text-xs text-zinc-500">Maksimal 6 foto. Di layar lebar foto tampil di samping kanan tulisan; di HP tampil di bawah tulisan.</p>

        @if($current->isNotEmpty())
            <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3">
                @foreach($current as $img)
                    <label class="block rounded-xl border border-zinc-200 p-2 text-xs">
                        <img src="{{ asset('storage/'.$img) }}" class="h-28 w-full rounded-lg object-cover" alt="">
                        <span class="mt-2 flex items-center gap-2 font-semibold text-red-600"><input type="checkbox" name="remove_images[]" value="{{ $img }}"> Hapus foto ini</span>
                    </label>
                @endforeach
            </div>
        @endif

        <label class="mt-4 block text-sm font-semibold">Tambah foto (bisa pilih beberapa sekaligus)
            <input type="file" name="new_images[]" accept="image/*" multiple class="mt-2 block w-full text-sm"></label>
    </div>

    <div class="flex gap-3">
        <button class="rounded-xl bg-honda px-8 py-3 text-sm font-bold text-white hover:bg-honda-dark">Simpan</button>
        <a href="{{ route('admin.pages.index') }}" class="rounded-xl border border-zinc-300 bg-white px-6 py-3 text-sm font-semibold">Batal</a>
    </div>
</form>
@endsection
