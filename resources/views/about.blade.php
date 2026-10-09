@extends('layouts.frontend')

@php
    $h = $about['hero']; $story = $about['story']; $svc = $about['services']; $vm = $about['vision']; $ar = $about['areas'];

    // Kata dalam judul yang diberi warna (aman: semua teks di-escape)
    $hl = trim($h['highlight'] ?? '');
    $titleHtml = e($h['title']);
    if ($hl !== '' && str_contains($h['title'], $hl)) {
        $titleHtml = str_replace(e($hl), '<span class="text-red-400">'.e($hl).'</span>', $titleHtml);
    }

    $paras = array_filter(array_map('trim', preg_split('/\R{2,}/', (string) $story['text'])));
    $missions = array_filter(array_map('trim', preg_split('/\R/', (string) $vm['missions'])));
    $areas = collect(preg_split('/\R/', (string) $ar['items']))->map(fn ($l) => trim(explode('|', $l, 2)[0]))->filter()
        ->map(fn ($n) => ['name' => $n])->values();
    $homeArea = trim((string) ($ar['home'] ?? ''));

    $heroPhoto = ! empty($h['photo']) ? asset('storage/'.$h['photo']) : null;
    $storyPhoto = ! empty($story['photo']) ? asset('storage/'.$story['photo']) : null;
@endphp

@section('title', ($about['seo']['title'] ?: 'Tentang Kami').' | Dealer Motor Honda Garut')
@section('meta_description', $about['seo']['description'])
@section('og_image', $heroPhoto)

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org', '@type' => 'AboutPage',
    'name' => 'Tentang Kami - CV. Surya Wijaya Sejahtera',
    'url' => route('about'),
    'about' => ['@type' => 'AutoDealer', 'name' => 'Dealer Motor Honda Garut - CV. Surya Wijaya Sejahtera', 'foundingDate' => '1991'],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
{{-- HERO --}}
<section class="relative isolate overflow-hidden bg-zinc-900 text-white">
    <div class="absolute inset-0 -z-10 bg-[radial-gradient(ellipse_at_85%_15%,rgba(214,0,28,0.35),transparent_55%)]"></div>
    <div class="pointer-events-none absolute -right-28 -top-28 -z-10 h-96 w-96 rounded-full border border-white/10"></div>
    <div class="pointer-events-none absolute -right-10 -top-10 -z-10 h-56 w-56 rounded-full border border-white/10"></div>

    <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 pb-24 pt-12 md:pb-32 md:pt-16 {{ $heroPhoto ? 'lg:grid-cols-2' : '' }}">
        <div>
            <p class="flex items-center gap-3 text-[11px] font-bold uppercase tracking-[0.2em] text-amber-300"><span class="h-px w-8 bg-amber-300/70"></span>{{ $h['eyebrow'] }}</p>
            <h1 class="mt-4 text-4xl font-extrabold leading-[1.1] tracking-tight sm:text-5xl lg:text-6xl">{!! $titleHtml !!}</h1>
            <p class="mt-5 max-w-xl text-base leading-relaxed text-zinc-300 md:text-lg">{{ $h['text'] }}</p>
            <div class="mt-8 flex flex-wrap gap-3">
                @if($h['btn1'])<a href="#cerita" data-scroll="cerita" class="inline-flex items-center gap-2 rounded-full bg-honda px-6 py-3 text-sm font-bold text-white transition hover:bg-honda-dark">{{ $h['btn1'] }} <span aria-hidden="true">&darr;</span></a>@endif
                @if($h['btn2'])<a href="#layanan" data-scroll="layanan" class="inline-flex items-center rounded-full border border-white/30 px-6 py-3 text-sm font-bold text-white transition hover:bg-white/10">{{ $h['btn2'] }}</a>@endif
            </div>
        </div>

        @if($heroPhoto)
            <div class="mx-auto w-full max-w-md lg:max-w-none">
                <div class="rotate-2 rounded-3xl border border-white/20 bg-white/10 p-2 shadow-2xl backdrop-blur">
                    <img src="{{ $heroPhoto }}" alt="{{ $h['title'] }}" class="aspect-[4/3] w-full rounded-2xl object-cover">
                </div>
            </div>
        @endif
    </div>
</section>

{{-- KARTU ANGKA --}}
<section class="relative z-10 mx-auto -mt-14 max-w-7xl px-4 md:-mt-16" aria-label="Ringkasan">
    <div class="grid gap-3 sm:grid-cols-3 md:gap-5">
        @foreach($about['stats'] as $st)
            <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-lg">
                <p class="text-2xl font-extrabold text-honda md:text-3xl">{{ $st['value'] }}</p>
                <p class="mt-1 text-sm font-bold text-zinc-900">{{ $st['label'] }}</p>
                <p class="text-xs text-zinc-500">{{ $st['desc'] }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- CERITA --}}
<section id="cerita" class="mx-auto max-w-7xl scroll-mt-24 px-4 pt-16 md:pt-24 lg:scroll-mt-28">
    <div class="grid items-center gap-8 lg:gap-14 {{ $storyPhoto ? 'lg:grid-cols-2' : '' }}">
        @if($storyPhoto)
            <img src="{{ $storyPhoto }}" alt="{{ $story['title'] }}" loading="lazy" class="aspect-[4/3] w-full rounded-3xl object-cover shadow-xl">
        @endif
        <div>
            <p class="flex items-center gap-3 text-[11px] font-bold uppercase tracking-[0.2em] text-honda"><span class="h-px w-8 bg-honda/60"></span>{{ $story['eyebrow'] }}</p>
            <h2 class="mt-3 text-2xl font-extrabold leading-tight tracking-tight text-zinc-900 md:text-4xl">{{ $story['title'] }}</h2>
            <div class="mt-5 space-y-4 text-sm leading-relaxed text-zinc-600 md:text-base">
                @foreach($paras as $p)<p>{{ $p }}</p>@endforeach
            </div>
        </div>
    </div>
</section>

{{-- LAYANAN --}}
<section id="layanan" class="mt-16 scroll-mt-20 bg-zinc-50 py-16 md:mt-24 md:py-20 lg:scroll-mt-24">
    <div class="mx-auto max-w-7xl px-4">
        <p class="flex items-center gap-3 text-[11px] font-bold uppercase tracking-[0.2em] text-honda"><span class="h-px w-8 bg-honda/60"></span>{{ $svc['eyebrow'] }}</p>
        <h2 class="mt-3 text-3xl font-extrabold leading-tight tracking-tight text-zinc-900 md:text-5xl">{{ $svc['title'] }}</h2>
        <p class="mt-4 text-sm leading-relaxed text-zinc-600 md:text-base">{{ $svc['intro'] }}</p>

        <div class="mt-8 grid gap-4 md:grid-cols-3 md:gap-6">
            @foreach($svc['items'] as $it)
                <div class="flex flex-col rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 text-sm font-extrabold text-honda">{{ $it['code'] }}</span>
                    <h3 class="mt-4 text-lg font-bold text-zinc-900">{{ $it['title'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-zinc-600">{{ $it['text'] }}</p>
                    @if($it['chip'])<div class="mt-auto pt-5"><span class="inline-block rounded-full bg-red-50 px-3 py-1.5 text-xs font-bold text-honda">{{ $it['chip'] }}</span></div>@endif
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- VISI & MISI --}}
<section class="mx-auto max-w-7xl px-4 pt-16 md:pt-24">
    <p class="flex items-center gap-3 text-[11px] font-bold uppercase tracking-[0.2em] text-honda"><span class="h-px w-8 bg-honda/60"></span>{{ $vm['eyebrow'] }}</p>
    <h2 class="mt-3 text-3xl font-extrabold leading-tight tracking-tight text-zinc-900 md:text-5xl">{{ $vm['title'] }}</h2>

    <div class="mt-8 grid gap-4 md:grid-cols-2 md:gap-6">
        <div class="rounded-3xl bg-zinc-50 p-6 md:p-8">
            <h3 class="text-2xl font-bold text-zinc-900">Visi</h3>
            <p class="mt-3 text-sm font-bold leading-relaxed text-zinc-900 md:text-base">&ldquo;{{ $vm['vision_quote'] }}&rdquo;</p>
            <p class="mt-3 text-sm leading-relaxed text-zinc-600 md:text-base">{{ $vm['vision_text'] }}</p>
        </div>
        <div class="rounded-3xl bg-zinc-50 p-6 md:p-8">
            <h3 class="text-2xl font-bold text-zinc-900">Misi</h3>
            <ol class="mt-3 list-decimal space-y-3 pl-5 text-sm leading-relaxed text-zinc-600 marker:font-semibold marker:text-honda md:text-base">
                @foreach($missions as $m)<li>{{ $m }}</li>@endforeach
            </ol>
        </div>
    </div>
</section>

{{-- JANGKAUAN LAYANAN: daftar kecamatan --}}
@if($areas->isNotEmpty())
<section class="mx-auto max-w-7xl px-4 pt-16 md:pt-24"
         x-data="{ q: '', all: {{ Js::from($areas->map(fn ($a) => Str::lower($a['name']))->values()) }},
                   get shown() { const s = this.q.trim().toLowerCase(); return s === '' ? this.all.length : this.all.filter(n => n.includes(s)).length; } }">
    <p class="flex items-center gap-3 text-[11px] font-bold uppercase tracking-[0.2em] text-honda"><span class="h-px w-8 bg-honda/60"></span>{{ $ar['eyebrow'] }}</p>
    <h2 class="mt-3 text-3xl font-extrabold leading-tight tracking-tight text-zinc-900 md:text-5xl">{{ $ar['title'] }}</h2>
    <p class="mt-4 text-sm leading-relaxed text-zinc-600 md:text-base">{{ $ar['intro'] }}</p>

    <div class="mt-8 rounded-3xl border border-zinc-200 bg-white p-5 shadow-sm md:p-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-50 text-xl font-extrabold text-honda">{{ $areas->count() }}</span>
                <div>
                    <p class="text-sm font-bold text-zinc-900">Kecamatan terlayani</p>
                    <p class="text-xs text-zinc-500">di seluruh Kabupaten Garut</p>
                </div>
            </div>
            <input x-model="q" type="search" placeholder="Cari kecamatan..." aria-label="Cari kecamatan" class="w-full rounded-full border border-zinc-300 px-5 py-2.5 text-sm sm:max-w-xs">
        </div>

        <ul class="mt-6 grid grid-cols-2 gap-2 sm:grid-cols-3 lg:grid-cols-5 xl:grid-cols-6">
            @foreach($areas as $i => $a)
                @php($isHome = $homeArea !== '' && Str::lower($a['name']) === Str::lower($homeArea))
                <li x-show="q.trim() === '' || {{ Js::from(Str::lower($a['name'])) }}.includes(q.trim().toLowerCase())"
                    class="flex items-center gap-2.5 rounded-xl border px-3 py-2.5 text-sm {{ $isHome ? 'border-honda bg-red-50 font-bold text-honda' : 'border-zinc-200 bg-zinc-50 font-medium text-zinc-700' }}">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-[11px] font-bold {{ $isHome ? 'bg-honda text-white' : 'bg-white text-zinc-400 ring-1 ring-zinc-200' }}">{{ $i + 1 }}</span>
                    <span class="min-w-0">
                        <span class="block truncate">{{ $a['name'] }}</span>
                        @if($isHome)<span class="block text-[10px] font-semibold uppercase tracking-wide">Lokasi dealer</span>@endif
                    </span>
                </li>
            @endforeach
        </ul>
        <p x-show="shown === 0" x-cloak class="mt-4 text-center text-sm text-zinc-500">Kecamatan tidak ditemukan.</p>
    </div>

    <a href="{{ route('contact') }}" class="mt-6 inline-flex items-center gap-2 text-sm font-bold text-honda hover:text-zinc-900">Lihat peta & kontak dealer <span aria-hidden="true">&rarr;</span></a>
</section>
@endif

{{-- AJAKAN --}}
<section class="mx-auto max-w-7xl px-4 pt-16 md:pt-24">
    <div class="rounded-2xl bg-zinc-900 p-6 text-white md:p-8">
        <p class="text-lg font-extrabold">Ingin tahu lebih banyak?</p>
        <p class="mt-1 text-sm text-zinc-400">Tim sales counter kami siap membantu Anda.</p>
        <div class="mt-5 flex flex-wrap gap-3">
            <button type="button" x-data @click="$dispatch('open-wa')" class="rounded-xl bg-green-500 px-6 py-3 text-sm font-bold text-white hover:bg-green-600">Chat Sales Counter</button>
            <a href="{{ route('contact') }}" class="rounded-xl border border-zinc-600 px-6 py-3 text-sm font-bold hover:border-white">Kontak & Lokasi</a>
            <a href="{{ route('pricelist') }}" class="rounded-xl border border-zinc-600 px-6 py-3 text-sm font-bold hover:border-white">Lihat Daftar Harga</a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Tombol hero: gulir halus ke bagian yang dituju
    document.querySelectorAll('[data-scroll]').forEach(function (a) {
        a.addEventListener('click', function (e) {
            var target = document.getElementById(a.dataset.scroll);
            if (!target) return;
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            history.replaceState(null, '', '#' + a.dataset.scroll);
        });
    });
</script>
@endpush
