@extends('layouts.frontend')

@php
    $coverUrl = $post->cover ? asset('storage/'.$post->cover) : null;
    $date = $post->published_at ?? $post->created_at;
@endphp

@section('title', $post->title.' | Berita Dealer Motor Honda Garut')
@section('meta_description', $post->summary)
@section('og_image', $coverUrl)
@section('og_type', 'article')

@push('head')
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@context' => 'https://schema.org', '@type' => 'Article',
    'headline' => $post->title,
    'description' => $post->summary,
    'image' => $coverUrl,
    'datePublished' => $date->toAtomString(),
    'dateModified' => $post->updated_at->toAtomString(),
    'mainEntityOfPage' => route('news.show', $post),
    'author' => ['@type' => 'Organization', 'name' => 'CV. Surya Wijaya Sejahtera'],
    'publisher' => ['@type' => 'Organization', 'name' => 'Dealer Motor Honda Garut'],
]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org', '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => route('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Berita', 'item' => route('news.index')],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $post->title],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<article class="mx-auto max-w-6xl px-4 pt-10">
    <nav class="text-xs text-zinc-500" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-honda">Beranda</a> /
        <a href="{{ route('news.index') }}" class="hover:text-honda">Berita</a>
    </nav>

    {{-- flow-root: membungkus float agar tinggi container benar --}}
    <div class="flow-root">
        @if($coverUrl)
            {{-- Gambar HARUS ditaruh sebelum judul & isi agar float bekerja --}}
            <img src="{{ $coverUrl }}" alt="{{ $post->title }}"
                 class="mt-6 w-full rounded-2xl md:float-right md:ml-8 md:mb-4 md:mt-4 md:w-5/12">
        @endif

        <h1 class="mt-4 text-2xl font-extrabold leading-tight text-zinc-900 md:text-4xl">{{ $post->title }}</h1>
        <p class="mt-3 text-sm text-zinc-500">{{ $date->translatedFormat('d F Y') }} &middot; CV. Surya Wijaya Sejahtera</p>

        <div class="page-content mt-8 md:mt-6">{!! $post->content !!}</div>
    </div>

    <div class="clear-both mt-10 rounded-2xl bg-zinc-900 p-6 text-white md:p-8">
        <p class="text-lg font-extrabold">Tertarik dengan motor Honda?</p>
        <p class="mt-1 text-sm text-zinc-400">Lihat harga terbaru atau konsultasikan langsung dengan sales counter kami.</p>
        <div class="mt-5 flex flex-wrap gap-3">
            <a href="{{ route('pricelist') }}" class="rounded-xl bg-honda px-6 py-3 text-sm font-bold hover:bg-honda-dark">Lihat Daftar Harga</a>
            <button type="button" x-data @click="$dispatch('open-wa', {{ Js::from(['topic' => $post->title]) }})" class="rounded-xl bg-green-500 px-6 py-3 text-sm font-bold hover:bg-green-600">Chat Sales Counter</button>
        </div>
    </div>
</article>

@if($related->isNotEmpty())
<section class="mx-auto max-w-7xl px-4 pt-16">
    <h2 class="text-xl font-extrabold text-zinc-900 md:text-2xl">Berita lainnya</h2>
    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 lg:gap-6">
        @foreach($related as $post) @include('partials.post-card') @endforeach
    </div>
</section>
@endif
@endsection
