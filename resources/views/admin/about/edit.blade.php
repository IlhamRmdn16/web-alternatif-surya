@extends('admin.layout')
@section('title', 'Tentang Kami')
@section('actions')<a href="{{ route('about') }}" target="_blank" class="text-sm font-bold text-honda">Lihat halaman</a>@endsection
@section('content')
<form method="POST" enctype="multipart/form-data" action="{{ route('admin.about.update') }}" class="max-w-4xl space-y-6">
    @csrf @method('PUT')

    <p class="rounded-xl bg-amber-50 p-4 text-sm text-amber-900">Isi awal adalah <b>contoh</b>. Mohon sesuaikan terutama <b>Visi, Misi</b>, dan angka pada kartu dengan data resmi perusahaan.</p>

    {{-- HERO --}}
    <div class="space-y-4 rounded-2xl bg-white p-6 shadow-sm">
        <h2 class="font-bold">1. Bagian atas (hero)</h2>
        @include('admin.about.field', ['path' => 'hero.eyebrow', 'label' => 'Teks kecil di atas judul'])
        @include('admin.about.field', ['path' => 'hero.title', 'label' => 'Judul utama'])
        @include('admin.about.field', ['path' => 'hero.highlight', 'label' => 'Kata dalam judul yang diberi warna merah (salin persis dari judul; boleh kosong)'])
        @include('admin.about.field', ['path' => 'hero.text', 'label' => 'Paragraf pengantar', 'type' => 'textarea', 'rows' => 3])
        <div class="grid gap-4 sm:grid-cols-2">
            @include('admin.about.field', ['path' => 'hero.btn1', 'label' => 'Tombol 1 (menggulung ke bagian Cerita)'])
            @include('admin.about.field', ['path' => 'hero.btn2', 'label' => 'Tombol 2 (menggulung ke bagian Layanan)'])
        </div>
        <div>
            <p class="text-sm font-semibold">Foto hero (di sebelah kanan; foto dealer/showroom)</p>
            @if(! empty($about['hero']['photo']))
                <img src="{{ asset('storage/'.$about['hero']['photo']) }}" class="mt-2 h-28 rounded-xl object-cover" alt="">
                <label class="mt-2 flex items-center gap-2 text-sm font-semibold text-red-600"><input type="checkbox" name="remove_hero_photo" value="1"> Hapus foto ini</label>
            @endif
            <input type="file" name="hero_photo" accept="image/*" class="mt-2 block w-full text-sm">
            <p class="mt-1 text-xs text-zinc-500">Disarankan rasio 4:3 (mis. 1200x900 px), boleh webp. Tanpa foto, teks tampil sendiri.</p>
        </div>
    </div>

    {{-- ANGKA --}}
    <div class="space-y-4 rounded-2xl bg-white p-6 shadow-sm">
        <h2 class="font-bold">2. Tiga kartu angka</h2>
        @foreach([0, 1, 2] as $i)
            <div class="grid gap-3 rounded-xl border border-zinc-200 p-4 sm:grid-cols-3">
                @include('admin.about.field', ['path' => "stats.$i.value", 'label' => 'Kartu '.($i + 1).': angka/teks besar'])
                @include('admin.about.field', ['path' => "stats.$i.label", 'label' => 'Judul'])
                @include('admin.about.field', ['path' => "stats.$i.desc", 'label' => 'Keterangan'])
            </div>
        @endforeach
    </div>

    {{-- CERITA --}}
    <div class="space-y-4 rounded-2xl bg-white p-6 shadow-sm">
        <h2 class="font-bold">3. Cerita perusahaan</h2>
        @include('admin.about.field', ['path' => 'story.eyebrow', 'label' => 'Teks kecil di atas judul'])
        @include('admin.about.field', ['path' => 'story.title', 'label' => 'Judul'])
        @include('admin.about.field', ['path' => 'story.text', 'label' => 'Isi cerita (pisahkan paragraf dengan satu baris kosong)', 'type' => 'textarea', 'rows' => 12])
        <div>
            <p class="text-sm font-semibold">Foto cerita (di sebelah kiri tulisan)</p>
            @if(! empty($about['story']['photo']))
                <img src="{{ asset('storage/'.$about['story']['photo']) }}" class="mt-2 h-28 rounded-xl object-cover" alt="">
                <label class="mt-2 flex items-center gap-2 text-sm font-semibold text-red-600"><input type="checkbox" name="remove_story_photo" value="1"> Hapus foto ini</label>
            @endif
            <input type="file" name="story_photo" accept="image/*" class="mt-2 block w-full text-sm">
            <p class="mt-1 text-xs text-zinc-500">Disarankan rasio 4:3. Tanpa foto, tulisan tampil selebar penuh.</p>
        </div>
    </div>

    {{-- LAYANAN --}}
    <div class="space-y-4 rounded-2xl bg-white p-6 shadow-sm">
        <h2 class="font-bold">4. Layanan (tujuan tombol "Jelajahi Layanan")</h2>
        @include('admin.about.field', ['path' => 'services.eyebrow', 'label' => 'Teks kecil di atas judul'])
        @include('admin.about.field', ['path' => 'services.title', 'label' => 'Judul'])
        @include('admin.about.field', ['path' => 'services.intro', 'label' => 'Paragraf pengantar', 'type' => 'textarea', 'rows' => 2])
        @foreach([0, 1, 2] as $i)
            <div class="space-y-3 rounded-xl border border-zinc-200 p-4">
                <div class="grid gap-3 sm:grid-cols-3">
                    @include('admin.about.field', ['path' => "services.items.$i.code", 'label' => 'Kartu '.($i + 1).': kode'])
                    @include('admin.about.field', ['path' => "services.items.$i.title", 'label' => 'Judul'])
                    @include('admin.about.field', ['path' => "services.items.$i.chip", 'label' => 'Label kecil'])
                </div>
                @include('admin.about.field', ['path' => "services.items.$i.text", 'label' => 'Deskripsi', 'type' => 'textarea', 'rows' => 2])
            </div>
        @endforeach
    </div>

    {{-- VISI MISI --}}
    <div class="space-y-4 rounded-2xl bg-white p-6 shadow-sm">
        <h2 class="font-bold">5. Visi dan misi</h2>
        @include('admin.about.field', ['path' => 'vision.eyebrow', 'label' => 'Teks kecil di atas judul'])
        @include('admin.about.field', ['path' => 'vision.title', 'label' => 'Judul'])
        @include('admin.about.field', ['path' => 'vision.vision_quote', 'label' => 'Visi (kalimat utama)', 'type' => 'textarea', 'rows' => 2])
        @include('admin.about.field', ['path' => 'vision.vision_text', 'label' => 'Penjelasan visi', 'type' => 'textarea', 'rows' => 2])
        @include('admin.about.field', ['path' => 'vision.missions', 'label' => 'Misi (satu misi per baris)', 'type' => 'textarea', 'rows' => 6])
    </div>

    {{-- LOKASI --}}
    <div class="space-y-4 rounded-2xl bg-white p-6 shadow-sm">
        <h2 class="font-bold">6. Lokasi</h2>
        @include('admin.about.field', ['path' => 'places.eyebrow', 'label' => 'Teks kecil di atas judul'])
        @include('admin.about.field', ['path' => 'places.title', 'label' => 'Judul'])
        @include('admin.about.field', ['path' => 'places.intro', 'label' => 'Paragraf pengantar', 'type' => 'textarea', 'rows' => 2])
        @include('admin.about.field', ['path' => 'places.items', 'label' => 'Daftar lokasi (satu lokasi per baris, format: Nama lokasi | Keterangan). Kosongkan untuk menyembunyikan bagian ini.', 'type' => 'textarea', 'rows' => 5])
    </div>

    {{-- SEO --}}
    <div class="space-y-4 rounded-2xl bg-white p-6 shadow-sm">
        <h2 class="font-bold">7. SEO</h2>
        @include('admin.about.field', ['path' => 'seo.title', 'label' => 'SEO title (maks. ±60 karakter)'])
        @include('admin.about.field', ['path' => 'seo.description', 'label' => 'SEO description (maks. ±160 karakter)', 'type' => 'textarea', 'rows' => 2])
    </div>

    <div class="flex gap-3">
        <button class="rounded-xl bg-honda px-8 py-3 text-sm font-bold text-white hover:bg-honda-dark">Simpan</button>
    </div>
</form>
@endsection
