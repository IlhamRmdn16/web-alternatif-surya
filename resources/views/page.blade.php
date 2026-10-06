@extends('layouts.frontend')

@section('title', ($page->meta_title ?: $page->title).' | DealerMotorHondaGarut.id')
@section('meta_description', $page->meta_description)

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
{{-- Banner: gambar sebagai latar, judul halaman statis di depannya (diatur di Admin > Halaman) --}}
<section class="relative isolate w-full overflow-hidden {{ $page->banner ? 'bg-zinc-900' : 'bg-gradient-to-br from-zinc-900 via-zinc-800 to-red-900' }}">
    @if($page->banner)
        <img src="{{ asset('storage/'.$page->banner) }}" alt="Banner {{ $page->title }}" class="absolute inset-0 -z-10 h-full w-full object-cover">
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-black/75 via-black/55 to-black/25"></div>
    @endif
    <div class="mx-auto flex min-h-[220px] max-w-7xl flex-col justify-center px-4 py-12 md:min-h-[320px] md:py-16">
        <nav class="text-xs text-white/70" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-white">Beranda</a> / <span class="text-white">{{ $page->title }}</span>
        </nav>
        <h1 class="mt-3 max-w-4xl text-3xl font-extrabold leading-tight tracking-tight text-white drop-shadow md:text-5xl">{{ $page->title }}</h1>
        <div class="mt-4 h-1 w-16 rounded bg-honda"></div>
    </div>
</section>

<article class="mx-auto max-w-7xl px-4 pt-10">
    <div class="page-content">{!! $page->content !!}</div>

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
