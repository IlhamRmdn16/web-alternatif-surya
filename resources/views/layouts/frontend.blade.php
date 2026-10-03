<!DOCTYPE html>
<html lang="id">
@php
    $siteName   = $settings['site_name'] ?? 'Dealer Motor Honda Garut';
    $pageTitle  = trim($__env->yieldContent('title')) ?: $siteName.' | DealerMotorHondaGarut.id';
    $metaDesc   = trim($__env->yieldContent('meta_description')) ?: ($settings['seo_home_description'] ?? '');
    $canonical  = rtrim(config('app.url'), '/').'/'.ltrim(request()->path(), '/');
    $ogImage    = trim($__env->yieldContent('og_image')) ?: (! empty($settings['og_image']) ? asset('storage/'.$settings['og_image']) : asset('images/og-image.jpg'));
    $logo       = ! empty($settings['logo']) ? asset('storage/'.$settings['logo']) : null;
    $nav = [
        ['home', 'Beranda', 'home'],
        ['pricelist', 'Pricelist', 'pricelist'],
        ['promos.index', 'Promo', 'promos.*'],
        ['faq', 'FAQ', 'faq'],
    ];
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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
        'telephone' => $settings['phone'] ?? ('+'.$waNumber),
        'email' => $settings['email'] ?? null,
        'address' => ['@type' => 'PostalAddress', 'streetAddress' => $settings['address'] ?? '', 'addressLocality' => 'Garut', 'addressRegion' => 'Jawa Barat', 'addressCountry' => 'ID'],
        'areaServed' => 'Garut',
        'brand' => ['@type' => 'Brand', 'name' => 'Honda'],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
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

        <nav class="hidden items-center gap-1 md:flex" aria-label="Menu utama">
            @foreach($nav as [$route, $label, $pattern])
                <a href="{{ route($route) }}" class="rounded-lg px-4 py-2 text-sm font-semibold transition {{ request()->routeIs($pattern) ? 'bg-honda text-white' : 'text-zinc-700 hover:bg-zinc-100' }}">{{ $label }}</a>
            @endforeach
        </nav>

        <button class="rounded-lg p-2 text-zinc-700 md:hidden" @click="menu = !menu" aria-label="Buka menu">
            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>
    <nav x-show="menu" x-cloak class="border-t border-zinc-100 bg-white px-4 py-3 md:hidden" aria-label="Menu mobile">
        @foreach($nav as [$route, $label, $pattern])
            <a href="{{ route($route) }}" class="block rounded-lg px-3 py-3 text-sm font-semibold {{ request()->routeIs($pattern) ? 'bg-red-50 text-honda' : 'text-zinc-700' }}">{{ $label }}</a>
        @endforeach
    </nav>
</header>

<main>@yield('content')</main>

<footer class="mt-20 bg-zinc-900 text-zinc-300">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 md:grid-cols-3">
        <div>
            <p class="text-lg font-extrabold text-white">DealerMotorHondaGarut.id</p>
            <p class="mt-3 text-sm leading-relaxed">Dealer motor Honda Garut resmi, CV. Surya Wijaya Sejahtera. Pricelist, promo, simulasi kredit, dan konsultasi pembelian motor Honda.</p>
            <div class="mt-4 flex flex-wrap gap-3 text-sm">
                @foreach(['instagram' => 'Instagram', 'facebook' => 'Facebook', 'tiktok' => 'TikTok', 'youtube' => 'YouTube'] as $k => $label)
                    @if(! empty($settings[$k]))<a href="{{ $settings[$k] }}" target="_blank" rel="noopener" class="underline-offset-4 hover:text-white hover:underline">{{ $label }}</a>@endif
                @endforeach
            </div>
        </div>
        <div>
            <p class="font-bold text-white">Kontak dealer</p>
            <ul class="mt-3 space-y-2 text-sm">
                @if(! empty($settings['address']))<li>{{ $settings['address'] }}</li>@endif
                <li>WhatsApp: <a class="hover:text-white" href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener">+{{ $waNumber }}</a></li>
                @if(! empty($settings['phone']))<li>Telepon: {{ $settings['phone'] }}</li>@endif
                @if(! empty($settings['email']))<li><a class="hover:text-white" href="mailto:{{ $settings['email'] }}">{{ $settings['email'] }}</a></li>@endif
                @if(! empty($settings['map_url']))<li><a class="underline-offset-4 hover:text-white hover:underline" href="{{ $settings['map_url'] }}" target="_blank" rel="noopener">Lihat lokasi di Google Maps</a></li>@endif
            </ul>
            @if(! empty($settings['hours']))
                <p class="mt-5 font-bold text-white">Jam operasional</p>
                <p class="mt-2 whitespace-pre-line text-sm">{{ $settings['hours'] }}</p>
            @endif
        </div>
        <div>
            <p class="font-bold text-white">Menu</p>
            <ul class="mt-3 space-y-2 text-sm">
                @foreach($nav as [$route, $label])<li><a class="hover:text-white" href="{{ route($route) }}">{{ $label }}</a></li>@endforeach
            </ul>
        </div>
    </div>
    <p class="border-t border-zinc-800 py-5 text-center text-xs text-zinc-500">&copy; {{ date('Y') }} DealerMotorHondaGarut.id - CV. Surya Wijaya Sejahtera. Seluruh hak cipta dilindungi.</p>
</footer>

{{-- Tombol WhatsApp melayang (semua halaman) --}}
<div x-data="waLead('{{ route('prospect.wa') }}')" @keydown.escape.window="open = false">
    <button @click="open = true" class="fixed bottom-5 right-5 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-green-500 text-white shadow-lg transition hover:scale-110 hover:bg-green-600 md:bottom-8 md:right-8 md:h-16 md:w-16" aria-label="Chat via WhatsApp">
        <svg class="h-8 w-8 md:h-9 md:w-9" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
    </button>
    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-end justify-center bg-black/50 p-4 sm:items-center">
        <form @submit.prevent="submit" @click.outside="open = false" class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <h2 class="text-lg font-bold text-zinc-900">Chat dengan sales kami</h2>
            <p class="mt-1 text-sm text-zinc-500">Isi nama dan alamat Anda, lalu kami arahkan ke WhatsApp.</p>
            <label class="mt-4 block text-sm font-semibold">Nama
                <input x-model="name" type="text" maxlength="100" class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5 focus:border-honda focus:ring-honda">
            </label>
            <label class="mt-3 block text-sm font-semibold">Alamat
                <input x-model="address" type="text" maxlength="255" class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5 focus:border-honda focus:ring-honda">
            </label>
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

@stack('scripts')
</body>
</html>
