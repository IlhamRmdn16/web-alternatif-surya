<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin') - Admin DealerMotorHondaGarut</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php
    $menu = [
        ['admin.dashboard', 'Dashboard', 'admin.dashboard'],
        ['admin.prospects.index', 'Prospek', 'admin.prospects.*'],
        ['admin.motors.index', 'Motor & Harga', 'admin.motors.*'],
        ['admin.categories.index', 'Jenis Motor', 'admin.categories.*'],
        ['admin.banners.index', 'Foto Beranda', 'admin.banners.*'],
        ['admin.promos.index', 'Promo', 'admin.promos.*'],
        ['admin.faqs.index', 'FAQ', 'admin.faqs.*'],
        ['admin.posts.index', 'Berita', 'admin.posts.*'],
        ['admin.about.edit', 'Tentang Kami', 'admin.about.*'],
        ['admin.pages.index', 'Halaman', 'admin.pages.*'],
        ['admin.sales.index', 'Sales Counter', 'admin.sales.*'],
        ['admin.settings.edit', 'Pengaturan', 'admin.settings.*'],
    ];
    $newProspects = \App\Models\Prospect::where('status', 'baru')->count();
@endphp
<body class="bg-zinc-100 font-sans text-zinc-800" x-data="{ side: false }">
<div class="flex min-h-screen">
    <aside :class="side ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-40 w-64 transform overflow-y-auto bg-zinc-900 p-5 transition md:sticky md:top-0 md:h-screen md:shrink-0 md:translate-x-0 md:self-start">
        <p class="text-lg font-extrabold text-white">Admin <span class="text-red-500">Dealer</span></p>
        <nav class="mt-8 space-y-1">
            @foreach($menu as [$route, $label, $pattern])
                <a href="{{ route($route) }}" class="flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-semibold {{ request()->routeIs($pattern) ? 'bg-red-600 text-white' : 'text-zinc-300 hover:bg-zinc-800' }}">
                    {{ $label }}
                    @if($route === 'admin.prospects.index' && $newProspects)<span class="rounded-full bg-white px-2 text-xs font-bold text-red-600">{{ $newProspects }}</span>@endif
                </a>
            @endforeach
        </nav>
        <div class="mt-8 space-y-2 border-t border-zinc-800 pt-5 text-sm">
            <a href="{{ route('home') }}" target="_blank" class="block text-zinc-400 hover:text-white">Lihat website</a>
            <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="text-zinc-400 hover:text-white">Keluar</button></form>
        </div>
    </aside>

    <div class="min-w-0 flex-1">
        <div class="flex items-center justify-between bg-white px-4 py-3 shadow-sm md:hidden">
            <button @click="side = !side" class="rounded p-1" aria-label="Menu"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg></button>
            <span class="font-bold">@yield('title', 'Admin')</span><span class="w-6"></span>
        </div>
        <main class="p-4 md:p-8">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <h1 class="text-2xl font-extrabold text-zinc-900">@yield('title')</h1>
                @yield('actions')
            </div>
            @if(session('ok'))<div class="mb-5 rounded-xl bg-green-100 px-4 py-3 text-sm font-semibold text-green-800">{{ session('ok') }}</div>@endif
            @if(session('err'))<div class="mb-5 rounded-xl bg-red-100 px-4 py-3 text-sm font-semibold text-red-800">{{ session('err') }}</div>@endif
            @if($errors->any() && ! isset($hideErrors))
                <div class="mb-5 rounded-xl bg-red-100 px-4 py-3 text-sm text-red-800"><ul class="list-disc pl-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
