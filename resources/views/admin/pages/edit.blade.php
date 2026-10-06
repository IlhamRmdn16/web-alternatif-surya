@extends('admin.layout')
@section('title', 'Edit: '.$page->title)
@section('actions')<a href="{{ route('page.show', $page->slug) }}" target="_blank" class="text-sm font-bold text-honda">Lihat halaman</a>@endsection
@section('content')
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

    <div class="rounded-2xl bg-white p-6 shadow-sm" x-data="{ pv: null }">
        <h2 class="font-bold">Banner halaman</h2>
        <p class="mt-1 text-xs leading-relaxed text-zinc-500">
            Disarankan <strong>1240 x 520 px</strong> (JPG/PNG/WebP, minimal 1000 x 420 px, maks. 5 MB).
            Sistem otomatis memotong dari tengah &amp; mengompres menjadi WebP: versi desktop 1240x520 dan versi mobile rasio 5:4,
            jadi ringan untuk pengunjung. Letakkan objek penting di bagian tengah gambar.
            Judul halaman tampil di depan banner. Tanpa banner, judul tampil di atas latar gelap.
        </p>

        @error('banner')<p class="mt-3 text-sm font-semibold text-red-600">{{ $message }}</p>@enderror

        @if($page->banner)
            <p class="mt-4 text-xs font-semibold text-zinc-500">Banner saat ini (rasio 1240:520)</p>
            <img src="{{ asset('storage/'.$page->banner) }}" class="mt-1 aspect-[1240/520] w-full max-w-xl rounded-xl object-cover" alt="Banner saat ini">
            <label class="mt-3 flex items-center gap-2 text-sm font-semibold text-red-600"><input type="checkbox" name="remove_banner" value="1"> Hapus banner ini</label>
        @endif

        <template x-if="pv">
            <div>
                <p class="mt-4 text-xs font-semibold text-zinc-500">Pratinjau banner baru (hasil akhir dipotong dari tengah)</p>
                <img :src="pv" class="mt-1 aspect-[1240/520] w-full max-w-xl rounded-xl object-cover" alt="Pratinjau banner baru">
            </div>
        </template>

        <label class="mt-4 block text-sm font-semibold">{{ $page->banner ? 'Ganti banner' : 'Upload banner' }}
            <input type="file" name="banner" accept="image/jpeg,image/png,image/webp" class="mt-2 block w-full text-sm"
                   @change="pv = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"></label>
    </div>

    <div class="flex gap-3">
        <button class="rounded-xl bg-honda px-8 py-3 text-sm font-bold text-white hover:bg-honda-dark">Simpan</button>
        <a href="{{ route('admin.pages.index') }}" class="rounded-xl border border-zinc-300 bg-white px-6 py-3 text-sm font-semibold">Batal</a>
    </div>
</form>
@endsection
