@extends('layouts.frontend')

@section('title', ($page->meta_title ?: $page->title).' | DealerMotorHondaGarut.id')
@section('meta_description', $page->meta_description)

@php
    $images = collect($page->images ?? [])->filter()->values();
    $hasAside = $images->isNotEmpty();
@endphp

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
<article class="mx-auto px-4 pt-10 {{ $hasAside ? 'max-w-6xl' : 'max-w-3xl' }}">
    <nav class="text-xs text-zinc-500" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-honda">Beranda</a> / <span class="text-zinc-800">{{ $page->title }}</span>
    </nav>
    <h1 class="mt-4 text-2xl font-extrabold text-zinc-900 md:text-4xl">{{ $page->title }}</h1>
    <div class="mt-3 h-1 w-14 rounded bg-honda"></div>

    <div class="mt-8 {{ $hasAside ? 'grid items-start gap-8 lg:grid-cols-3 lg:gap-12' : '' }}">
        <div class="page-content {{ $hasAside ? 'lg:col-span-2' : '' }}">{!! $page->content !!}</div>

        {{-- Foto di sebelah kanan teks (diatur di Admin > Halaman) --}}
        @if($hasAside)
            <aside class="space-y-4 lg:sticky lg:top-28" aria-label="Foto">
                @foreach($images as $img)
                    <img src="{{ asset('storage/'.$img) }}" alt="{{ $page->title }} - foto {{ $loop->iteration }}" loading="{{ $loop->first ? 'eager' : 'lazy' }}" class="w-full rounded-2xl object-cover shadow-md">
                @endforeach
            </aside>
        @endif
    </div>

    <p class="mt-10 text-xs text-zinc-400">Terakhir diperbarui: {{ $page->updated_at->translatedFormat('d F Y') }}</p>

    @if($page->slug !== 'kebijakan-privasi')
        <div class="mt-10 rounded-2xl bg-zinc-900 p-6 text-white md:p-8">
            <p class="text-lg font-extrabold">Masih ada pertanyaan?</p>
            <p class="mt-1 text-sm text-zinc-400">Tim sales counter kami siap membantu Anda.</p>
            <div class="mt-5 flex flex-wrap gap-3">
                <button type="button" x-data @click="$dispatch('open-wa')" class="rounded-xl bg-green-500 px-6 py-3 text-sm font-bold text-white hover:bg-green-600">Chat Sales Counter</button>
                <a href="{{ route('contact') }}" class="rounded-xl border border-zinc-600 px-6 py-3 text-sm font-bold hover:border-white">Kontak & Lokasi</a>
                <a href="{{ route('faq') }}" class="rounded-xl border border-zinc-600 px-6 py-3 text-sm font-bold hover:border-white">Lihat FAQ</a>
            </div>
        </div>
    @endif
</article>
@endsection
