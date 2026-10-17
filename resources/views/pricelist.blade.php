@extends('layouts.frontend')

@section('title', 'Daftar Harga Motor Honda Garut Terbaru: Harga OTR & Diskon Cash | Dealer Motor Honda Garut')
@section('meta_description', 'Daftar harga motor Honda Garut terbaru semua seri dan tipe: Matic, Sport, EV, dan Cub. Cek harga OTR, diskon pembelian cash, dan konsultasi kredit di HondaGarut.id.')

@section('content')
@php
    // teks pencarian per seri: nama + semua tipe (huruf kecil)
    $hay = fn ($s) => Str::lower($s->cheapest->display_name.' '.$s->types->pluck('variant')->implode(' '));
    $qInit = Str::limit(trim(is_string(request('q')) ? request('q') : ''), 60, '');
    $allHay = $categories->flatMap(fn ($c) => $c->series)->map($hay)->values();
@endphp
{{-- Pencarian per kata: semua kata harus ada, urutan bebas (mis. beat cbs iss cocok dengan Beat tipe CBS ISS) --}}
<section class="mx-auto max-w-7xl px-4 pt-10"
         x-data="{ cat: 'all', q: {{ Js::from($qInit) }}, all: {{ Js::from($allHay) }},
                   match(h) { const w = this.q.trim().toLowerCase().split(/\s+/).filter(Boolean); return w.every(x => h.includes(x)); },
                   get none() { return this.q.trim() !== '' && !this.all.some(h => this.match(h)) } }">
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

    <div class="mt-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div class="no-scrollbar flex gap-2 overflow-x-auto">
            <button @click="cat = 'all'" :class="cat === 'all' ? 'bg-honda text-white' : 'bg-zinc-100 text-zinc-700'" class="whitespace-nowrap rounded-full px-5 py-2 text-sm font-bold">Semua</button>
            @foreach($categories as $c)
                <button @click="cat = '{{ $c->slug }}'" :class="cat === '{{ $c->slug }}' ? 'bg-honda text-white' : 'bg-zinc-100 text-zinc-700'" class="whitespace-nowrap rounded-full px-5 py-2 text-sm font-bold">{{ $c->name }}</button>
            @endforeach
        </div>
        <input x-model="q" type="search" placeholder="Cari motor, mis. Beat" class="w-full rounded-full border border-zinc-300 px-5 py-2.5 text-sm md:w-72">
    </div>

    <p x-show="none" x-cloak class="mt-10 rounded-2xl border border-dashed border-zinc-300 p-8 text-center text-sm text-zinc-500">Motor yang Anda cari tidak ditemukan. Coba kata kunci lain atau hubungi kami via WhatsApp.</p>

    @forelse($categories as $c)
        <div x-show="(cat === 'all' || cat === '{{ $c->slug }}') && {{ Js::from($c->series->map($hay)->values()) }}.some(h => match(h))" class="mt-10">
            <h2 class="text-xl font-extrabold text-zinc-900">Motor Honda {{ $c->name }}</h2>
            <div class="mt-5 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach($c->series as $s)
                    <article x-show="match({{ Js::from($hay($s)) }})" class="flex flex-col overflow-hidden rounded-2xl border border-zinc-200 bg-white">
                        <a href="{{ $s->url }}" class="flex items-center gap-4 bg-zinc-50 p-4">
                            @if($s->image)<img src="{{ asset('storage/'.$s->image) }}" alt="{{ $s->cheapest->display_name }} Garut" loading="lazy" class="h-24 w-28 object-contain">@endif
                            <h3 class="text-lg font-extrabold text-zinc-900 hover:text-honda">{{ $s->name }} Series</h3>
                        </a>
                        <ul class="divide-y divide-zinc-100 px-4 text-sm">
                            @foreach($s->types as $t)
                                <li>
                                    <a href="{{ route('motor.show', $t) }}" class="flex items-center justify-between gap-3 py-3 hover:text-honda">
                                        <span class="font-medium text-zinc-700">{{ $t->variant }}</span>
                                        @php($o = $t->lowest_offer)
                                        <span class="text-right">@if($t->has_varied_prices)<span class="block text-[10px] text-zinc-400">mulai</span>@endif @include('partials.price', ['price' => $o['price'], 'disc' => $o['discount']])</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ $s->url }}" class="m-4 mt-auto rounded-xl bg-zinc-900 py-2.5 text-center text-sm font-semibold text-white hover:bg-honda">Lihat warna & konsultasi</a>
                    </article>
                @endforeach
            </div>
        </div>
    @empty
        <p class="mt-10 rounded-2xl border border-dashed border-zinc-300 p-8 text-center text-sm text-zinc-500">Daftar harga belum tersedia.</p>
    @endforelse
</section>
@endsection
