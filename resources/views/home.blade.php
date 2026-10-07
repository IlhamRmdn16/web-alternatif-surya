@extends('layouts.frontend')

@section('title', $settings['seo_home_title'] ?? 'Dealer Motor Honda Garut Resmi | DealerMotorHondaGarut.id')
@section('meta_description', $settings['seo_home_description'] ?? '')

@section('content')

{{-- Hero: banner bergerak otomatis (latar), teks statis di depannya.
     Ukuran: mobile rasio 5:4, desktop 1240x520 (maks. lebar 1240px, di tengah).
     Ringan: slide pertama dimuat prioritas, sisanya lazy; versi mobile (WebP 750px) dipakai bila tersedia.
     Jelas: gradasi gelap hanya di sisi tulisan (bawah di mobile, kiri di desktop). --}}
<div class="md:px-4 md:pt-6">
<section class="relative isolate mx-auto aspect-[5/4] w-full overflow-hidden bg-zinc-900 md:aspect-[1240/520] md:max-w-[1240px] md:rounded-2xl" aria-label="Banner utama">
    {{-- Pembungkus absolut: class .swiper (CSS Swiper) memaksa position:relative sehingga
         tidak boleh dipasang bersamaan dengan "absolute" pada elemen yang sama --}}
    <div class="absolute inset-0 z-0">
        @if($banners->isNotEmpty())
            <div class="swiper hero-swiper h-full w-full">
                <div class="swiper-wrapper">
                    @foreach($banners as $b)
                        <div class="swiper-slide !h-full">
                            @if($b->link)<a href="{{ $b->link }}" class="block h-full w-full">@endif
                                <picture>
                                    @if($m = \App\Support\BannerImage::mobileUrl($b->image))
                                        <source media="(max-width: 767px)" srcset="{{ $m }}">
                                    @endif
                                    <img src="{{ asset('storage/'.$b->image) }}" alt="{{ $b->title ?: 'Promo Motor Honda Garut' }}"
                                         class="h-full w-full object-cover" width="1240" height="520" decoding="async"
                                         @if($loop->first) fetchpriority="high" @else loading="lazy" @endif>
                                </picture>
                            @if($b->link)</a>@endif
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>
        @else
            <div class="h-full w-full bg-gradient-to-br from-zinc-900 via-zinc-800 to-red-900"></div>
        @endif
    </div>

    {{-- Gradasi tipis: banner tetap jelas, hanya area tulisan yang digelapkan --}}
    <div class="pointer-events-none absolute inset-0 z-10 bg-gradient-to-t from-black/80 via-black/15 to-transparent md:bg-gradient-to-r md:from-black/70 md:via-black/25 md:to-transparent"></div>

    {{-- Teks statis (tidak ikut bergerak), tepat di depan banner --}}
    <div class="pointer-events-none absolute inset-0 z-20 flex items-end px-5 pb-9 md:items-center md:px-12 md:pb-0">
        <div class="max-w-full md:max-w-md lg:max-w-xl">
            <h1 class="text-lg font-extrabold leading-tight tracking-tight text-white drop-shadow sm:text-2xl md:text-4xl">{{ $settings['home_h1'] ?? 'Dealer Motor Honda Garut Resmi' }}</h1>
            <div class="mt-2 h-1 w-10 rounded bg-honda md:mt-3 md:w-16"></div>
            <p class="mt-2 line-clamp-3 text-xs leading-relaxed text-white/90 drop-shadow sm:text-sm md:mt-3 md:line-clamp-none md:text-base">{{ $settings['home_intro'] ?? '' }}</p>
        </div>
    </div>
</section>
</div>

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
        </div>

        {{-- Gambar statis (hanya desktop). Letakkan file di public/images/faq.webp --}}
        <div class="hidden lg:block">
            <img src="{{ asset('images/faq.webp') }}" alt="Ilustrasi tanya jawab seputar beli motor Honda di Garut" loading="lazy" onerror="this.parentElement.remove()" class="mx-auto h-auto w-full max-w-md">
        </div>
    </div>
</section>
@endif

@endsection
