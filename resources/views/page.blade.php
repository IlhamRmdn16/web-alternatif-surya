@extends('layouts.frontend')

@section('title', ($page->meta_title ?: $page->title).' | DealerMotorHondaGarut.id')
@section('meta_description', $page->meta_description)

@php($photo = $page->banner ? asset('storage/'.$page->banner) : null)

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org', '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => route('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => $page->title],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
{{-- HERO (konsep sama dengan halaman Tentang Kami) --}}
<section class="relative isolate overflow-hidden bg-zinc-900 text-white">
    <div class="absolute inset-0 -z-10 bg-[radial-gradient(ellipse_at_85%_15%,rgba(214,0,28,0.35),transparent_55%)]"></div>
    <div class="pointer-events-none absolute -right-28 -top-28 -z-10 h-96 w-96 rounded-full border border-white/10"></div>
    <div class="pointer-events-none absolute -right-10 -top-10 -z-10 h-56 w-56 rounded-full border border-white/10"></div>

    <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 pb-24 pt-12 md:pb-32 md:pt-16 {{ $photo ? 'lg:grid-cols-2' : '' }}">
        <div>
            <nav class="text-xs text-white/60" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white">Beranda</a> / <span class="text-white">{{ $page->title }}</span>
            </nav>
            <p class="mt-6 flex items-center gap-3 text-[11px] font-bold uppercase tracking-[0.2em] text-amber-300"><span class="h-px w-8 bg-amber-300/70"></span>Transparansi data</p>
            <h1 class="mt-4 text-4xl font-extrabold leading-[1.1] tracking-tight sm:text-5xl lg:text-6xl">{{ $page->title }}</h1>
            @if($page->meta_description)<p class="mt-5 max-w-xl text-base leading-relaxed text-zinc-300 md:text-lg">{{ $page->meta_description }}</p>@endif
        </div>

        @if($photo)
            <div class="mx-auto w-full max-w-md lg:max-w-none">
                <div class="rotate-2 rounded-3xl border border-white/20 bg-white/10 p-2 shadow-2xl backdrop-blur">
                    <img src="{{ $photo }}" alt="{{ $page->title }}" class="aspect-[4/3] w-full rounded-2xl object-cover">
                </div>
            </div>
        @endif
    </div>
</section>

{{-- ISI: kartu putih di atas latar motif titik --}}
<div class="bg-zinc-50 bg-[radial-gradient(#d4d4d8_1px,transparent_1px)] [background-size:20px_20px] pb-16">
    <article class="relative z-10 mx-auto -mt-14 max-w-7xl px-4 md:-mt-16">
        <div class="page-content policy-content rounded-3xl border border-zinc-200 bg-white p-6 shadow-xl md:p-10">{!! $page->content !!}</div>
        <p class="mt-6 text-xs text-zinc-500">Terakhir diperbarui: {{ $page->updated_at->translatedFormat('d F Y') }}</p>

        @if($page->slug !== 'kebijakan-privasi')
            <div class="mt-10 rounded-2xl bg-zinc-900 p-6 text-white md:p-8">
                <p class="text-lg font-extrabold">Masih ada pertanyaan?</p>
                <p class="mt-1 text-sm text-zinc-400">Tim sales counter kami siap membantu Anda.</p>
                <div class="mt-5 flex flex-wrap gap-3">
                    <button type="button" x-data @click="$dispatch('open-wa')" class="rounded-xl bg-green-500 px-6 py-3 text-sm font-bold text-white hover:bg-green-600">Chat Sales Counter</button>
                    <a href="{{ route('contact') }}" class="rounded-xl border border-zinc-600 px-6 py-3 text-sm font-bold hover:border-white">Kontak & Lokasi</a>
                </div>
            </div>
        @endif
    </article>
</div>
@endsection
