@extends('layouts.frontend')

@section('title', 'Daftar Harga Motor Honda Garut Terbaru: Harga OTR & Diskon Cash | Dealer Motor Honda Garut')
@section('meta_description', 'Daftar harga motor Honda Garut terbaru semua seri dan tipe: Matic, Sport, EV, dan Cub. Cek harga OTR, diskon pembelian cash, dan konsultasi kredit di HondaGarut.id.')

@section('content')
@php
    // ?jenis=sport -> langsung menampilkan jenis itu (dari tombol "Lihat semua motor Sport" di beranda)
    $jenis = request('jenis');
    $catInit = is_string($jenis) && $categories->contains('slug', $jenis) ? $jenis : 'all';
@endphp
<section class="mx-auto max-w-7xl px-4 pt-10"
         x-data="{ cat: {{ Js::from($catInit) }} }">
    <h1 class="text-2xl font-extrabold text-zinc-900 md:text-4xl">Daftar Harga Motor Honda Garut</h1>
    <div class="mt-3 h-1 w-14 rounded bg-honda"></div>
    <p class="mt-4 text-sm leading-relaxed text-zinc-600 md:text-base">Harga OTR setiap tipe motor Honda di dealer kami, termasuk potongan untuk pembelian cash. Harga dapat berubah sewaktu-waktu, hubungi kami untuk konfirmasi harga dan stok terbaru.</p>

    {{-- Brosur & Daftar Harga PDF (diatur di Admin > Brosur & Daftar Harga) --}}
    @if($brochures->isNotEmpty())
        <div id="brosur" class="mt-8 rounded-3xl border border-red-100 bg-gradient-to-br from-red-50 to-white p-5 md:p-7">
            <h2 class="text-lg font-extrabold text-zinc-900 md:text-xl">Brosur & Daftar Harga (PDF)</h2>
            <p class="mt-1 text-sm text-zinc-600">Lihat atau unduh daftar harga dan simulasi kredit lengkap dalam format PDF.</p>

            <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($brochures as $b)
                    <div class="flex flex-col rounded-2xl border border-zinc-200 bg-white p-4 shadow-sm">
                        <div class="flex items-start gap-3">
                            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-50 text-honda" aria-hidden="true">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                            </span>
                            <div class="min-w-0">
                                <h3 class="text-sm font-bold leading-snug text-zinc-900 md:text-base">{{ $b->title }}</h3>
                                @if($b->description)<p class="mt-1 line-clamp-2 text-xs text-zinc-500">{{ $b->description }}</p>@endif
                                <p class="mt-1 text-[11px] text-zinc-400">PDF @if($b->size_label)&middot; {{ $b->size_label }} @endif&middot; Diperbarui {{ $b->updated_at->translatedFormat('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="mt-4 flex gap-2">
                            <a href="{{ route('brochures.show', $b) }}" target="_blank" rel="noopener" class="flex-1 rounded-xl bg-honda py-2.5 text-center text-sm font-bold text-white transition hover:bg-honda-dark">Lihat</a>
                            <a href="{{ route('brochures.download', $b) }}" class="flex-1 rounded-xl border border-zinc-300 py-2.5 text-center text-sm font-bold text-zinc-700 transition hover:border-honda hover:text-honda">Unduh</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="mt-8">
        <div class="no-scrollbar flex gap-2 overflow-x-auto">
            <button @click="cat = 'all'" :class="cat === 'all' ? 'bg-honda text-white' : 'bg-zinc-100 text-zinc-700'" class="whitespace-nowrap rounded-full px-5 py-2 text-sm font-bold">Semua</button>
            @foreach($categories as $c)
                <button @click="cat = '{{ $c->slug }}'" :class="cat === '{{ $c->slug }}' ? 'bg-honda text-white' : 'bg-zinc-100 text-zinc-700'" class="whitespace-nowrap rounded-full px-5 py-2 text-sm font-bold">{{ $c->name }}</button>
            @endforeach
        </div>
    </div>

    @forelse($categories as $c)
        <div x-show="cat === 'all' || cat === '{{ $c->slug }}'" class="mt-10">
            <h2 class="text-xl font-extrabold text-zinc-900">Motor Honda {{ $c->name }}</h2>
            {{-- Satu kartu per tipe (sama seperti beranda): label seri, tipe, titik warna, foto & harga tipe itu --}}
            <div class="mt-5 grid grid-cols-2 gap-3 md:grid-cols-3 md:gap-6 lg:grid-cols-4">
                @foreach($c->cards as $s)
                    <div class="flex [&>a]:w-full">@include('partials.series-card')</div>
                @endforeach
            </div>
        </div>
    @empty
        <p class="mt-10 rounded-2xl border border-dashed border-zinc-300 p-8 text-center text-sm text-zinc-500">Daftar harga belum tersedia.</p>
    @endforelse
</section>
@endsection