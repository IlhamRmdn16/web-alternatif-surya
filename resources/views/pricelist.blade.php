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
<section class="mx-auto max-w-7xl px-4 pt-10"
         x-data="{ cat: 'all', q: {{ Js::from($qInit) }}, all: {{ Js::from($allHay) }},
                   get none() { return this.q.trim() !== '' && !this.all.some(h => h.includes(this.q.trim().toLowerCase())) } }">
    <h1 class="text-2xl font-extrabold text-zinc-900 md:text-4xl">Daftar Harga Motor Honda Garut</h1>
    <div class="mt-3 h-1 w-14 rounded bg-honda"></div>
    <p class="mt-4 text-sm leading-relaxed text-zinc-600 md:text-base">Harga OTR setiap tipe motor Honda di dealer kami, termasuk potongan untuk pembelian cash. Harga dapat berubah sewaktu-waktu, hubungi kami untuk konfirmasi harga dan stok terbaru.</p>

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
        <div x-show="(cat === 'all' || cat === '{{ $c->slug }}') && (q.trim() === '' || {{ Js::from($c->series->map($hay)->values()) }}.some(h => h.includes(q.trim().toLowerCase())))" class="mt-10">
            <h2 class="text-xl font-extrabold text-zinc-900">Motor Honda {{ $c->name }}</h2>
            <div class="mt-5 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach($c->series as $s)
                    <article x-show="q.trim() === '' || {{ Js::from($hay($s)) }}.includes(q.trim().toLowerCase())" class="flex flex-col overflow-hidden rounded-2xl border border-zinc-200 bg-white">
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
