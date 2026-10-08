<!DOCTYPE html>
<html lang="id">
@php
    $siteName   = $settings['site_name'] ?? 'Dealer Motor Honda Garut';
    $pageTitle  = trim($__env->yieldContent('title')) ?: $siteName.' | DealerMotorHondaGarut.id';
    $metaDesc   = trim($__env->yieldContent('meta_description')) ?: ($settings['seo_home_description'] ?? '');
    $canonical  = rtrim(config('app.url'), '/').'/'.ltrim(request()->path(), '/');
    $ogImage    = trim($__env->yieldContent('og_image')) ?: (! empty($settings['og_image']) ? asset('storage/'.$settings['og_image']) : asset('images/og-image.jpg'));
    $logo       = ! empty($settings['logo']) ? asset('storage/'.$settings['logo']) : null;
    // Alamat dealer (statis)
    $dealerAddress = 'Jl. Papandayan No.112, Kota Kulon, Kec. Garut Kota, Kabupaten Garut, Jawa Barat 44114';
    $mapUrl = ! empty($settings['map_url']) ? $settings['map_url'] : 'https://www.google.com/maps/search/?api=1&query='.urlencode($dealerAddress);
    // Menu utama (header). FAQ & Kebijakan Privasi hanya di footer.
    $nav = [
        ['url' => route('home'), 'label' => 'Beranda', 'active' => request()->routeIs('home')],
        ['url' => route('pricelist'), 'label' => 'Daftar Harga', 'active' => request()->routeIs('pricelist', 'motor.show')],
        ['url' => route('promos.index'), 'label' => 'Promo', 'active' => request()->routeIs('promos.*')],
        ['url' => route('news.index'), 'label' => 'Berita', 'active' => request()->routeIs('news.*')],
        ['url' => route('about'), 'label' => 'Tentang Kami', 'active' => request()->routeIs('about')],
        ['url' => route('contact'), 'label' => 'Kontak', 'active' => request()->routeIs('contact')],
    ];
    $footerNav = array_merge($nav, [
        ['url' => route('page.show', 'kebijakan-privasi'), 'label' => 'Kebijakan Privasi', 'active' => request()->is('kebijakan-privasi')],
    ]);
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $metaDesc }}">
    <meta name="keywords" content="dealer motor honda garut, dealermotorhondagarut, dealermotorhondagarut.id, honda garut, dealer honda garut, dealer resmi honda garut, harga motor honda garut, kredit motor honda garut, promo motor honda garut, simulasi kredit motor garut">
    <meta name="author" content="CV. Surya Wijaya Sejahtera">
    <meta name="robots" content="{{ app()->environment('production') ? 'index, follow, max-image-preview:large' : 'noindex, nofollow' }}">
    <meta name="geo.region" content="ID-JB">
    <meta name="geo.placename" content="Garut">
    <link rel="canonical" href="{{ rtrim($canonical, '/') ?: $canonical }}">

    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ $siteName }} - DealerMotorHondaGarut.id">
    <meta property="og:locale" content="id_ID">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $metaDesc }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $metaDesc }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <link rel="icon" href="{{ asset('images/icon.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'AutoDealer',
        'name' => $siteName.' - CV. Surya Wijaya Sejahtera',
        'alternateName' => ['DealerMotorHondaGarut.id', 'Surya Wijaya Garut'],
        'url' => rtrim(config('app.url'), '/'),
        'logo' => $logo,
        'image' => $ogImage,
        'foundingDate' => '1991',
        'telephone' => $settings['phone'] ?? null,
        'email' => $settings['email'] ?? null,
        'address' => ['@type' => 'PostalAddress', 'streetAddress' => 'Jl. Papandayan No.112, Kota Kulon, Kec. Garut Kota', 'addressLocality' => 'Garut', 'addressRegion' => 'Jawa Barat', 'postalCode' => '44114', 'addressCountry' => 'ID'],
        'hasMap' => $mapUrl,
        'areaServed' => 'Garut',
        'brand' => ['@type' => 'Brand', 'name' => 'Honda'],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@if(request()->routeIs('home') && isset($faqs) && $faqs->isNotEmpty())
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org', '@type' => 'FAQPage',
        'mainEntity' => $faqs->map(fn ($f) => ['@type' => 'Question', 'name' => $f->question, 'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags($f->answer)]])->values(),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
    @endif
    @stack('head')
</head>
<body class="bg-white font-sans text-zinc-800 antialiased">

<header class="sticky top-0 z-40 border-b border-zinc-200 bg-white/95 backdrop-blur" x-data="{ menu: false }">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 md:h-20">
        <a href="{{ route('home') }}" class="flex items-center gap-2" aria-label="{{ $siteName }}">
            @if($logo)
                <img src="{{ $logo }}" alt="Logo {{ $siteName }}" class="h-10 w-auto md:h-12">
            @else
                <span class="text-lg font-extrabold leading-tight text-zinc-900 md:text-xl">Dealer Motor <span class="text-honda">Honda Garut</span></span>
            @endif
        </a>

        <nav class="hidden items-center gap-1 lg:flex" aria-label="Menu utama">
            @foreach($nav as $item)
                <a href="{{ $item['url'] }}" class="rounded-lg px-3 py-2 text-sm font-semibold transition lg:px-3.5 {{ $item['active'] ? 'bg-honda text-white' : 'text-zinc-700 hover:bg-zinc-100' }}">{{ $item['label'] }}</a>
            @endforeach
        </nav>

        <form action="{{ route('pricelist') }}" method="GET" role="search" class="hidden w-64 xl:block">
            <label class="relative block">
                <span class="sr-only">Cari motor</span>
                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="search" name="q" value="{{ is_string(request('q')) ? request('q') : '' }}" placeholder="Cari motor, mis. Beat" class="w-full rounded-full border border-zinc-300 bg-white py-2 pl-9 pr-4 text-sm">
            </label>
        </form>

        <button class="rounded-lg p-2 text-zinc-700 lg:hidden" @click="menu = !menu" aria-label="Buka menu">
            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>
    {{-- Kolom cari untuk layar kecil/menengah: selalu terlihat, tanpa perlu scroll --}}
    <form action="{{ route('pricelist') }}" method="GET" role="search" class="border-t border-zinc-100 px-4 py-2 xl:hidden">
        <label class="relative block">
            <span class="sr-only">Cari motor</span>
            <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="search" name="q" value="{{ is_string(request('q')) ? request('q') : '' }}" placeholder="Cari motor, mis. Beat" class="w-full rounded-full border border-zinc-300 bg-zinc-50 py-2 pl-9 pr-4 text-sm">
        </label>
    </form>
    <nav x-show="menu" x-cloak class="border-t border-zinc-100 bg-white px-4 py-3 lg:hidden" aria-label="Menu mobile">
        @foreach($footerNav as $item)
            <a href="{{ $item['url'] }}" class="block rounded-lg px-3 py-3 text-sm font-semibold {{ $item['active'] ? 'bg-red-50 text-honda' : 'text-zinc-700' }}">{{ $item['label'] }}</a>
        @endforeach
    </nav>
</header>

<main>@yield('content')</main>

@php
    // Ikon sosial media (stroke 24x24). Tampil hanya jika link diisi di Admin > Pengaturan.
    $socials = [
        'instagram' => ['Instagram', '<rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>'],
        'facebook'  => ['Facebook', '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>'],
        'tiktok'    => ['TikTok', '<path d="M21 7.917v4.034a9.948 9.948 0 0 1-5-1.951v4.5a6.5 6.5 0 1 1-8-6.326v4.326a2.5 2.5 0 1 0 4 2v-11.5h4.083a6.005 6.005 0 0 0 4.917 4.917z"/>'],
        'youtube'   => ['YouTube', '<path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"/>'],
    ];
    $iconBtn = 'flex h-10 w-10 items-center justify-center rounded-full bg-zinc-800 text-zinc-300 transition hover:-translate-y-0.5 hover:bg-honda hover:text-white';
@endphp
<footer class="mt-20 bg-zinc-900 text-zinc-300">
    {{-- Ajakan konsultasi --}}
    <div class="border-b border-zinc-800">
        <div class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-5 px-4 py-9 md:flex-row md:items-center">
            <div>
                <p class="text-xl font-extrabold text-white md:text-2xl">Siap memiliki motor Honda impian Anda?</p>
                <p class="mt-1 text-sm text-zinc-400">Konsultasikan pembelian, cek stok, dan minta simulasi kredit bersama tim kami.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="button" x-data @click="$dispatch('open-wa')" class="inline-flex items-center gap-2 rounded-xl bg-green-500 px-6 py-3 text-sm font-bold text-white transition hover:bg-green-600">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    Chat WhatsApp
                </button>
                <a href="{{ route('pricelist') }}" class="inline-flex items-center rounded-xl border border-zinc-600 px-6 py-3 text-sm font-bold text-white transition hover:border-white">Lihat Pricelist</a>
            </div>
        </div>
    </div>

    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 md:grid-cols-2 lg:grid-cols-12 lg:gap-8">
        {{-- Brand --}}
        <div class="lg:col-span-4">
            <a href="{{ route('home') }}" class="text-xl font-extrabold leading-tight text-white">Dealer Motor <span class="text-red-500">Honda Garut</span></a>
            <p class="mt-1 text-xs font-semibold uppercase tracking-widest text-zinc-500">CV. Surya Wijaya Sejahtera</p>
            <p class="mt-4 max-w-sm text-sm leading-relaxed text-zinc-400">Dealer motor Honda di Garut sejak 1991. Daftar harga lengkap, promo terbaru, simulasi kredit, dan konsultasi pembelian motor Honda yang jujur dan transparan.</p>

            <p class="mt-6 text-xs font-bold uppercase tracking-widest text-zinc-500">Ikuti kami</p>
            <div class="mt-3 flex flex-wrap gap-3">
                @foreach($socials as $key => [$label, $paths])
                    @if(! empty($settings[$key]))
                        <a href="{{ $settings[$key] }}" target="_blank" rel="noopener" aria-label="{{ $label }}" title="{{ $label }}" class="{{ $iconBtn }}">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">{!! $paths !!}</svg>
                        </a>
                    @endif
                @endforeach
                <button type="button" x-data @click="$dispatch('open-wa')" aria-label="Chat sales counter via WhatsApp" title="Chat sales counter" class="{{ $iconBtn }}">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                </button>
            </div>
        </div>

        {{-- Menu --}}
        <div class="lg:col-span-2">
            <p class="text-sm font-bold uppercase tracking-widest text-white">Menu</p>
            <ul class="mt-4 space-y-3 text-sm">
                @foreach($footerNav as $item)
                    <li><a class="text-zinc-400 transition hover:text-white" href="{{ $item['url'] }}">{{ $item['label'] }}</a></li>
                @endforeach
            </ul>
        </div>

        {{-- Kontak --}}
        <div class="lg:col-span-3">
            <p class="text-sm font-bold uppercase tracking-widest text-white">Kunjungi Dealer</p>
            <ul class="mt-4 space-y-4 text-sm">
                <li class="flex gap-3">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <address class="not-italic leading-relaxed text-zinc-400">{{ $dealerAddress }}</address>
                </li>
                <li class="flex gap-3">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    <button type="button" x-data @click="$dispatch('open-wa')" class="text-left text-zinc-400 transition hover:text-white">Chat sales counter via WhatsApp</button>
                </li>
                @if(! empty($settings['phone']))
                    <li class="flex gap-3">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $settings['phone']) }}" class="text-zinc-400 transition hover:text-white">{{ $settings['phone'] }}</a>
                    </li>
                @endif
                @if(! empty($settings['email']))
                    <li class="flex gap-3">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <a href="mailto:{{ $settings['email'] }}" class="break-all text-zinc-400 transition hover:text-white">{{ $settings['email'] }}</a>
                    </li>
                @endif
            </ul>
            <a href="{{ $mapUrl }}" target="_blank" rel="noopener" class="mt-5 inline-flex items-center gap-2 rounded-lg border border-zinc-700 px-4 py-2 text-xs font-bold text-white transition hover:border-honda hover:bg-honda">
                Lihat di Google Maps
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>
        </div>

        {{-- Jam operasional --}}
        <div class="lg:col-span-3">
            <p class="text-sm font-bold uppercase tracking-widest text-white">Jam Operasional</p>
            @if(! empty($settings['hours']))
                <ul class="mt-4 space-y-3 text-sm">
                    @foreach(preg_split('/\r\n|\r|\n/', trim($settings['hours'])) as $line)
                        @continue(trim($line) === '')
                        <li class="flex items-start gap-3 border-b border-zinc-800 pb-3 last:border-0">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            @if(preg_match('/^(.*?):\s*(\d.*)$/u', trim($line), $m))
                                <span class="flex flex-1 flex-wrap justify-between gap-x-3"><span class="text-zinc-400">{{ $m[1] }}</span><span class="font-semibold text-white">{{ $m[2] }}</span></span>
                            @else
                                <span class="text-zinc-400">{{ trim($line) }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="mt-4 text-sm text-zinc-400">Hubungi kami via WhatsApp untuk informasi jam operasional.</p>
            @endif
        </div>
    </div>

    <div class="border-t border-zinc-800">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-2 px-4 py-5 text-center text-xs text-zinc-500 md:flex-row md:text-left">
            <p>&copy; {{ date('Y') }} DealerMotorHondaGarut.id - CV. Surya Wijaya Sejahtera. Seluruh hak cipta dilindungi.</p>
            <p>Harga dapat berubah sewaktu-waktu. Konfirmasi harga dan stok ke sales kami. <a href="{{ route('page.show', 'kebijakan-privasi') }}" class="underline underline-offset-2 hover:text-white">Kebijakan Privasi</a> &middot; <button type="button" x-data @click="$dispatch('open-cookie')" class="underline underline-offset-2 hover:text-white">Pengaturan Cookie</button></p>
        </div>
    </div>
</footer>

{{-- Tombol WhatsApp melayang (semua halaman): pilih sales counter -> isi nama & nomor -> WhatsApp --}}
<div x-data="waLead('{{ route('prospect.wa') }}', {{ Js::from($salesList) }})" @keydown.escape.window="open = false" @open-wa.window="openFor($event.detail)">
    <button @click="openFor()" class="fixed bottom-5 right-5 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-green-500 text-white shadow-lg transition hover:scale-110 hover:bg-green-600 md:bottom-8 md:right-8 md:h-16 md:w-16" aria-label="Chat via WhatsApp">
        <svg class="h-8 w-8 md:h-9 md:w-9" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
    </button>
    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-end justify-center overflow-y-auto bg-black/50 p-4 sm:items-center">
        <form @submit.prevent="submit" @click.outside="open = false" class="my-auto w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <h2 class="text-lg font-bold text-zinc-900" x-text="callCenter ? 'Hubungi Call Center' : 'Hubungi Sales Counter'">Hubungi Sales Counter</h2>
            <p class="mt-1 text-sm text-zinc-500" x-text="callCenter ? 'Isi nama dan nomor WhatsApp Anda, lalu kami arahkan ke WhatsApp call center.' : 'Pilih sales counter, isi nama dan nomor WhatsApp Anda, lalu kami arahkan ke WhatsApp.'">Pilih sales counter, isi nama dan nomor WhatsApp Anda, lalu kami arahkan ke WhatsApp.</p>

            <div x-show="!callCenter" x-cloak>@include('partials.sales-picker')</div>

            <label class="mt-4 block text-sm font-semibold">Nama
                <input x-model="name" type="text" maxlength="100" class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5 focus:border-honda focus:ring-honda">
            </label>
            <label class="mt-3 block text-sm font-semibold">Nomor WhatsApp Anda
                <input x-model="phone" type="tel" inputmode="tel" maxlength="20" placeholder="mis. 08123456789" class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5 focus:border-honda focus:ring-honda">
            </label>
            <p class="mt-3 text-xs text-zinc-500">Dengan melanjutkan, Anda menyetujui <a href="{{ route('page.show', 'kebijakan-privasi') }}" target="_blank" class="font-semibold text-honda underline">Kebijakan Privasi</a> kami.</p>
            <p x-show="error" x-text="error" class="mt-3 text-sm text-red-600"></p>
            <div class="mt-5 flex gap-3">
                <button type="button" @click="open = false" class="flex-1 rounded-xl border border-zinc-300 py-2.5 text-sm font-semibold">Batal</button>
                <button type="submit" :disabled="loading" class="flex-1 rounded-xl bg-green-500 py-2.5 text-sm font-semibold text-white hover:bg-green-600 disabled:opacity-60">
                    <span x-show="!loading">Lanjut ke WhatsApp</span><span x-show="loading">Memproses...</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Banner & pengaturan cookie: dipasang di semua halaman agar pilihan pengunjung berlaku di seluruh website --}}
@include('partials.cookie-notice')

@stack('scripts')
</body>
</html>
