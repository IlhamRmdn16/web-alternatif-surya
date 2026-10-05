@extends('layouts.frontend')

@section('title', $settings['seo_home_title'] ?? 'Dealer Motor Honda Garut Resmi | DealerMotorHondaGarut.id')
@section('meta_description', $settings['seo_home_description'] ?? '')

@section('content')

{{-- Banner slider --}}
@if($banners->isNotEmpty())
<section class="swiper hero-swiper relative w-full bg-zinc-100" aria-label="Banner promo">
    <div class="swiper-wrapper">
        @foreach($banners as $b)
            <div class="swiper-slide">
                @if($b->link)<a href="{{ $b->link }}">@endif
                    <img src="{{ asset('storage/'.$b->image) }}" alt="{{ $b->title ?: 'Promo Motor Honda Garut' }}" class="aspect-[16/9] w-full object-cover md:aspect-[8/3]" @if(! $loop->first) loading="lazy" @endif>
                @if($b->link)</a>@endif
            </div>
        @endforeach
    </div>
    <div class="swiper-button-prev !hidden md:!flex"></div>
    <div class="swiper-button-next !hidden md:!flex"></div>
    <div class="swiper-pagination"></div>
</section>
@endif

{{-- Pengantar --}}
<section class="mx-auto max-w-7xl px-4 pt-10 md:pt-14">
    <h1 class="text-2xl font-extrabold tracking-tight text-zinc-900 md:text-4xl">{{ $settings['home_h1'] ?? 'Dealer Motor Honda Garut Resmi' }}</h1>
    <div class="mt-3 h-1 w-14 rounded bg-honda"></div>
    <p class="mt-4 max-w-3xl text-sm leading-relaxed text-zinc-600 md:text-base">{{ $settings['home_intro'] ?? '' }}</p>
</section>

{{-- Katalog per jenis --}}
@php($tabs = $categories->filter(fn ($c) => $c->homeSeries->isNotEmpty())->values())
@if($tabs->isNotEmpty())
<section class="mx-auto max-w-7xl px-4 pt-12" x-data="{ tab: '{{ $tabs->first()->slug }}' }">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <h2 class="text-xl font-extrabold text-zinc-900 md:text-2xl">Motor Honda Pilihan</h2>
            <p class="mt-1 text-sm text-zinc-500">Pilih jenis motor, lalu lihat tipe, harga OTR, dan diskon pembelian cash.</p>
        </div>
        <a href="{{ route('pricelist') }}" class="text-sm font-bold text-honda hover:text-zinc-900">Lihat semua daftar harga</a>
    </div>

    <div class="no-scrollbar mt-6 flex gap-2 overflow-x-auto border-b border-zinc-200" role="tablist">
        @foreach($tabs as $c)
            <button role="tab" @click="tab = '{{ $c->slug }}'" :aria-selected="tab === '{{ $c->slug }}'"
                    :class="tab === '{{ $c->slug }}' ? 'border-honda text-honda' : 'border-transparent text-zinc-500 hover:text-zinc-800'"
                    class="-mb-px whitespace-nowrap border-b-2 px-5 py-3 text-sm font-bold transition">{{ $c->name }}</button>
        @endforeach
    </div>

    @foreach($tabs as $c)
        <div x-show="tab === '{{ $c->slug }}'" @if(! $loop->first) x-cloak @endif role="tabpanel">
            <div class="mt-6 grid grid-cols-2 gap-3 md:grid-cols-4 md:gap-6">
                @foreach($c->homeSeries as $s) @include('partials.series-card') @endforeach
            </div>
            <div class="mt-8 text-center">
                <a href="{{ route('pricelist') }}" class="inline-block rounded-xl bg-honda px-8 py-3 text-sm font-bold text-white transition hover:bg-honda-dark">Lihat semua motor {{ $c->name }}</a>
            </div>
        </div>
    @endforeach
</section>
@endif

{{-- Promo --}}
<section class="mx-auto max-w-7xl px-4 pt-16">
    <div class="flex items-end justify-between gap-3">
        <div>
            <h2 class="text-xl font-extrabold text-zinc-900 md:text-2xl">Promo Motor Honda Garut</h2>
            <p class="mt-1 text-sm text-zinc-500">Penawaran terbatas dari dealer.</p>
        </div>
        @if($promos->isNotEmpty())<a href="{{ route('promos.index') }}" class="text-sm font-bold text-honda hover:text-zinc-900">Semua promo</a>@endif
    </div>
    @if($promos->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-zinc-300 p-8 text-center text-sm text-zinc-500">Belum ada promo aktif. Hubungi kami via WhatsApp untuk info penawaran terbaru.</div>
    @else
        <div class="mt-6 grid gap-4 md:grid-cols-3 md:gap-6">
            @foreach($promos as $p) @include('partials.promo-card') @endforeach
        </div>
    @endif
</section>

{{-- Berita terbaru --}}
@if($posts->isNotEmpty())
<section class="mx-auto max-w-7xl px-4 pt-16">
    <div class="flex items-end justify-between gap-3">
        <div>
            <h2 class="text-xl font-extrabold text-zinc-900 md:text-2xl">Berita & Tips Terbaru</h2>
            <p class="mt-1 text-sm text-zinc-500">Informasi dan panduan seputar motor Honda.</p>
        </div>
        <a href="{{ route('news.index') }}" class="text-sm font-bold text-honda hover:text-zinc-900">Semua berita</a>
    </div>
    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 lg:gap-6">
        @foreach($posts as $post) @include('partials.post-card') @endforeach
    </div>
</section>
@endif

{{-- FAQ --}}
@if($faqs->isNotEmpty())
<section class="mx-auto max-w-7xl px-4 pt-16">
    <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
        <div>
            <h2 class="text-xl font-extrabold text-zinc-900 md:text-2xl">Pertanyaan yang sering diajukan</h2>
            <div class="mt-6 space-y-3" x-data="{ open: null }">
                @foreach($faqs as $i => $f)
                    <div class="rounded-xl border border-zinc-200">
                        <button class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left text-sm font-bold text-zinc-900 md:text-base" @click="open = open === {{ $i }} ? null : {{ $i }}" :aria-expanded="open === {{ $i }}">
                            {{ $f->question }}
                            <span class="text-xl text-honda" x-text="open === {{ $i }} ? '−' : '+'"></span>
                        </button>
                        <div x-show="open === {{ $i }}" x-cloak class="whitespace-pre-line px-5 pb-5 text-sm leading-relaxed text-zinc-600">{{ $f->answer }}</div>
                    </div>
                @endforeach
            </div>
            <p class="mt-5"><a href="{{ route('faq') }}" class="text-sm font-bold text-honda hover:text-zinc-900">Lihat semua FAQ</a></p>
        </div>

        {{-- Gambar statis (hanya desktop). Letakkan file di public/images/faq.webp --}}
        <div class="hidden lg:block">
            <img src="{{ asset('images/faq.webp') }}" alt="Ilustrasi tanya jawab seputar beli motor Honda di Garut" loading="lazy" onerror="this.parentElement.remove()" class="mx-auto h-auto w-full max-w-md">
        </div>
    </div>
</section>
@endif

@endsection
